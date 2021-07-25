<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware("auth")->except(["showRegisterForm","register"]);
        $this->user = $user;
    }


    public function showRegisterForm()
    {
        return Inertia::render('User/Register');
    }


    /**
     * @param App\Http\Requests\UserRequest;;
     * @return jsonResponse
     */
    public function register(UserRequest $request)
    {
        $payload = $request->only($this->user->getFillable());
        if ($request->photo){
            $payload["photo"] = $this->uploadPhoto($request);
        }

        $this->user->create($payload);
        $request->session()->flash('success', 'User Registered Successfully! you can sign in now');
        return Redirect::route("showLoginForm");
    }


    /**
     * @param App\Http\Requests\UserRequest;;
     * @return string imagepath
     */
    public function uploadPhoto($request)
    {
        $image = $request->file('photo');
        $imageName = $request->username. "." . $image->getClientOriginalExtension();
        $image->storeAs('public/',$imageName);
        return $imageName;
    }


    public function showUsers(Request $request)
    {
        $id = Auth::user()->id;
        $type = Auth::user()->type;

        $users = $this->user
            ->checkUserType($type)
            ->where("id", "!=", $id)
            ->orderBy('id','DESC')->paginate(5);
        return Inertia::render('User/Index', [
            "users" => $users
        ]);
    }


    /**
     * get one user
     * @param int $id
     */
    public function getUser($id)
    {
        $user = $this->user->findOrFail($id);
        return Inertia::render('User/Show', [
            "user" => $user
        ]);
    }

    /**
     * get one user
     * @param int $id
     */
    public function editUser($id)
    {
        $user = $this->user->findOrFail($id);
        return Inertia::render('User/Edit', [
            "user" => $user
        ]);
    }


    /**
     * update user
     * @param App\Http\Requests\UserRequest;
     * @param int $id
     */
    public function updateUser(Request $request, $id)
    {
        $user = $this->user->findOrFail($id);
        $payload = $request->only($this->user->getFillable());
        if ($request->file("photo")){
            Log::info("with photo");
            Log::info($request->photo);
            $payload["photo"] = $this->uploadPhoto($request);
        }
        $update = $user->update($payload);
        $request->session()->flash('success', 'User updated Successfully!');
        return Redirect::route("showUsers");
    }

}
