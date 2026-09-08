<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->text('medical_report')->nullable()->after('notes');
            $table->string('instagram_id')->nullable()->after('medical_report');
            $table->foreignId('locker_id')->nullable()->after('instagram_id')->constrained()->nullOnDelete();
            $table->unique('locker_id');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropUnique(['locker_id']);
            $table->dropConstrainedForeignId('locker_id');
            $table->dropColumn(['medical_report', 'instagram_id']);
        });
    }
};
