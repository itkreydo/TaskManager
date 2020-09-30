<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->integer('user_id');
            $table->text('text');
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
        Schema::dropIfExists('task_messages');
    }
}
//$user = new App\User();
//$user->password = bcrypt('testtest');
//$user->password = Hash::make('testtest');
//$user->email = "test@test.test";
//$user->name = "Igor";
//$user->surname = "Kreydo";
//$user->patronymic = "Vitalevich";
//$user->date_of_birth = "1998-11-30";
//$user->group_id = 1;
//$user->avatar_id = 1;
//$user->save();
//
//$group = new App\Group();
//$group->name = 'Administrator';
//$group->unique_name = 'admin';
//$group->save();
