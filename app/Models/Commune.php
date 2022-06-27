<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['regions'];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'id_com', 'id_com');
    }

    public function regions()
    {
        return $this->hasMany(Region::class, 'id_reg', 'id_reg');
    }
}
