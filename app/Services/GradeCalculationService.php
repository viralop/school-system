<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Level;
use App\Models\MonthlyExam;
use App\Models\Student;
use App\Models\Term;

class GradeCalculationService
{
    public function calculateTermPercentage(int $studentId, int $termId): ?array
    {
        $grades = Grade::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('status', 'approved')
            ->with(['subject'])
            ->get();

        if ($grades->isEmpty()) {
            return null;
        }

        $totalEarned = $grades->sum('score');
        $totalMax = $grades->sum(fn($g) => $g->subject->default_max_degree);
        $percentage = $totalMax > 0 ? ($totalEarned / $totalMax) * 100 : 0;

        return [
            'total_earned' => round($totalEarned, 2),
            'total_max' => round($totalMax, 2),
            'percentage' => round($percentage, 2),
            'grades' => $grades,
        ];
    }

    public function calculateLevelGrade(int $studentId, int $levelId): ?array
    {
        $terms = Term::where('level_id', $levelId)->orderBy('order')->get();

        if ($terms->isEmpty()) {
            return null;
        }

        $termResults = [];
        $percentages = [];

        foreach ($terms as $term) {
            $result = $this->calculateTermPercentage($studentId, $term->id);
            if ($result) {
                $termResults[$term->id] = [
                    'term' => $term,
                    'percentage' => $result['percentage'],
                    'total_earned' => $result['total_earned'],
                    'total_max' => $result['total_max'],
                ];
                $percentages[] = $result['percentage'];
            }
        }

        if (empty($percentages)) {
            return null;
        }

        $levelPercentage = count($percentages) > 0
            ? array_sum($percentages) / count($percentages)
            : 0;

        return [
            'level_percentage' => round($levelPercentage, 2),
            'passed' => $levelPercentage >= 50,
            'terms' => $termResults,
        ];
    }

    public function getLevelResults(int $levelId): array
    {
        $students = Student::where('level_id', $levelId)->get();
        $results = [];

        foreach ($students as $student) {
            $levelGrade = $this->calculateLevelGrade($student->id, $levelId);
            $results[] = [
                'student' => $student,
                'level_grade' => $levelGrade,
            ];
        }

        return $results;
    }

    public function calculateMonthlyExamResults(int $monthlyExamId): array
    {
        $exam = MonthlyExam::with('subjects.subject')->findOrFail($monthlyExamId);
        $students = Student::where('level_id', $exam->level_id)->orderBy('name')->get();
        $fullMark = $exam->subjects->sum('max_degree');

        $results = [];
        foreach ($students as $student) {
            $grades = Grade::where('student_id', $student->id)
                ->where('exam_type', 'monthly')
                ->where('exam_id', $exam->id)
                ->where('status', 'approved')
                ->get();

            $totalScore = $grades->sum('score');
            $percentage = $fullMark > 0 ? ($totalScore / $fullMark) * 100 : 0;

            $subjectResults = [];
            foreach ($exam->subjects as $examSubject) {
                $grade = $grades->where('subject_id', $examSubject->subject_id)->first();
                $subjectResults[] = [
                    'subject' => $examSubject->subject,
                    'max_degree' => $examSubject->max_degree,
                    'score' => $grade ? $grade->score : null,
                ];
            }

            $results[] = [
                'student' => $student,
                'total_score' => round($totalScore, 2),
                'full_mark' => round($fullMark, 2),
                'percentage' => round($percentage, 2),
                'subjects' => $subjectResults,
            ];
        }

        return [
            'exam' => $exam,
            'full_mark' => round($fullMark, 2),
            'results' => $results,
        ];
    }
}
