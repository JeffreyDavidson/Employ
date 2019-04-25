<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the manager of the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function managers()
    {
        return $this->hasMany(User::class);
    }
}
