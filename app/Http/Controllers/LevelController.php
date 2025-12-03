<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LevelController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        return Level::with(['courses' => function($q) {
            $q->with('level');
        }])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required'
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
            'title' => 'required|max:255',
            'description' => 'required'
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
