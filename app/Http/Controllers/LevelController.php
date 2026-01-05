<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
    }


    public function index()
    {
        return Level::with(['courses' => function($q) {
            $q->with('level');
        }, 'curriculumCategory'])
        ->orderBy('curriculum_category_id', 'asc') // order by category ID
        ->get();
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'curriculum_category_id' => 'required|integer|exists:curriculum_categories,id',
            'description' => 'nullable|string'
        ]);

        $level = Level::create($data);

        return [
            'status' => 200,
            'message' => 'Level created successfully',
            'response' => $level
        ];
    }

    public function show($id)
    {
        $data = Level::findOrFail($id);
        return [
            'status' => 200,
            'response' => $data
        ];
    }

    public function update(Request $request,  $id)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'curriculum_category_id' => 'required|integer|exists:curriculum_categories,id',
            'description' => 'nullable'
        ]);

        $level = Level::findOrFail($id);
        $level->update($data);

        return [
            'status' => 200,
            'message' => 'Level Updated successfully',
            'response' => $level
        ];
    }

    public function destroy($id)
    {
        Level::findOrFail($id)->delete();

        return [
            'status' => 200,
            'message' => 'Level deleted successfully'
        ];
    }
}
