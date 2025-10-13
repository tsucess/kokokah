<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get lesson quizzes
     */
    public function index($lessonId)
    {
        try {
            $lesson = Lesson::findOrFail($lessonId);
            $user = Auth::user();

            // Check if user has access to this lesson
            $isEnrolled = $lesson->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $lesson->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin && !$lesson->is_free) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to view quizzes'
                ], 403);
            }

            $quizzes = $lesson->quizzes()
                            ->with(['questions' => function($query) use ($user, $isInstructor, $isAdmin) {
                                if (!$isInstructor && !$isAdmin) {
                                    $query->select('id', 'quiz_id', 'question_text', 'type', 'points');
                                }
                            }])
                            ->get()
                            ->map(function ($quiz) use ($user) {
                                $quizData = $quiz->toArray();
                                
                                // Add user's quiz attempts
                                $attempts = Answer::where('student_id', $user->id)
                                                ->whereIn('question_id', $quiz->questions->pluck('id'))
                                                ->get()
                                                ->groupBy('attempt_number');

                                $quizData['attempts'] = $attempts->count();
                                $quizData['max_attempts'] = $quiz->max_attempts;
                                $quizData['can_attempt'] = $quiz->max_attempts === null || $attempts->count() < $quiz->max_attempts;
                                
                                // Add best score
                                if ($attempts->count() > 0) {
                                    $scores = $attempts->map(function ($attemptAnswers) {
                                        return $attemptAnswers->sum('points_earned');
                                    });
                                    $quizData['best_score'] = $scores->max();
                                    $quizData['latest_score'] = $scores->last();
                                }

                                return $quizData;
                            });

            return response()->json([
                'success' => true,
                'data' => $quizzes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quizzes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new quiz
     */
    public function store(Request $request, $lessonId)
    {
        try {
            $lesson = Lesson::findOrFail($lessonId);

            // Check if user is instructor or admin
            if ($lesson->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to create quizzes for this lesson'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'type' => 'required|in:practice,graded,final',
                'time_limit_minutes' => 'nullable|integer|min:1',
                'max_attempts' => 'nullable|integer|min:1',
                'passing_score' => 'nullable|integer|min:0|max:100',
                'shuffle_questions' => 'boolean',
                'questions' => 'required|array|min:1',
                'questions.*.question_text' => 'required|string',
                'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer,essay',
                'questions.*.points' => 'required|integer|min:1',
                'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
                'questions.*.correct_answer' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Create quiz
                $quizData = $request->except(['questions']);
                $quizData['lesson_id'] = $lesson->id;
                $quizData['shuffle_questions'] = $request->boolean('shuffle_questions', false);

                $quiz = Quiz::create($quizData);

                // Create questions
                foreach ($request->questions as $questionData) {
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $questionData['question_text'],
                        'type' => $questionData['type'],
                        'points' => $questionData['points'],
                        'options' => $questionData['options'] ?? null,
                        'correct_answer' => $questionData['correct_answer'],
                        'explanation' => $questionData['explanation'] ?? null
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Quiz created successfully',
                    'data' => $quiz->load('questions')
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quiz details
     */
    public function show($id)
    {
        try {
            $quiz = Quiz::with(['lesson.course', 'questions'])->findOrFail($id);
            $user = Auth::user();

            // Check access
            $isEnrolled = $quiz->lesson->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $quiz->lesson->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin && !$quiz->lesson->is_free) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to view this quiz'
                ], 403);
            }

            $quizData = $quiz->toArray();

            // For students, hide correct answers and explanations
            if (!$isInstructor && !$isAdmin) {
                $quizData['questions'] = collect($quiz->questions)->map(function ($question) {
                    $questionData = $question->toArray();
                    unset($questionData['correct_answer']);
                    unset($questionData['explanation']);
                    return $questionData;
                });
            }

            // Add user's attempt history
            $attempts = Answer::where('student_id', $user->id)
                            ->whereIn('question_id', $quiz->questions->pluck('id'))
                            ->get()
                            ->groupBy('attempt_number');

            $quizData['user_attempts'] = $attempts->map(function ($attemptAnswers, $attemptNumber) {
                $totalPoints = $attemptAnswers->sum('points_earned');
                $maxPoints = $attemptAnswers->sum('points_possible');
                
                return [
                    'attempt_number' => $attemptNumber,
                    'score' => $totalPoints,
                    'max_score' => $maxPoints,
                    'percentage' => $maxPoints > 0 ? round(($totalPoints / $maxPoints) * 100, 2) : 0,
                    'submitted_at' => $attemptAnswers->first()->created_at,
                    'passed' => $quiz->passing_score ? ($totalPoints / $maxPoints * 100) >= $quiz->passing_score : true
                ];
            })->values();

            $quizData['can_attempt'] = $quiz->max_attempts === null || $attempts->count() < $quiz->max_attempts;

            return response()->json([
                'success' => true,
                'data' => $quizData
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz not found'
            ], 404);
        }
    }

    /**
     * Update quiz
     */
    public function update(Request $request, $id)
    {
        try {
            $quiz = Quiz::findOrFail($id);

            // Check if user is instructor or admin
            if ($quiz->lesson->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this quiz'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'type' => 'sometimes|in:practice,graded,final',
                'time_limit_minutes' => 'nullable|integer|min:1',
                'max_attempts' => 'nullable|integer|min:1',
                'passing_score' => 'nullable|integer|min:0|max:100',
                'shuffle_questions' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $quiz->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Quiz updated successfully',
                'data' => $quiz
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete quiz
     */
    public function destroy($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);

            // Check if user is instructor or admin
            if ($quiz->lesson->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this quiz'
                ], 403);
            }

            // Check if quiz has been attempted
            $hasAttempts = Answer::whereIn('question_id', $quiz->questions->pluck('id'))->exists();
            if ($hasAttempts) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete quiz that has been attempted by students'
                ], 400);
            }

            $quiz->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quiz deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a quiz attempt
     */
    public function startAttempt($id)
    {
        try {
            $quiz = Quiz::with(['questions', 'lesson.course'])->findOrFail($id);
            $user = Auth::user();

            // Check access
            $isEnrolled = $quiz->lesson->course->enrollments()->where('user_id', $user->id)->exists();
            if (!$isEnrolled && !$quiz->lesson->is_free) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to take this quiz'
                ], 403);
            }

            // Check attempt limits
            $attempts = Answer::where('student_id', $user->id)
                            ->whereIn('question_id', $quiz->questions->pluck('id'))
                            ->get()
                            ->groupBy('attempt_number');

            if ($quiz->max_attempts && $attempts->count() >= $quiz->max_attempts) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum attempts reached for this quiz'
                ], 400);
            }

            $nextAttemptNumber = $attempts->count() + 1;

            // Prepare questions for attempt
            $questions = $quiz->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'type' => $question->type,
                    'points' => $question->points,
                    'options' => $question->options
                ];
            });

            // Shuffle questions if enabled
            if ($quiz->shuffle_questions) {
                $questions = $questions->shuffle();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'quiz' => [
                        'id' => $quiz->id,
                        'title' => $quiz->title,
                        'type' => $quiz->type,
                        'time_limit_minutes' => $quiz->time_limit_minutes,
                        'passing_score' => $quiz->passing_score
                    ],
                    'attempt_number' => $nextAttemptNumber,
                    'questions' => $questions,
                    'started_at' => now(),
                    'expires_at' => $quiz->time_limit_minutes ? now()->addMinutes($quiz->time_limit_minutes) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit quiz answers
     */
    public function submitQuiz(Request $request, $id)
    {
        try {
            $quiz = Quiz::with(['questions', 'lesson.course'])->findOrFail($id);
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'attempt_number' => 'required|integer|min:1',
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|exists:questions,id',
                'answers.*.answer' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $totalScore = 0;
                $maxScore = 0;
                $results = [];

                foreach ($request->answers as $answerData) {
                    $question = Question::findOrFail($answerData['question_id']);
                    $maxScore += $question->points;

                    // Grade the answer
                    $pointsEarned = $this->gradeAnswer($question, $answerData['answer']);
                    $totalScore += $pointsEarned;

                    // Save answer
                    Answer::create([
                        'student_id' => $user->id,
                        'question_id' => $question->id,
                        'answer_text' => $answerData['answer'],
                        'points_earned' => $pointsEarned,
                        'points_possible' => $question->points,
                        'attempt_number' => $request->attempt_number,
                        'is_correct' => $pointsEarned === $question->points
                    ]);

                    $results[] = [
                        'question_id' => $question->id,
                        'points_earned' => $pointsEarned,
                        'points_possible' => $question->points,
                        'is_correct' => $pointsEarned === $question->points,
                        'correct_answer' => $question->correct_answer,
                        'explanation' => $question->explanation
                    ];
                }

                $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
                $passed = $quiz->passing_score ? $percentage >= $quiz->passing_score : true;

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Quiz submitted successfully',
                    'data' => [
                        'score' => $totalScore,
                        'max_score' => $maxScore,
                        'percentage' => $percentage,
                        'passed' => $passed,
                        'attempt_number' => $request->attempt_number,
                        'results' => $results
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quiz results
     */
    public function results($id)
    {
        try {
            $quiz = Quiz::with(['questions', 'lesson.course'])->findOrFail($id);
            $user = Auth::user();

            // Check access
            $isEnrolled = $quiz->lesson->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $quiz->lesson->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin && !$quiz->lesson->is_free) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to view quiz results'
                ], 403);
            }

            $answers = Answer::with('question')
                           ->where('student_id', $user->id)
                           ->whereIn('question_id', $quiz->questions->pluck('id'))
                           ->get()
                           ->groupBy('attempt_number');

            $results = $answers->map(function ($attemptAnswers, $attemptNumber) {
                $totalScore = $attemptAnswers->sum('points_earned');
                $maxScore = $attemptAnswers->sum('points_possible');

                return [
                    'attempt_number' => $attemptNumber,
                    'score' => $totalScore,
                    'max_score' => $maxScore,
                    'percentage' => $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0,
                    'submitted_at' => $attemptAnswers->first()->created_at,
                    'answers' => $attemptAnswers->map(function ($answer) {
                        return [
                            'question_id' => $answer->question_id,
                            'question_text' => $answer->question->question_text,
                            'user_answer' => $answer->answer_text,
                            'correct_answer' => $answer->question->correct_answer,
                            'points_earned' => $answer->points_earned,
                            'points_possible' => $answer->points_possible,
                            'is_correct' => $answer->is_correct,
                            'explanation' => $answer->question->explanation
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'quiz' => $quiz,
                    'results' => $results->values()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch results: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quiz analytics (for instructors)
     */
    public function analytics($id)
    {
        try {
            $quiz = Quiz::with(['questions', 'lesson.course'])->findOrFail($id);

            // Check if user is instructor or admin
            if ($quiz->lesson->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view quiz analytics'
                ], 403);
            }

            $answers = Answer::whereIn('question_id', $quiz->questions->pluck('id'))->get();
            $uniqueStudents = $answers->pluck('student_id')->unique();

            $analytics = [
                'overview' => [
                    'total_attempts' => $answers->groupBy(['student_id', 'attempt_number'])->count(),
                    'unique_students' => $uniqueStudents->count(),
                    'average_score' => $answers->groupBy(['student_id', 'attempt_number'])
                                           ->map(function ($attemptAnswers) {
                                               $totalScore = $attemptAnswers->sum('points_earned');
                                               $maxScore = $attemptAnswers->sum('points_possible');
                                               return $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
                                           })
                                           ->avg(),
                    'pass_rate' => $quiz->passing_score ?
                        $answers->groupBy(['student_id', 'attempt_number'])
                               ->filter(function ($attemptAnswers) use ($quiz) {
                                   $totalScore = $attemptAnswers->sum('points_earned');
                                   $maxScore = $attemptAnswers->sum('points_possible');
                                   $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
                                   return $percentage >= $quiz->passing_score;
                               })
                               ->count() / max($answers->groupBy(['student_id', 'attempt_number'])->count(), 1) * 100
                        : 100
                ],
                'question_analytics' => $quiz->questions->map(function ($question) use ($answers) {
                    $questionAnswers = $answers->where('question_id', $question->id);
                    $correctAnswers = $questionAnswers->where('is_correct', true);

                    return [
                        'question_id' => $question->id,
                        'question_text' => $question->question_text,
                        'total_attempts' => $questionAnswers->count(),
                        'correct_attempts' => $correctAnswers->count(),
                        'accuracy_rate' => $questionAnswers->count() > 0 ?
                            round(($correctAnswers->count() / $questionAnswers->count()) * 100, 2) : 0,
                        'average_points' => $questionAnswers->avg('points_earned')
                    ];
                }),
                'score_distribution' => $this->getScoreDistribution($answers, $quiz)
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grade an individual answer
     */
    private function gradeAnswer($question, $userAnswer)
    {
        switch ($question->type) {
            case 'multiple_choice':
            case 'true_false':
                return strtolower(trim($userAnswer)) === strtolower(trim($question->correct_answer))
                    ? $question->points : 0;

            case 'short_answer':
                // Simple string comparison (could be enhanced with fuzzy matching)
                $correctAnswer = strtolower(trim($question->correct_answer));
                $userAnswerLower = strtolower(trim($userAnswer));

                if ($correctAnswer === $userAnswerLower) {
                    return $question->points;
                }

                // Partial credit for similar answers
                similar_text($correctAnswer, $userAnswerLower, $percent);
                return $percent >= 80 ? round($question->points * 0.8) : 0;

            case 'essay':
                // Essays require manual grading, return 0 for now
                return 0;

            default:
                return 0;
        }
    }

    /**
     * Get score distribution for analytics
     */
    private function getScoreDistribution($answers, $quiz)
    {
        $attempts = $answers->groupBy(['student_id', 'attempt_number']);
        $scores = $attempts->map(function ($attemptAnswers) {
            $totalScore = $attemptAnswers->sum('points_earned');
            $maxScore = $attemptAnswers->sum('points_possible');
            return $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
        });

        $distribution = [
            '0-20' => 0,
            '21-40' => 0,
            '41-60' => 0,
            '61-80' => 0,
            '81-100' => 0
        ];

        foreach ($scores as $score) {
            if ($score <= 20) $distribution['0-20']++;
            elseif ($score <= 40) $distribution['21-40']++;
            elseif ($score <= 60) $distribution['41-60']++;
            elseif ($score <= 80) $distribution['61-80']++;
            else $distribution['81-100']++;
        }

        return $distribution;
    }
}
