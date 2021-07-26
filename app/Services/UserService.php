<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use App\Services\UserServiceInterface;
use Illuminate\Database\Eloquent\Model;

class UserService implements UserServiceInterface
{
   /**
    * The model instance.
    *
    * @var App\User
    */
   protected $model;

   /**
    * The request instance.
    *
    * @var \Illuminate\Http\Request
    */
   protected $request;

   /**
    * Constructor to bind model to a repository.
    *
    * @param \App\User                $model
    * @param \Illuminate\Http\Request $request
    */
   public function __construct(User $model, Request $request)
   {
      $this->model = $model;
      $this->request = $request;
   }

   /**
    * Define the validation rules for the model.
    *
    * @param  int $id
    * @return array
    */
   public function rules($id = null)
   {
      return [
         "prefixname" => "sometimes|in:Mr,Mrs,Ms",
         "firstname" => "required",
         "lastname" => "required",
         "username" => "required|unique:users,username,". $id,
         "email" => "required|unique:users,email,". $id ,
         "password" => "required|min:4",
         "confirm_password" => "required|same:password",
         "photo" => "nullable|mimes:jpeg,png,jpg,gif,svg|max:300"
      ];
   }

   /**
    * Retrieve all resources and paginate.
    *
    * @return array $users
    */
   public function list(int $pageLength = 5)
   {

      $id = Auth::user()->id ?? 0 ;
      $type = Auth::user()->type ?? "user";

      $users = $this->model
            ->checkUserType($type)
            ->where("id", "!=", $id)
            ->orderBy('id','DESC')->paginate($pageLength);

      return $users;
   }

   /**
    * Create model resource.
    *
    * @param  array $attributes
    * @return \Illuminate\Database\Eloquent\Model
    */
   public function store(array $attributes)
   {
      return $this->model->create($attributes);
   }

   /**
    * Retrieve model resource details.
    * Abort to 404 if not found.
    *
    * @param  integer $id
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function find(int $id):? Model
   {
      return $this->model->findOrFail($id);
   }

   /**
    * Update model resource.
    *
    * @param  integer $id
    * @param  array   $attributes
    * @return boolean
    */
   public function update(int $id, array $attributes): bool
   {
      $user = $this->model->find($id);
      return $user->update($attributes);
   }

   /**
    * Soft delete model resource.
    *
    * @param  integer|array $id
    * @return void
    */
   public function destroy($id)
   {
      $user = $this->model->findOrFail($id);
      $user->delete();
   }

   /**
    * Include only soft deleted records in the results.
    *
    * @return array soft deleted users
    */
   public function listTrashed(int $pageLength = 5)
   {
      $type = Auth::user()->type;
      return $users = $this->model
            ->checkUserType($type)
            ->onlyTrashed()
            ->orderBy('id','DESC')->paginate($pageLength);
   }

   /**
    * Restore model resource.
    *
    * @param  integer|array $id
    * @return void
    */
   public function restore($id)
   {
      $user = $this->model->withTrashed()->where("id", $id)->first();
      $user->restore();
   }

   /**
    * Permanently delete model resource.
    *
    * @param  integer|array $id
    * @return void
    */
   public function delete($id)
   {
      $user = $this->model->withTrashed()->where("id", $id)->first();
      $user->forceDelete();
   }


   /**
    * Upload the given file.
    *
    * @param  \Illuminate\Http\UploadedFile $file
    * @return string|null
    */
   public function upload(UploadedFile $file, $filename)
   {
      $imageName = $filename. "." . $file->getClientOriginalExtension();
      $file->storeAs('public/',$imageName);
      return $imageName;
   }
   

   /**
    * get Fillable columns of the  model
    @return array
    */
   public function getFillable()
   {
      return $this->model->getFillable();
   }


   public function getBy($column, $value)
   {
      return $this->model->where($column, "=", $value);
   }

}