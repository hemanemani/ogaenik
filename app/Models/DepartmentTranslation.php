<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentTranslation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  
  protected $fillable = ['name', 'lang', 'department_id'];

  public function department(){
    return $this->belongsTo(Department::class);
  }
}
