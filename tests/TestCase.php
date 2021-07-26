<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, RefreshDatabase;
    
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $credential = ["email" => "salacsoft22@gmail.com", "password" => "secretkey"];
        $this->post("login", $credential);
    }
    
}
