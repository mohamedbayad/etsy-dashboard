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
        Schema::table('niches', function (Blueprint $table) {
            $table->string('sheet_status')->default('unchecked')->index()->after('sheet_id');
            $table->timestamp('sheet_last_checked_at')->nullable()->after('sheet_status');
            $table->text('sheet_error_message')->nullable()->after('sheet_last_checked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('niches', function (Blueprint $table) {
            $table->dropColumn([
                'sheet_status',
                'sheet_last_checked_at',
                'sheet_error_message',
            ]);
        });
    }
};
