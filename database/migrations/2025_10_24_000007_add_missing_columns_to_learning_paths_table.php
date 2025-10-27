<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('learning_paths')) {
            $columns = Schema::getColumnListing('learning_paths');
            
            Schema::table('learning_paths', function (Blueprint $table) use ($columns) {
                // Add category if it doesn't exist
                if (!in_array('category', $columns)) {
                    $table->string('category')->nullable()->after('difficulty');
                }
                
                // Add difficulty_level if it doesn't exist (keep difficulty for backward compatibility)
                if (!in_array('difficulty_level', $columns)) {
                    $table->string('difficulty_level')->nullable()->after('category');
                }
                
                // Add estimated_duration if it doesn't exist (keep estimated_hours for backward compatibility)
                if (!in_array('estimated_duration', $columns)) {
                    $table->integer('estimated_duration')->nullable()->after('difficulty_level');
                }
                
                // Add prerequisites if it doesn't exist
                if (!in_array('prerequisites', $columns)) {
                    $table->text('prerequisites')->nullable()->after('estimated_duration');
                }
                
                // Add learning_objectives if it doesn't exist
                if (!in_array('learning_objectives', $columns)) {
                    $table->json('learning_objectives')->nullable()->after('prerequisites');
                }
                
                // Add image_path if it doesn't exist
                if (!in_array('image_path', $columns)) {
                    $table->string('image_path')->nullable()->after('learning_objectives');
                }
                
                // Add creator_id if it doesn't exist (keep created_by for backward compatibility)
                if (!in_array('creator_id', $columns)) {
                    $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null')->after('image_path');
                }
                
                // Add status if it doesn't exist
                if (!in_array('status', $columns)) {
                    $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('creator_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('learning_paths')) {
            Schema::table('learning_paths', function (Blueprint $table) {
                $columnsToCheck = ['category', 'difficulty_level', 'estimated_duration', 'prerequisites', 'learning_objectives', 'image_path', 'creator_id', 'status'];
                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('learning_paths', $column)) {
                        if ($column === 'creator_id') {
                            $table->dropForeign(['creator_id']);
                        }
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};

