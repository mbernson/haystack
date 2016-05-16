<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'title',
        'status',
        'occurences',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function events() {
        return $this->hasMany(Event::class);
    }
}
