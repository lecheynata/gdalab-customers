<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Disable timestamps defaults
    public $timestamps = false;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['commune'];

    public function commune()
    {
        return $this->hasOne(Commune::class, 'id_com', 'id_com');
    }

    public function region()
    {
        return $this->hasOne(Region::class, 'id_reg', 'id_reg');
    }
}
