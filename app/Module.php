<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Module extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    public function submodule()
    {
        return $this->hasMany(SubModule::class,'module_id');
    }
}
