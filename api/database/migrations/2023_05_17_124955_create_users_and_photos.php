<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 10)->unique();
            });

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
                $table->integer('points');
                $table->foreignId('role_id')->constrained('roles');
                $table->timestamps();
            });

            Schema::create('photos', function (Blueprint $table) {
                $table->id();
                $table->string('url');
                $table->boolean('is_main');
                $table->foreignId('user_id')->constrained('users');
                $table->timestamps();
            });

            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('source_user_id')->constrained('users');
                $table->foreignId('target_user_id')->constrained('users');
                $table->boolean('is_mutual');
                $table->timestamps();
            });
            Schema::create('dislikes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('source_user_id')->constrained('users');
                $table->foreignId('target_user_id')->constrained('users');
                $table->timestamps();
            });


            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sender_id');
                $table->string('sender_username');
                $table->unsignedBigInteger('recipient_id');
                $table->string('recipient_username');
                $table->text('content');
                $table->timestamp('date_read')->nullable();
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
    
                $table->timestamps();
            });
            Schema::create('reports', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('reporting_id');
                $table->unsignedBigInteger('reported_id');
                $table->string('reason', 300);
                $table->timestamps(); 
                $table->foreign('reported_id')->references('id')->on('users');
                $table->foreign('reporting_id')->references('id')->on('users');
            });
            Schema::create('blockages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('admin_id');
                $table->string('reason', 300);
                $table->timestamp('until')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            });
            Schema::create('default_points', function (Blueprint $table) {
                $table->increments('id');
                $table->string('what_for');
                $table->string('description');
                $table->integer('points');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('photos');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('users');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('blockages');
    }
};
