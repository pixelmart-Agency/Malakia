<?php

use App\Enum\ReportStatusEnum;
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
        Schema::table('general_reports', function (Blueprint $table) {
            $table->enum('status',ReportStatusEnum::values());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_reports', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
