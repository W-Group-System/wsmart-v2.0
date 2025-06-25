<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Subsidiary extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
