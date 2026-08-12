<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('membership_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->date('joining_date');
            $table->date('membership_start_date')->nullable();
            $table->date('membership_expiry_date')->nullable();
            $table->enum('payment_status', ['paid', 'pending', 'overdue'])->default('pending');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'deleted_at']);
            $table->index('membership_expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
