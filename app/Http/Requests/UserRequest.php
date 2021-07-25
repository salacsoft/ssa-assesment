<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "prefixname" => "sometimes|in:Mr,Mrs,Ms",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required|unique:users,username,". $this->id ,
            "email" => "required|unique:users,email,". $this->id ,
            "password" => "required|min:4",
            "confirm_password" => "required|same:password",
            "photo" => "nullable|sometimes|mimes:jpeg,png,jpg,gif,svg"
        ];
    }
}
