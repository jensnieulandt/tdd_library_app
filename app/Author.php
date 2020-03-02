<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];
    protected $dates = ['date_of_birth'];

    public function setDateOfBirthAttribute($dateOfBirth)
    {
        $this->attributes['date_of_birth'] = Carbon::parse($dateOfBirth);
    }
}
