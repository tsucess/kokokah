<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    // GET all topics
    public function index()
    {
        return response()->json(Topic::orderBy('order')->get());
    }

    // GET topics for a specific course
    public function getByCourse($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $topics = Topic::where('course_id', $courseId)
                          ->with('lessons')
                          ->orderBy('order')
                          ->get();

            return response()->json([
                'success' => true,
                'data' => $topics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
    }

    // GET single topic
    public function show($id)
    {
        return response()->json(Topic::findOrFail($id));
    }

    // POST create topic
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'course_id' => 'nullable|integer',
            'order' => 'integer'
        ]);

        $topic = Topic::create($data);

        return response()->json($topic, 201);
    }

    // PUT update topic
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'course_id' => 'nullable|integer',
            'order' => 'integer'
        ]);

        $topic = Topic::findOrFail($id);
        $topic->update($data);

        return response()->json($topic);
    }

    // DELETE topic
    public function destroy($id)
    {
        Topic::findOrFail($id)->delete();

        return response()->json(['message' => 'Topic deleted']);
    }
}
