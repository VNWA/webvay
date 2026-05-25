<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table): void {
            $table->string('phone', 20)->nullable()->after('ward');
        });

        Schema::table('ekyc_documents', function (Blueprint $table): void {
            $table->string('holding_front_path')->nullable()->after('back_path');
            $table->string('holding_back_path')->nullable()->after('holding_front_path');
        });
    }

    public function down(): void
    {
        Schema::table('ekyc_documents', function (Blueprint $table): void {
            $table->dropColumn(['holding_front_path', 'holding_back_path']);
        });

        Schema::table('customer_profiles', function (Blueprint $table): void {
            $table->dropColumn('phone');
        });
    }
};
