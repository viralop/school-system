<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Level;
use App\Models\MonthlyExam;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Services\GradeCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function __construct(
        private GradeCalculationService $calcService,
    ) {}

    public function pending(Request $request)
    {
        $levels = Level::orderBy('order')->get();
        $query = Grade::where('status', 'pending')
            ->with(['student.level', 'student.section', 'subject', 'term', 'enteredBy']);

        if ($request->filled('level_id')) {
            $query->whereHas('student', fn($q) => $q->where('level_id', $request->level_id));
        }

        if ($request->filled('term_id')) {
            $query->where('term_id', $request->term_id);
        }

        $grades = $query->latest()->paginate(50)->withQueryString();

        return view('supervisor.grades-pending', compact('grades', 'levels'));
    }

    public function approve(Grade $grade)
    {
        $grade->approve(Auth::id());
        return back()->with('success', 'Grade approved.');
    }

    public function reject(Grade $grade)
    {
        $grade->reject(Auth::id());
        return back()->with('success', 'Grade rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'grade_ids' => ['nullable', 'array'],
            'grade_ids.*' => ['exists:grades,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ]);

        $supervisorId = Auth::id();

        if ($request->filled('subject_id')) {
            $grades = Grade::where('subject_id', $request->subject_id)
                ->where('status', 'pending')
                ->get();

            foreach ($grades as $g) {
                $g->approve($supervisorId);
            }

            return back()->with('success', 'All pending grades for this subject approved.');
        }

        if ($request->filled('grade_ids')) {
            Grade::whereIn('id', $request->grade_ids)
                ->where('status', 'pending')
                ->each(fn($g) => $g->approve($supervisorId));

            return back()->with('success', count($request->grade_ids) . " grade(s) approved.");
        }

        return back()->withErrors(['error' => 'No grades selected.']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
            'term_id' => ['required', 'exists:terms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        $term = Term::where('id', $request->term_id)
            ->where('level_id', $subject->level_id)
            ->firstOrFail();

        if (!$term->isOpen()) {
            return back()->withErrors(['file' => 'This term is closed for grade entry.']);
        }

        try {
            DB::beginTransaction();

            $rows = $this->parseCsv($request->file('file')->getRealPath());

            $imported = 0;
            $skipped = 0;
            $supervisorId = Auth::id();

            foreach ($rows as $row) {
                $studentNumber = trim($row['student_number'] ?? $row['number'] ?? $row['id'] ?? '');
                $score = floatval($row['score'] ?? $row['grade'] ?? $row['degree'] ?? 0);

                if (empty($studentNumber)) {
                    $skipped++;
                    continue;
                }

                $student = Student::where('student_number', $studentNumber)
                    ->where('level_id', $subject->level_id)
                    ->first();

                if (!$student) {
                    $skipped++;
                    continue;
                }

                if ($score > $subject->default_max_degree) {
                    $skipped++;
                    continue;
                }

                Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'exam_type' => 'term',
                        'exam_id' => $term->id,
                    ],
                    [
                        'term_id' => $term->id,
                        'score' => $score,
                        'status' => 'pending',
                        'entered_by' => $supervisorId,
                        'approved_by' => null,
                        'approved_at' => null,
                    ],
                );
                $imported++;
            }

            DB::commit();

            $msg = "Imported {$imported} grade(s).";
            if ($skipped > 0) {
                $msg .= " Skipped {$skipped} row(s).";
            }

            return back()->with('success', $msg);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $headers = array_map(fn($h) => strtolower(trim(str_replace([' ', '-', '_'], '', $h))), $headers);

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === count($headers)) {
                $rows[] = array_combine($headers, $data);
            }
        }
        fclose($handle);

        return $rows;
    }

    public function results(Request $request)
    {
        $levels = Level::orderBy('order')->get();
        $selectedLevel = $request->filled('level_id') ? Level::find($request->level_id) : $levels->first();

        $results = $selectedLevel ? $this->calcService->getLevelResults($selectedLevel->id) : [];

        return view('supervisor.results', compact('levels', 'selectedLevel', 'results'));
    }
}
