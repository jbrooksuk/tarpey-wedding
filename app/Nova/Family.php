<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;

class Family extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Family';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = null;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            Text::make('Family Name', 'name')->sortable()->displayUsing(function () {
                if ($this->members()->count()) {
                    return Str::limit($this->members()->orderBy('id', 'asc')->first()->first_name, 1, '.') . ' ' . $this->name;
                }

                return $this->name;
            }),

            Number::make('Family Members', function () {
                return $this->members()->count();
            })->onlyOnIndex(),

            Boolean::make('Evening'),

            HasMany::make('Family Members', 'members'),

            Markdown::make('Notes'),

//            Boolean::make('Attending', function () {
//                return $this->is_attending;
//            })->onlyOnIndex()->sortable(),
            Text::make('Attending', function () {
                $members = $this->members()
                                ->toBase()
                                ->selectRaw('
                                       families.evening,
                                    SUM(
                                        CASE WHEN families.evening = 0 AND attending IS NULL THEN 1
                                             WHEN families.evening = 1 AND attending_evening IS NULL THEN 1
                                        END
                                    ) AS unknown,
                                    SUM(
                                        CASE WHEN families.evening = 0 AND attending = 0 THEN 1
                                             WHEN families.evening = 1 AND attending_evening = 0 THEN 1
                                        END
                                    ) AS not_attending,
                                    SUM(
                                        CASE WHEN families.evening = 0 AND attending = 1 THEN 1
                                             WHEN families.evening = 1 AND attending_evening = 1 THEN 1
                                        END
                                    ) AS attending
                                ')
                                ->join('families', 'family_members.family_id', '=', 'families.id')
                                ->first();

                $strings = [];
                $allAttending = false;
                $allNotAttending = false;
                $familyMembers = $this->members()->count();

                if ($familyMembers === 0) {
                    return '&mdash;';
                }

                if (optional($members)->attending > 0) {
                    if ($members->attending == $familyMembers) {
                        $strings[] = 'Everyone attending';
                        $allAttending = true;
                    } else {
                        $strings[] = $members->attending.' attending';
                    }
                }

                if (!$allAttending && optional($members)->not_attending > 0) {
                    if ($members->not_attending == $familyMembers) {
                        $strings[] = 'Nobody attending';
                        $allNotAttending = true;
                    } else {
                        $strings[] = $members->not_attending.' not attending';
                    }
                }

                if ((!$allAttending || !$allNotAttending) && optional($members)->unknown > 0) {
                    if ($members->unknown == $familyMembers) {
                        $strings[] = 'Waiting on replies';
                    } else {
                        $strings[] = $members->unknown. ' not replied';
                    }
                }

                return implode(', ', $strings);
            })->onlyOnIndex()->asHtml(),

            new Panel('Contact Information', [
                Text::make('Email Address', 'email')->hideFromIndex(),
            ]),

            HasOne::make('Invite'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\Attending,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
//            (new Actions\SendSMS)->showOnTableRow(),
            (new Actions\GenerateInviteCode)->showOnTableRow(),
        ];
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string
     */
    public function subtitle()
    {
        return $this->members->implodeLast('first_name', ', ', ' and ');
    }
}
