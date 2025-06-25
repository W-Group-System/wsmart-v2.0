<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubModule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table ='submodules';
    
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
