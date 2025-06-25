<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function submodule()
    {
        return $this->hasMany(SubModule::class,'module_id');
    }
}
