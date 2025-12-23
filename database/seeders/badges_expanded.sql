-- Enhanced Badge System (30 Badges)
-- Covers: Quizzes, Lessons, Topics, Courses, Chatroom, Enrollments, Points

INSERT INTO `badges` (`id`, `name`, `description`, `points`, `icon`, `criteria`, `category`, `type`, `created_at`, `updated_at`) VALUES

-- LESSON BADGES (1-3)
(1, 'First Lesson', 'Complete your first lesson', 10, '📖', 'lesson_completion:1', 'learning', 'lesson_completion', NOW(), NOW()),
(2, 'Lesson Enthusiast', 'Complete 10 lessons', 25, '📚', 'lesson_completion:10', 'learning', 'lesson_completion', NOW(), NOW()),
(3, 'Lesson Master', 'Complete 50 lessons', 50, '📕', 'lesson_completion:50', 'learning', 'lesson_completion', NOW(), NOW()),

-- TOPIC BADGES (4-6)
(4, 'Topic Starter', 'Complete your first topic', 15, '🎯', 'topic_completion:1', 'learning', 'topic_completion', NOW(), NOW()),
(5, 'Topic Explorer', 'Complete 5 topics', 30, '🗺️', 'topic_completion:5', 'learning', 'topic_completion', NOW(), NOW()),
(6, 'Topic Conqueror', 'Complete 20 topics', 60, '⛰️', 'topic_completion:20', 'learning', 'topic_completion', NOW(), NOW()),

-- COURSE BADGES (7-10)
(7, 'Course Starter', 'Enroll in your first course', 5, '🚀', 'enrollment:1', 'learning', 'course_enrollment', NOW(), NOW()),
(8, 'Course Completer', 'Complete your first course', 50, '🎓', 'course_completion:1', 'learning', 'course_completion', NOW(), NOW()),
(9, 'Scholar', 'Complete 10 courses', 100, '🏆', 'course_completion:10', 'learning', 'course_completion', NOW(), NOW()),
(10, 'Master Student', 'Complete 25 courses', 150, '👑', 'course_completion:25', 'learning', 'course_completion', NOW(), NOW()),

-- QUIZ BADGES (11-14)
(11, 'Quiz Taker', 'Complete your first quiz', 10, '❓', 'quiz_pass:1', 'achievement', 'quiz_mastery', NOW(), NOW()),
(12, 'Perfect Score', 'Get 100% on a quiz', 40, '💯', 'quiz_perfect:1', 'achievement', 'quiz_mastery', NOW(), NOW()),
(13, 'Quiz Master', 'Pass 25 quizzes', 75, '🧠', 'quiz_pass:25', 'achievement', 'quiz_mastery', NOW(), NOW()),
(14, 'Quiz Legend', 'Pass 50 quizzes', 120, '⚡', 'quiz_pass:50', 'achievement', 'quiz_mastery', NOW(), NOW()),

-- POINTS BADGES (15-17)
(15, 'Point Collector', 'Earn 100 points', 20, '💰', 'points:100', 'achievement', 'points', NOW(), NOW()),
(16, 'Point Hoarder', 'Earn 500 points', 50, '💎', 'points:500', 'achievement', 'points', NOW(), NOW()),
(17, 'Point Master', 'Earn 1000 points', 100, '👑', 'points:1000', 'achievement', 'points', NOW(), NOW()),

-- SPEED & TIME BADGES (18-20)
(18, 'Quick Learner', 'Complete a course in less than 7 days', 35, '⚡', 'course_speed:7', 'achievement', 'speed', NOW(), NOW()),
(19, 'Early Bird', 'Complete 5 lessons before 8 AM', 25, '🌅', 'early_bird:5', 'achievement', 'time', NOW(), NOW()),
(20, 'Night Owl', 'Complete 5 lessons after 10 PM', 25, '🦉', 'night_owl:5', 'achievement', 'time', NOW(), NOW()),

-- CONSISTENCY BADGES (21-23)
(21, 'Consistent Learner', 'Study for 7 consecutive days', 40, '📅', 'consecutive_days:7', 'achievement', 'streak', NOW(), NOW()),
(22, 'Dedicated Learner', 'Study for 30 consecutive days', 80, '🔥', 'consecutive_days:30', 'achievement', 'streak', NOW(), NOW()),
(23, 'Unstoppable', 'Study for 100 consecutive days', 150, '💪', 'consecutive_days:100', 'achievement', 'streak', NOW(), NOW()),

-- CHATROOM & SOCIAL BADGES (24-26)
(24, 'Social Butterfly', 'Participate in 10 chatroom discussions', 20, '💬', 'chatroom_posts:10', 'social', 'participation', NOW(), NOW()),
(25, 'Community Helper', 'Help 5 students in chatroom', 35, '🤝', 'helpful_posts:5', 'social', 'participation', NOW(), NOW()),
(26, 'Community Leader', 'Help 20 students in chatroom', 70, '👥', 'helpful_posts:20', 'social', 'participation', NOW(), NOW()),

-- ENROLLMENT BADGES (27-28)
(27, 'Multi-Learner', 'Enroll in 5 courses simultaneously', 30, '🎪', 'active_enrollments:5', 'learning', 'course_enrollment', NOW(), NOW()),
(28, 'Enrollment Master', 'Enroll in 20 courses total', 60, '📋', 'total_enrollments:20', 'learning', 'course_enrollment', NOW(), NOW()),

-- SPECIAL BADGES (29-30)
(29, 'Instructor', 'Create your first course', 50, '🎬', 'instructor:1', 'special', 'instructor', NOW(), NOW()),
(30, 'Legendary Learner', 'Achieve Expert level (1000+ points)', 200, '⭐', 'level:expert', 'special', 'milestone', NOW(), NOW());

