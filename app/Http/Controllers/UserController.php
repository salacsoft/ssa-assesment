<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //
    protected $user;
    public function __construct(UserServiceInterface $user)
    {
        $this->middleware("auth")->except(["showRegisterForm","register"]);
        $this->user = $user;
    }


    public function register()
    {
        return Inertia::render('User/Register');
    }


    /**
     * @param App\Http\Requests\UserRequest;;
     * @return jsonResponse
     */
    public function create(UserRequest $request)
    {   
        Log::info($request->all());
        $payload = $request->only($this->user->getFillable());
        if ($request->photo){
            $payload["photo"] = $this->user->upload($request->file('photo'), $payload["username"]);
        }

        $this->user->store($payload);
        $request->session()->flash('success', 'User Registered Successfully!');
        return Redirect::route("users.index");
    }


    // /**
    //  * @param App\Http\Requests\UserRequest;;
    //  * @return string imagepath
    //  */
    // public function uploadPhoto($request)
    // {
    //     $image = $request->file('photo');
    //     $imageName = $request->username. "." . $image->getClientOriginalExtension();
    //     $image->storeAs('public/',$imageName);
    //     return $imageName;
    // }


    public function index(Request $request)
    {
        $users = $this->user->list();
        return Inertia::render('User/Index', [
            "users" => $users
        ]);
    }


    /**
     * get one user
     * @param int $id
     */
    public function show($id)
    {
        $user = $this->user->find($id);
        return Inertia::render('User/Show', [
            "user" => $user
        ]);
    }

    /**
     * get one user
     * @param int $id
     */
    public function edit($id)
    {
        $user = $this->user->find($id);
        return Inertia::render('User/Edit', [
            "user" => $user
        ]);
    }


    /**
     * update user
     * @param App\Http\Requests\UserRequest;
     * @param int $id
     */
    public function update(UserRequest $request, $id)
    {
        $payload = $request->only($this->user->getFillable());
        if ($request->file("photo")){
            Log::info("may laman");
            $payload["photo"] = $this->user->upload($request->file('photo'), $payload["username"]);
        }
        $update = $this->user->update($id, $payload);
        $request->session()->flash('success', 'User updated Successfully!');
        return Redirect::route("users.index");
    }


     /**
     * get one user
     * @param int $id
     */
    public function delete(Request $request, $id)
    {
        if ($request->force) {
            $this->user->delete($id);
            $request->session()->flash('success', 'User was permanently deleted!');
        }else {
            $this->user->destroy($id);
            $request->session()->flash('success', 'User move to inactive users (soft deleted)');
        }
        return Redirect::route("users.trashed");
    }


    /**
     * display all soft deleted users
     */
    public function allTrashed()
    {
        $users = $this->user->listTrashed();
        return Inertia::render('User/InactiveUser', [
            "users" => $users
        ]);
    }


    /**
     * Restore soft deleted user
     * @param int id
     * @return Vue component with soft deleted users
     */
    public function restore(Request $request, $id)
    {
        $this->user->restore($id);
        $request->session()->flash('success', 'User successfuly restored and back to active users');
        return Redirect::route("users.trashed");
    }

    // /**
    //  * permanently  delete user
    //  * @param int id
    //  * @return Vue component with soft deleted users
    //  */
    // public function hardDelete(Request $request, $id)
    // {
    //     $this->user->delete($id);
        
    //     return Redirect::route("users.trashed");
    // }

}
