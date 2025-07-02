<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
