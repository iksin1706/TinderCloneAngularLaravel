<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username');
                $table->string('email')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('known_as');
                $table->timestamp('created')->useCurrent();
                $table->timestamp('last_active')->nullable();
                $table->string('gender');
                $table->text('introduction')->nullable();
                $table->text('looking_for')->nullable();
                $table->text('interests')->nullable();
                $table->string('city');
                $table->string('country');
                $table->string('password');
                $table->timestamps();
            });

            Schema::create('photos', function (Blueprint $table) {
                $table->id();
                $table->string('url');
                $table->boolean('is_main');
                $table->string('public_id');
                $table->foreignId('user_id')->constrained('users');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('photos');
    }
};
