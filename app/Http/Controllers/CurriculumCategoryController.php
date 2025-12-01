<?php

namespace App\Http\Controllers;

use App\Models\CurriculumCategory;
// use GuzzleHttp\Middleware;
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
        return CurriculumCategory::with(['courses' => function($query) {
            $query->with('level');
        }])->get();
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


        // $category = CurriculumCategory::create($data);
        $category = $request->user()->curriculumCategories()->create($data);

        return ['status' => 200, 'message' => 'Category created successfully', 'response' => $category];
    }

    /**
     * Display the specified resource.
     */
    public function show(CurriculumCategory $category)
    {
        return ['status' => 200, 'response' => $category];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CurriculumCategory $category)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);

        // $category = CurriculumCategory::create($data);
        $category->update($data);

        return ['status' => 200, 'message' => 'Category Updated successfully', 'response' => $category];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CurriculumCategory $category)
    {
        $category->delete();

        return ['status' => 200, 'message' => 'Category deleted successfully'];
    }
}
