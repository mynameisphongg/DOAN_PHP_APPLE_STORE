<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('remember_token')->nullable()->change(); // Chuyển thành TEXT để chứa token dài
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('remember_token', 100)->nullable()->change(); // Trả lại kích thước cũ nếu rollback
        });
    }
};

