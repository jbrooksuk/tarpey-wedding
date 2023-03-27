<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateInviteCode extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $family) {
            // If the family doesn't have an invite, generate it.
            if ($family->doesntHave('invite')) {
                $first = $family->members()->where('child', '=', false)->oldest()->limit(2)->get()->map(function ($member) {
                    return Str::limit($member->first_name, 1, '');
                })->implode('');

                $second = Str::limit($family->name, 3, '');

                $code = strtoupper($first.'-'.$second.'-23');


                $family->invite()->create(compact('code'));

            }

            $this->markAsFinished($family);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
