<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Inertia\Testing\Assert;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\App\Services\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;


class UserServiceTest extends TestCase
{

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupFaker();
    }

    /**
     * @test
     */
    public function test_it_can_visit_user_listing_page()
    {
        
        $response = $this->get("users")
                        ->assertInertia(fn (Assert $page) => $page
                        ->component('User/Index')
                        ->has('users'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function test_it_can_return_a_paginated_list_of_users()
    {
        $user = UserServiceInterface::list();
        $this->assertTrue(property_exists($user, "currentPage"));
        $this->assertTrue(property_exists($user, "total"));
        $this->assertTrue(property_exists($user, "lastPage"));
        $this->assertTrue(property_exists($user, "perPage"));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Arrangements
        $payload = [
            'prefixname' => "Mr",
            'firstname' => $this->faker->firstName(),
            'middlename' => "Evangelio",
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->safeEmail(),
            'password' => 'password', // password
            'confirm_password' => 'password',
            '_token' => csrf_token()
        ];

        // Actions
        $response = $this->post(route('users.create'), $payload);
        
        $user = UserServiceInterface::getBy("email", $payload["email"])->first();  
        // Assertions
        $this->assertEquals($user["email"], $payload["email"]);
        $response->assertRedirect('users');
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Arrangements
        $data = User::factory()->create();
 
        // Actions
        $response = $this->get(route("users.show", ["id" => $data->id]))
                ->assertInertia(fn (Assert $page) => $page
                ->component('User/Show')
                ->has('user'));

        $user = UserServiceInterface::getBy("email", $data->email)->first();

        // Assertions
        $this->assertEquals($data->email, $user->email);
        $response->assertOk();
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        $data = User::factory()->create();

        $payload = [
            'prefixname' => "Mr",
            'firstname' => $this->faker->firstName(),
            'middlename' => "Evangelio",
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->safeEmail(),
            'password' => 'password', // password
            'confirm_password' => 'password',
            '_token' => csrf_token()
        ];

        // Actions
        $response = $this->patch(route("users.update", ["id" => $data->id]), $payload);
        $user = UserServiceInterface::getBy("email", $payload["email"])->first();
        // Assertions
        $this->assertEquals($payload["firstname"], $user->firstname);
        $response->assertRedirect('users');
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
	    // Arrangements
        $data = User::factory()->create();
    	// Actions
        $response = $this->delete(route("users.delete", ["id" => $data->id]));
        $isNull = UserServiceInterface::getBy("id", $data->id)->first();  
        $notNull = UserServiceInterface::getBy("id", $data->id)->withTrashed()->first();  
	    // Assertions
        $this->assertNull($isNull);
        $this->assertNotNull($notNull);
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
	    // Arrangements
        $data = User::factory()->create();
        $this->json("delete", "users/". $data->id ."/destroy");
        
	    // Actions
        $response = $this->get(route("users.trashed"))
            ->assertInertia(fn (Assert $page) => $page
            ->component('User/InactiveUser')
            ->has('users'));

        $user = UserServiceInterface::listTrashed();

	    // Assertions
        $this->assertNotNull($user);
        $response->assertOk();
        $response->assertSessionHasNoErrors();

        $this->assertEquals(1, $user->currentPage());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrangements
        $data = User::factory()->create();
        $response = $this->delete(route("users.delete", ["id" => $data->id]));

        // Actions
        $response = $this->json("patch", "users/". $data->id ."/restore");
        // Assertions
        $response->assertRedirect('users/trashed');
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Arrangements
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->json("delete", "users/". $user1->id ."/destroy");

        // Actions
        $response = $this->delete(route("users.delete", ["id" => $user1->id, "force" => true]));
        $isNull = UserServiceInterface::getBy("id", $user1->id)->withTrashed()->first();  
        $notNull = UserServiceInterface::getBy("id", $user2->id)->withTrashed()->first();  
        
        // Assertions
        $this->assertNull($isNull);
        $this->assertNotNull($notNull);
        $response->assertRedirect('users/trashed');
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {;
        $payload = [
            'prefixname' => "Mr",
            'firstname' => $this->faker->firstName(),
            'middlename' => "Evangelio",
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->safeEmail(),
            'password' => 'password', // password
            'confirm_password' => 'password',
            'photo' => UploadedFile::fake()->image('avatar.jpg'),
            '_token' => csrf_token()
        ];

        // Actions
        $response = $this->post(route("users.create"), $payload);
        $user = UserServiceInterface::getBy("email", $payload["email"])->first();
        Storage::disk('public')->assertExists($payload["username"].'.jpg');
        Storage::disk('public')->assertMissing('Missing.jpg');
        $response->assertSessionHasNoErrors();
        $this->assertEquals($payload["email"], $user->email);
    }
}
