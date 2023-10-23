<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->uuid('user_uuid')->primary();
            $table->string('user_id', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('nick_name', 30)->nullable();
            $table->string('pr', 200)->nullable();
            $table->text('icon_path')->nullable();
            $table->string('reset_password_access_key', 64)->nullable()->unique();
            $table->timestamp('reset_password_expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user');
    }
};
