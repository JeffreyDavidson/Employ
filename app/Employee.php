<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Format the employees's telephone number.
     *
     * @return string|null
     */
    public function getFormattedTelephoneAttribute()
    {
        return $this->telephone ? "(" . substr($this->telephone, 0, 3) . ") " . substr($this->telephone, 3, 3) . "-" . substr($this->telephone, 6) : null;
    }
}
