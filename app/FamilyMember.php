<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Builder;

class FamilyMember extends Model
{
    use Actionable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'family_id',
        'first_name',
        'last_name',
        'attending',
        'phone_number',
        'attending_evening',
        'starter_food_choice',
        'main_food_choice',
        'dessert_food_choice',
        'dietary_requirements',
        'child',
    ];

    protected $casts = [
        'attending' => 'bool',
        'attending_evening' => 'bool',
        'starter_food_choice' => 'int',
        'main_food_choice' => 'int',
        'dessert_food_choice' => 'int',
        'child' => 'bool',
    ];

    protected $hidden = [
        'cost',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function scopeAttending(Builder $query)
    {
        return $query->where('attending', '=', 1);
    }

    public function scopeAttendingEvening(Builder $query)
    {
        return $query->where('attending_evening', '=', 1);
    }

    public function scopeAdults(Builder $query)
    {
        return $query->where('child', '=', false);
    }
}
