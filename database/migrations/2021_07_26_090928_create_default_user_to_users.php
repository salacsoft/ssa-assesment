<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultUserToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::create([
            "prefixname" => "Mr",
            "firstname" => "Joseph",
            "middlename" => "Evangelio",
            "lastname" => "Salac",
            "username" => "joseph123",
            "email" => "salacsoft22@gmail.com",
            "type" => "admin",
            "photo" => "joseph.jpg",
            "password" => "secretkey"
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
