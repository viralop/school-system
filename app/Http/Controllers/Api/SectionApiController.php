<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sections = Section::where('level_id', $request->level_id)
            ->orderBy('name')
            ->get(['id', 'name', 'level_id']);

        return response()->json($sections);
    }
}
