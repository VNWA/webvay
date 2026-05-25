<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->default('customer')->after('email');
            $table->string('last_login_ip', 45)->nullable()->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('last_login_ip');
        });

        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('purpose', 32)->default('login');
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference', 32)->unique();
            $table->unsignedBigInteger('desired_amount')->default(10_000_000);
            $table->string('status', 32)->default('in_progress')->index();
            $table->string('wizard_step', 48)->default('personal');
            $table->unsignedTinyInteger('score')->nullable();
            $table->unsignedBigInteger('approved_amount')->nullable();
            $table->string('risk_level', 24)->nullable();
            $table->unsignedSmallInteger('tenure_months')->default(12);
            $table->unsignedBigInteger('monthly_payment')->nullable();
            $table->json('ai_progress')->nullable();
            $table->string('admin_decision', 24)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
            $table->string('full_name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 16)->nullable();
            $table->string('cccd_number', 32)->nullable();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->unsignedBigInteger('monthly_income')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->timestamps();
        });

        Schema::create('ekyc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
            $table->string('front_path')->nullable();
            $table->string('back_path')->nullable();
            $table->string('selfie_path')->nullable();
            $table->json('ocr_json')->nullable();
            $table->timestamps();
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
            $table->string('code', 48)->unique();
            $table->string('pdf_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signing_ip', 45)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 128)->index();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('ekyc_documents');
        Schema::dropIfExists('customer_profiles');
        Schema::dropIfExists('loan_applications');
        Schema::dropIfExists('otp_codes');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'last_login_ip', 'last_login_at']);
        });
    }
};
