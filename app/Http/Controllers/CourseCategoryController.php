<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
// use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseCategoryController extends Controller implements HasMiddleware
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
        return CourseCategory::with(['courses' => function($query) {
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

        $courseCategory = $request->user()->courseCategories()->create($data);

        return ['status' => 200, 'message' => 'Course Category created successfully', 'data' => $courseCategory];
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $courseCategory = CourseCategory::findOrFail($id);
        return ['status' => 200, 'data' => $courseCategory];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
        ]);

        $courseCategory = CourseCategory::findOrFail($id);
        $courseCategory->update($data);

        return ['status' => 200, 'message' => 'Course Category Updated successfully', 'data' => $courseCategory];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        CourseCategory::findOrFail($id)->delete();

        return ['status' => 200, 'message' => 'Course Category deleted successfully'];
    }
}
