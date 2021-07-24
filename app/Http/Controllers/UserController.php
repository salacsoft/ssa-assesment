<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
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
}
