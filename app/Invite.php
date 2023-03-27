<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['opened_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'family_id',
        'code',
        'opened_at',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['family'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
