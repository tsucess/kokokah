<?php

namespace App\Http\Controllers;

use App\Models\CurriculumCategory;
use Illuminate\Http\Request;

class CurriculumCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        return CurriculumCategory::with(['courses' => function($q) {
            $q->with('level');
        }])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);

        $curriculumCategory = $request->user()->curriculumCategories()->create($data);

        return [
            'status' => 200,
            'message' => 'Curriculum Category created successfully',
            'response' => $curriculumCategory
        ];
    }

    public function show($id)
    {
        $data = CurriculumCategory::findOrFail($id);
        return [
            'status' => 200,
            'response' => $data
        ];
    }

    public function update(Request $request,  $id)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);

        $curriculumCategory = CurriculumCategory::findOrFail($id);
        $curriculumCategory->update($data);

        return [
            'status' => 200,
            'message' => 'Curriculum Category Updated successfully',
            'response' => $curriculumCategory
        ];
    }

    public function destroy($id)
    {
        CurriculumCategory::findOrFail($id)->delete();

        return [
            'status' => 200,
            'message' => 'Curriculum Category deleted successfully'
        ];
    }
}
