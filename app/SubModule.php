<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $table ='submodules';
    
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
