<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\MonthlyExam;
use App\Models\MonthlyExamSubject;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TeacherGradeController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $subjects = Subject::where('teacher_id', $teacher->id)->with(['level.terms'])->get();
        $monthlyExams = MonthlyExam::whereHas('subjects.subject', fn($q) => $q->where('teacher_id', $teacher->id))
            ->where('status', 'open')
            ->with(['subjects.subject', 'level'])
            ->get();

        return view('teacher.grades', compact('subjects', 'monthlyExams'));
    }

    public function showEntryForm(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'exam_type' => ['required', 'in:term,monthly'],
            'exam_id' => ['required'],
        ]);

        if ($request->exam_type === 'monthly') {
            return $this->showMonthlyEntryForm($teacher, $request->exam_id);
        }

        return $this->showTermEntryForm($teacher, $request->subject_id, $request->exam_id);
    }

    private function showTermEntryForm($teacher, $subjectId, $termId)
    {
        $subject = Subject::where('id', $subjectId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $term = Term::where('id', $termId)
            ->where('level_id', $subject->level_id)
            ->firstOrFail();

        if (!$term->isOpen()) {
            throw ValidationException::withMessages([
                'exam_id' => 'This term is closed for grade entry.',
            ]);
        }

        $students = Student::where('level_id', $subject->level_id)
            ->with(['grades' => fn($q) => $q->where('subject_id', $subject->id)
                ->where('term_id', $term->id)
                ->where('exam_type', 'term')])
            ->orderBy('name')
            ->get();

        return view('teacher.grade-entry', [
            'subject' => $subject,
            'term' => $term,
            'students' => $students,
            'examType' => 'term',
            'examLabel' => $term->localizedName,
            'maxDegree' => $subject->default_max_degree,
        ]);
    }

    private function showMonthlyEntryForm($teacher, $examId)
    {
        $exam = MonthlyExam::with(['subjects.subject'])->findOrFail($examId);

        if (!$exam->isOpen()) {
            throw ValidationException::withMessages([
                'exam_id' => 'This exam is closed.',
            ]);
        }

        $teacherSubjectIds = Subject::where('teacher_id', $teacher->id)->pluck('id');
        $examSubject = $exam->subjects->first(fn($s) => $teacherSubjectIds->contains($s->subject_id));

        if (!$examSubject) {
            throw ValidationException::withMessages([
                'exam_id' => 'You are not assigned to any subject in this exam.',
            ]);
        }

        $subject = $examSubject->subject;

        $students = Student::where('level_id', $exam->level_id)
            ->with(['grades' => fn($q) => $q->where('subject_id', $subject->id)
                ->where('exam_type', 'monthly')
                ->where('exam_id', $exam->id)])
            ->orderBy('name')
            ->get();

        return view('teacher.grade-entry', [
            'subject' => $subject,
            'monthlyExam' => $exam,
            'students' => $students,
            'examType' => 'monthly',
            'examLabel' => $exam->localizedName,
            'maxDegree' => $examSubject->max_degree,
        ]);
    }

    public function store(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'exam_type' => ['required', 'in:term,monthly'],
            'exam_id' => ['required'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'grades' => ['required', 'array'],
            'grades.*.student_id' => ['required', 'exists:students,id'],
            'grades.*.score' => ['required', 'numeric', 'min:0'],
        ]);

        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        if ($request->exam_type === 'monthly') {
            return $this->storeMonthlyGrades($teacher, $subject, $request);
        }

        return $this->storeTermGrades($teacher, $subject, $request);
    }

    private function storeTermGrades($teacher, $subject, $request)
    {
        $term = Term::where('id', $request->exam_id)
            ->where('level_id', $subject->level_id)
            ->firstOrFail();

        if (!$term->isOpen()) {
            throw ValidationException::withMessages(['grades' => 'This term is closed for grade entry.']);
        }

        $saved = 0;
        foreach ($request->grades as $gradeData) {
            if ($gradeData['score'] > $subject->default_max_degree) {
                throw ValidationException::withMessages([
                    'grades' => "Score for student {$gradeData['student_id']} exceeds max degree of {$subject->default_max_degree}.",
                ]);
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'subject_id' => $subject->id,
                    'exam_type' => 'term',
                    'exam_id' => $term->id,
                ],
                [
                    'term_id' => $term->id,
                    'score' => $gradeData['score'],
                    'status' => 'pending',
                    'entered_by' => $teacher->id,
                    'approved_by' => null,
                    'approved_at' => null,
                ],
            );
            $saved++;
        }

        return redirect()->route('teacher.grades')
            ->with('success', "{$saved} grade(s) saved and submitted for approval.");
    }

    private function storeMonthlyGrades($teacher, $subject, $request)
    {
        $exam = MonthlyExam::findOrFail($request->exam_id);

        if (!$exam->isOpen()) {
            throw ValidationException::withMessages(['grades' => 'This exam is closed.']);
        }

        $examSubject = MonthlyExamSubject::where('monthly_exam_id', $exam->id)
            ->where('subject_id', $subject->id)
            ->first();

        if (!$examSubject) {
            throw ValidationException::withMessages(['grades' => 'Subject not part of this exam.']);
        }

        $saved = 0;
        foreach ($request->grades as $gradeData) {
            if ($gradeData['score'] > $examSubject->max_degree) {
                throw ValidationException::withMessages([
                    'grades' => "Score for student {$gradeData['student_id']} exceeds max degree of {$examSubject->max_degree}.",
                ]);
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'subject_id' => $subject->id,
                    'exam_type' => 'monthly',
                    'exam_id' => $exam->id,
                ],
                [
                    'term_id' => null,
                    'score' => $gradeData['score'],
                    'status' => 'pending',
                    'entered_by' => $teacher->id,
                    'approved_by' => null,
                    'approved_at' => null,
                ],
            );
            $saved++;
        }

        return redirect()->route('teacher.grades')
            ->with('success', "{$saved} grade(s) saved and submitted for approval.");
    }
}
