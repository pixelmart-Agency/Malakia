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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->foreignId('notice_type_id')->constrained('notice_types');
            $table->string('lat');
            $table->string('long');
            $table->string('notice_time');
            $table->string('notice_date');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['0', '1', '2'])->comment('0 = تحت المراجعه 1=مقبول 2= مرفوض');
            $table->string('refuse_reason')->nullable();
            $table->string('refuse_notice')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins');
            $table->foreignId('leader_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
