<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PurchaseRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function subsidiary()
    {
        return $this->belongsTo(Subsidiary::class);
    }
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
    public function purchaseRequestFile()
    {
        return $this->hasMany(PurchaseRequestFile::class);
    }
}
