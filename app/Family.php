<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Support\Facades\DB;

/**
 * @property bool $evening
 */
class Family extends Model
{
    use Actionable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'notes',
        'email',
        'evening',
    ];

    protected $casts = [
        'evening' => 'bool',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['members'];

    public function invite()
    {
        return $this->hasOne(Invite::class);
    }

    public function members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function getAnnouncementTypesAttribute()
    {
        if ($this->evening) {
            return ['All', 'Evening'];
        }

        return ['All', 'All Day'];
    }

    public function getIsAttendingAttribute()
    {
        return $this->members()->attending()->count() > 0;
    }
}
