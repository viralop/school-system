<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Level;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectManagementController extends Controller
{
    public function index()
    {
        $levels = Level::with(['subjects.teacher'])->orderBy('order')->get();
        $teachers = User::where('role', 'teacher')->where('status', 'active')->orderBy('name')->get();

        return view('supervisor.subjects', compact('levels', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'default_max_degree' => ['required', 'numeric', 'min:1', 'max:9999'],
            'level_id' => ['required', 'exists:levels,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($request->filled('teacher_id')) {
            $teacher = User::find($request->teacher_id);
            if (!$teacher || !$teacher->isTeacher()) {
                return back()->withErrors(['teacher_id' => 'Invalid teacher selected.']);
            }
        }

        Subject::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'default_max_degree' => $request->default_max_degree,
            'level_id' => $request->level_id,
            'teacher_id' => $request->teacher_id ?: null,
        ]);

        return back()->with('success', "Subject '{$request->name_en}' added.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
            'level_id' => ['required', 'exists:levels,id'],
        ]);

        $levelId = $request->level_id;

        try {
            DB::beginTransaction();

            $rows = $this->parseCsv($request->file('file')->getRealPath());

            $imported = 0;
            $skipped = 0;

            foreach ($rows as $row) {
                $name = trim($row['name_en'] ?? $row['name'] ?? $row['subject'] ?? '');
                $nameAr = trim($row['name_ar'] ?? '');
                $maxDegree = floatval($row['default_max_degree'] ?? $row['max_degree'] ?? $row['max_score'] ?? 100);
                $teacherEmail = trim($row['teacher_email'] ?? $row['teacher'] ?? $row['email'] ?? '');

                if (empty($name)) {
                    $skipped++;
                    continue;
                }

                $teacherId = null;
                if (!empty($teacherEmail)) {
                    $teacher = User::where('email', $teacherEmail)->where('role', 'teacher')->first();
                    $teacherId = $teacher?->id;
                }

                Subject::create([
                    'name_en' => $name,
                    'name_ar' => $nameAr ?: null,
                    'default_max_degree' => $maxDegree ?: 100,
                    'level_id' => $levelId,
                    'teacher_id' => $teacherId,
                ]);
                $imported++;
            }

            DB::commit();

            $msg = "Imported {$imported} subject(s).";
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

    public function grades(Subject $subject)
    {
        $subject->load('level', 'teacher');

        $terms = Term::where('level_id', $subject->level_id)->orderBy('order')->get();
        $students = Student::where('level_id', $subject->level_id)->orderBy('name')->get();
        $grades = Grade::where('subject_id', $subject->id)->get();

        return view('supervisor.subject-grades', compact('subject', 'terms', 'students', 'grades'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'default_max_degree' => ['required', 'numeric', 'min:1', 'max:9999'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($request->filled('teacher_id')) {
            $teacher = User::find($request->teacher_id);
            if (!$teacher || !$teacher->isTeacher()) {
                return back()->withErrors(['teacher_id' => 'Invalid teacher selected.']);
            }
        }

        $subject->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'default_max_degree' => $request->default_max_degree,
            'teacher_id' => $request->teacher_id ?: null,
        ]);

        return back()->with('success', "Subject '{$subject->name_en}' updated.");
    }

    public function destroy(Subject $subject)
    {
        if ($subject->grades()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete subject with existing grades.']);
        }

        $subject->delete();

        return back()->with('success', 'Subject deleted.');
    }
}
