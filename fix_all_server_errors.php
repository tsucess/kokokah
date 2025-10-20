<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\LearningPath;
use App\Models\File;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

echo "🔧 FIXING ALL SERVER ERRORS\n";
echo "============================\n\n";

// 1. Fix Settings table schema
echo "1. Checking Settings table schema...\n";
if (!Schema::hasColumn('settings', 'category')) {
    echo "   Adding missing 'category' column to settings table...\n";
    Schema::table('settings', function ($table) {
        $table->string('category')->default('general')->after('id');
        $table->integer('order')->default(0)->after('category');
    });
    echo "   ✅ Settings table schema fixed\n";
} else {
    echo "   ✅ Settings table schema is correct\n";
}

// 2. Create test data with ID=1 for all models
echo "\n2. Creating test data with ID=1...\n";

// Create admin user with ID=1
$admin = User::find(1);
if (!$admin) {
    echo "   Creating admin user with ID=1...\n";
    $admin = new User();
    $admin->id = 1;
    $admin->identifier = 'KOKOKAH-0001';
    $admin->first_name = 'Test';
    $admin->last_name = 'Admin';
    $admin->email = 'admin1@kokokah.com';
    $admin->password = Hash::make('password');
    $admin->role = 'admin';
    $admin->is_active = true;
    $admin->email_verified_at = now();
    $admin->save();
    echo "   ✅ Admin user created with ID=1\n";
} else {
    echo "   ✅ Admin user with ID=1 already exists\n";
}

// Create student user with ID=2 if needed
$student = User::find(2);
if (!$student) {
    echo "   Creating student user with ID=2...\n";
    $student = new User();
    $student->id = 2;
    $student->identifier = 'KOKOKAH-0002';
    $student->first_name = 'Test';
    $student->last_name = 'Student';
    $student->email = 'student1@kokokah.com';
    $student->password = Hash::make('password');
    $student->role = 'student';
    $student->is_active = true;
    $student->email_verified_at = now();
    $student->save();
    echo "   ✅ Student user created with ID=2\n";
} else {
    echo "   ✅ Student user with ID=2 already exists\n";
}

// Create category with ID=1
$category = Category::find(1);
if (!$category) {
    echo "   Creating category with ID=1...\n";
    $category = new Category();
    $category->id = 1;
    $category->user_id = $admin->id;
    $category->title = 'Test Category 1';
    $category->description = 'Test category for endpoint testing';
    $category->save();
    echo "   ✅ Category created with ID=1\n";
} else {
    echo "   ✅ Category with ID=1 already exists\n";
}

// Create course with ID=1
$course = Course::find(1);
if (!$course) {
    echo "   Creating course with ID=1...\n";
    $course = new Course();
    $course->id = 1;
    $course->title = 'Test Course 1';
    $course->description = 'Test course for endpoint testing';
    $course->category_id = $category->id;
    $course->instructor_id = $admin->id;
    $course->price = 99.99;
    $course->difficulty_level = 'beginner';
    $course->status = 'published';
    $course->is_featured = true;
    $course->published_at = now();
    $course->save();
    echo "   ✅ Course created with ID=1\n";
} else {
    echo "   ✅ Course with ID=1 already exists\n";
}

// Create enrollment with ID=1
$enrollment = Enrollment::find(1);
if (!$enrollment) {
    echo "   Creating enrollment with ID=1...\n";
    $enrollment = new Enrollment();
    $enrollment->id = 1;
    $enrollment->user_id = $student->id;
    $enrollment->course_id = $course->id;
    $enrollment->status = 'active';
    $enrollment->enrolled_at = now();
    $enrollment->save();
    echo "   ✅ Enrollment created with ID=1\n";
} else {
    echo "   ✅ Enrollment with ID=1 already exists\n";
}

// Create learning path with ID=1
$learningPath = LearningPath::find(1);
if (!$learningPath) {
    echo "   Creating learning path with ID=1...\n";
    $learningPath = new LearningPath();
    $learningPath->id = 1;
    $learningPath->title = 'Test Learning Path 1';
    $learningPath->description = 'Test learning path for endpoint testing';
    $learningPath->created_by = $admin->id;
    $learningPath->is_published = true;
    $learningPath->difficulty = 'beginner';
    $learningPath->estimated_hours = 10;
    $learningPath->save();
    echo "   ✅ Learning path created with ID=1\n";
} else {
    echo "   ✅ Learning path with ID=1 already exists\n";
}

// Create file record with ID=1
$file = File::find(1);
if (!$file) {
    echo "   Creating file record with ID=1...\n";
    $file = new File();
    $file->id = 1;
    $file->user_id = $admin->id;
    $file->file_name = 'test-file.pdf';
    $file->original_name = 'Test File.pdf';
    $file->mime_type = 'application/pdf';
    $file->file_size = 1024;
    $file->file_path = 'uploads/test-file.pdf';
    $file->extension = 'pdf';
    $file->folder = 'uploads';
    $file->is_public = true;
    $file->download_count = 0;
    $file->save();
    echo "   ✅ File record created with ID=1\n";
} else {
    echo "   ✅ File record with ID=1 already exists\n";
}

// Create basic settings
echo "\n3. Creating basic settings...\n";
$settings = [
    ['key' => 'site_name', 'value' => 'Kokokah LMS', 'category' => 'general', 'order' => 1],
    ['key' => 'site_description', 'value' => 'Nigerian Learning Management System', 'category' => 'general', 'order' => 2],
    ['key' => 'smtp_host', 'value' => 'smtp.gmail.com', 'category' => 'email', 'order' => 1],
    ['key' => 'smtp_port', 'value' => '587', 'category' => 'email', 'order' => 2],
    ['key' => 'paystack_public_key', 'value' => 'pk_test_xxxxx', 'category' => 'payment', 'order' => 1],
    ['key' => 'paystack_secret_key', 'value' => 'sk_test_xxxxx', 'category' => 'payment', 'order' => 2],
    ['key' => 'enable_forums', 'value' => 'true', 'category' => 'features', 'order' => 1],
    ['key' => 'enable_chat', 'value' => 'true', 'category' => 'features', 'order' => 2],
];

foreach ($settings as $settingData) {
    $setting = Setting::where('key', $settingData['key'])->first();
    if (!$setting) {
        Setting::create($settingData);
        echo "   ✅ Created setting: {$settingData['key']}\n";
    } else {
        echo "   ✅ Setting exists: {$settingData['key']}\n";
    }
}

echo "\n============================\n";
echo "✅ ALL SERVER ERROR FIXES APPLIED\n";
echo "============================\n";
echo "Created test data:\n";
echo "• Admin User ID: 1\n";
echo "• Student User ID: 2\n";
echo "• Category ID: 1\n";
echo "• Course ID: 1\n";
echo "• Enrollment ID: 1\n";
echo "• Learning Path ID: 1\n";
echo "• File ID: 1\n";
echo "• Settings: 8 records\n";
echo "\nNow testing endpoints should work with proper IDs.\n";
