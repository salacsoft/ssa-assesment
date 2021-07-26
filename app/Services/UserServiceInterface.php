<?php 
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface 
{

   public function rules($id = null);

   public function list(int $pageLength = 5);

   public function store(array $attributes);

   public function find(int $id):? Model;

   public function update(int $id, array $attributes): bool;

   public function destroy($id);

   public function listTrashed(int $pageLength = 5);

   public function restore($id);

   public function delete($id);

   public function upload(UploadedFile $file, $filename);

   public function getFillable();

   public function getBy($column, $value);

}