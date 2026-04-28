<?php

namespace App\Http\Controllers;

use App\Models\Grade;
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

        return view('teacher.grades', compact('subjects'));
    }

    public function showEntryForm(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'term_id' => ['required', 'exists:terms,id'],
        ]);

        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $term = Term::where('id', $request->term_id)
            ->where('level_id', $subject->level_id)
            ->firstOrFail();

        if (! $term->isOpen()) {
            throw ValidationException::withMessages([
                'term_id' => 'This term is closed for grade entry.',
            ]);
        }

        $subjectId = $subject->id;
        $termId = $term->id;

        $students = Student::where('level_id', $subject->level_id)
            ->with(['grades' => function ($q) use ($subjectId, $termId) {
                $q->where('subject_id', $subjectId)->where('term_id', $termId);
            }])
            ->orderBy('name')
            ->get();

        return view('teacher.grade-entry', compact('subject', 'term', 'students'));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'grades' => ['required', 'array'],
            'grades.*.student_id' => ['required', 'exists:students,id'],
            'grades.*.score' => ['required', 'numeric', 'min:0'],
        ]);

        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $term = Term::where('id', $request->term_id)
            ->where('level_id', $subject->level_id)
            ->firstOrFail();

        if (! $term->isOpen()) {
            throw ValidationException::withMessages([
                'grades' => 'This term is closed for grade entry.',
            ]);
        }

        $saved = 0;
        foreach ($request->grades as $gradeData) {
            if ($gradeData['score'] > $subject->max_score) {
                throw ValidationException::withMessages([
                    'grades' => "Score for student {$gradeData['student_id']} exceeds max score of {$subject->max_score}.",
                ]);
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'subject_id' => $subject->id,
                    'term_id' => $term->id,
                ],
                [
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
