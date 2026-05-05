<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Level;
use App\Models\MonthlyExam;
use App\Models\MonthlyExamSubject;
use App\Models\Student;
use App\Models\Subject;
use App\Services\GradeCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyExamController extends Controller
{
    public function __construct(
        private GradeCalculationService $calcService,
    ) {}

    public function index(Request $request)
    {
        $levels = Level::orderBy('order')->get();
        $selectedLevel = $request->filled('level_id') ? Level::find($request->level_id) : $levels->first();

        $exams = collect();
        if ($selectedLevel) {
            $exams = MonthlyExam::where('level_id', $selectedLevel->id)
                ->with(['subjects.subject', 'level'])
                ->orderByDesc('date')
                ->get();
        }

        return view('supervisor.monthly-exams', compact('levels', 'selectedLevel', 'exams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'level_id' => ['required', 'exists:levels,id'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*.subject_id' => ['required', 'exists:subjects,id'],
            'subjects.*.max_degree' => ['required', 'numeric', 'min:1', 'max:9999'],
        ]);

        $exam = MonthlyExam::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'date' => $request->date,
            'level_id' => $request->level_id,
            'status' => 'open',
        ]);

        foreach ($request->subjects as $subjectData) {
            MonthlyExamSubject::create([
                'monthly_exam_id' => $exam->id,
                'subject_id' => $subjectData['subject_id'],
                'max_degree' => $subjectData['max_degree'],
            ]);
        }

        return back()->with('success', "Monthly exam '{$request->name_en}' created.");
    }

    public function update(Request $request, MonthlyExam $monthlyExam)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'subjects' => ['nullable', 'array'],
            'subjects.*.subject_id' => ['required', 'exists:subjects,id'],
            'subjects.*.max_degree' => ['required', 'numeric', 'min:1', 'max:9999'],
        ]);

        $monthlyExam->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'date' => $request->date,
        ]);

        if ($request->filled('subjects')) {
            $newSubjectIds = collect($request->subjects)->pluck('subject_id')->toArray();
            $monthlyExam->subjects()
                ->whereNotIn('subject_id', $newSubjectIds)
                ->get()
                ->each(fn($s) => $s->delete());

            foreach ($request->subjects as $subjectData) {
                MonthlyExamSubject::updateOrCreate(
                    [
                        'monthly_exam_id' => $monthlyExam->id,
                        'subject_id' => $subjectData['subject_id'],
                    ],
                    [
                        'max_degree' => $subjectData['max_degree'],
                    ],
                );
            }
        }

        return back()->with('success', 'Monthly exam updated.');
    }

    public function destroy(MonthlyExam $monthlyExam)
    {
        if ($monthlyExam->grades()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete exam with existing grades.']);
        }

        $monthlyExam->subjects()->delete();
        $monthlyExam->delete();

        return back()->with('success', 'Monthly exam deleted.');
    }

    public function close(MonthlyExam $monthlyExam)
    {
        $monthlyExam->close();
        return back()->with('success', 'Monthly exam closed.');
    }

    public function open(MonthlyExam $monthlyExam)
    {
        $monthlyExam->open();
        return back()->with('success', 'Monthly exam reopened.');
    }

    public function results(MonthlyExam $monthlyExam)
    {
        $data = $this->calcService->calculateMonthlyExamResults($monthlyExam->id);
        return view('supervisor.monthly-exam-results', $data);
    }

    public function review(MonthlyExam $monthlyExam)
    {
        $monthlyExam->load(['subjects.subject', 'level']);

        $students = Student::where('level_id', $monthlyExam->level_id)
            ->with(['grades' => fn($q) => $q->where('exam_type', 'monthly')->where('exam_id', $monthlyExam->id)])
            ->orderBy('name')
            ->get();

        return view('supervisor.monthly-exam-review', compact('monthlyExam', 'students'));
    }

    public function bulkApprove(Request $request, MonthlyExam $monthlyExam)
    {
        $request->validate([
            'grade_ids' => ['required', 'array'],
            'grade_ids.*' => ['exists:grades,id'],
        ]);

        $supervisorId = Auth::id();
        $count = 0;

        Grade::whereIn('id', $request->grade_ids)
            ->where('exam_type', 'monthly')
            ->where('exam_id', $monthlyExam->id)
            ->where('status', 'pending')
            ->each(function ($g) use ($supervisorId, &$count) {
                $g->approve($supervisorId);
                $count++;
            });

        return back()->with('success', "{$count} grade(s) approved.");
    }

    public function bulkReject(Request $request, MonthlyExam $monthlyExam)
    {
        $request->validate([
            'grade_ids' => ['required', 'array'],
            'grade_ids.*' => ['exists:grades,id'],
        ]);

        $supervisorId = Auth::id();
        $count = 0;

        Grade::whereIn('id', $request->grade_ids)
            ->where('exam_type', 'monthly')
            ->where('exam_id', $monthlyExam->id)
            ->where('status', 'pending')
            ->each(function ($g) use ($supervisorId, &$count) {
                $g->reject($supervisorId);
                $count++;
            });

        return back()->with('success', "{$count} grade(s) rejected.");
    }

    public function approveGrade(MonthlyExam $monthlyExam, Grade $grade)
    {
        $grade->approve(Auth::id());
        return back()->with('success', 'Grade approved.');
    }

    public function rejectGrade(MonthlyExam $monthlyExam, Grade $grade)
    {
        $grade->reject(Auth::id());
        return back()->with('success', 'Grade rejected.');
    }
}
