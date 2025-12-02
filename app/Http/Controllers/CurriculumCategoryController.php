<?php

namespace App\Http\Controllers;

use App\Models\CurriculumCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CurriculumCategoryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CurriculumCategory::with([
            'courses' => function($query) {
                $query->with('level');
            }
        ])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);

        // Attach to authenticated user
        $category = $request->user()->curriculumCategories()->create($data);

        return [
            'status' => 200,
            'message' => 'Curriculum Category created successfully',
            'response' => $category
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(CurriculumCategory $curriculumCategory)
    {
        return [
            'status' => 200,
            'response' => $curriculumCategory
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CurriculumCategory $curriculumCategory)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);

        $curriculumCategory->update($data);

        return [
            'status' => 200,
            'message' => 'Curriculum Category updated successfully',
            'response' => $curriculumCategory
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CurriculumCategory $curriculumCategory)
    {
        $curriculumCategory->delete();

        return [
            'status' => 200,
            'message' => 'Curriculum Category deleted successfully'
        ];
    }
}
