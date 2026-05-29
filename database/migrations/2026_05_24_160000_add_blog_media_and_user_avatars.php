<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('avatar_path')->nullable()->after('email');
        });

        Schema::table('blog_posts', function (Blueprint $table): void {
            $table->string('cover_image')->nullable()->after('excerpt');
            $table->string('author_avatar')->nullable()->after('cover_image');
            $table->string('author_display_name')->nullable()->after('author_id');
            $table->string('author_title', 120)->nullable()->after('author_display_name');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table): void {
            $table->dropColumn(['cover_image', 'author_avatar', 'author_display_name', 'author_title']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('avatar_path');
        });
    }
};
