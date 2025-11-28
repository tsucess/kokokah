<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    // GET all topics
    public function index()
    {
        return response()->json(Topic::orderBy('order')->get());
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
