<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class FamilyMember extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\FamilyMember';

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
        'id', 'first_name', 'last_name',
    ];

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

            Text::make('First Name')->sortable(),

            Text::make('Last Name')->sortable(),

            Boolean::make('Child')->sortable(),

            Text::make('Attending', function () {
                switch ((string) $this->attending) {
                    case null:
                    case '': return '—';
                    case 0: return 'Not Attending';
                    case 1: return 'Attending';
                }
            })->onlyOnIndex(),

            Text::make('Attending Evening', function () {
                switch ((string) $this->attending_evening) {
                    case null:
                    case '': return '—';
                    case 0: return 'Not Attending';
                    case 1: return 'Attending';
                }
            })->onlyOnIndex(),

            Select::make('Attending')->options([
                null => 'Unknown',
                0 => 'Not Attending',
                1 => 'Attending',
            ])->displayUsingLabels()->hideFromIndex(),

            Select::make('Attending Evening')->options([
                null => 'Unknown',
                0 => 'Not Attending',
                1 => 'Attending',
            ])->displayUsingLabels()->hideFromIndex(),

            Select::make('Starter Food Choice')->options([
                '-',
                'Bruschetta',
                'Ham Hock Terrine',
            ])->displayUsingLabels(),

            Select::make('Main Food Choice')->options([
                '-',
                'Slow Cooked Belly Pork, Apple Cider Gravy, Dauphinois Potatoes and Seasonal Veg',
                'Slow Braised Shin Beef, Carrots and Greens, Shallots, Creamed Mashed Potatoes',
                'Alternative (Please Provide Dietary Requirements)',
            ])->displayUsingLabels(),

            Select::make('Dessert Food Choice')->options([
                '-',
                'Chocolate Brownie with Ice Cream',
                'Sticky Toffee Pudding',
            ])->displayUsingLabels(),

            Text::make('Phone Number')->hideFromIndex(),

            Markdown::make('Dietary Requirements'),

            BelongsTo::make('Family')->hideFromIndex()->searchable(),
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
        return [];
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
            (new Actions\MarkAsAttending)->showOnTableRow(),
            (new Actions\MarkAsNotAttending)->showOnTableRow(),
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Family Members';
    }
}
