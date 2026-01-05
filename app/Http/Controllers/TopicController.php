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

    // GET lessons for a specific topic
    public function getLessons($topicId)
    {
        try {
            $topic = Topic::findOrFail($topicId);
            $user = \Illuminate\Support\Facades\Auth::user();

            // Check if user has access to this topic's course
            $isEnrolled = $topic->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $topic->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to view lessons'
                ], 403);
            }

            $lessons = $topic->lessons()
                            ->with(['topic', 'course'])
                            ->orderBy('order')
                            ->get()
                            ->map(function ($lesson) use ($user) {
                                $lessonData = $lesson->toArray();

                                // Add completion status for the user
                                $completion = \App\Models\LessonCompletion::where('user_id', $user->id)
                                                                    ->where('lesson_id', $lesson->id)
                                                                    ->first();

                                $lessonData['is_completed'] = $completion !== null;
                                $lessonData['completed_at'] = $completion?->completed_at;
                                $lessonData['time_spent'] = $completion?->time_spent ?? 0;

                                return $lessonData;
                            });

            return response()->json([
                'success' => true,
                'data' => $lessons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lessons: ' . $e->getMessage()
            ], 500);
        }
    }

    // POST create topic
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'course_id' => 'nullable|integer',
            'term_id' => 'nullable|integer',
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
            'term_id' => 'nullable|integer',
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
