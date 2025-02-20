<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('axis_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->foreignId('axis_id')->constrained('axes');
            $table->enum('answer_type', ['0', '1', '2'])->comment('essy multiple true or false');
            $table->enum('require_file', ['0', '1'])->default(0)->comment('0=> not require, 1=> require');
            $table->foreignId('true_parent_id')->nullable()->constrained('axis_questions');
            $table->foreignId('false_parent_id')->nullable()->constrained('axis_questions');
            $table->bigInteger('order_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('axis_questions');
    }
};
