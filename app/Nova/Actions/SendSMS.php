<?php

namespace App\Nova\Actions;

use App\Family;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Nexmo\Laravel\Facade\Nexmo;

class SendSMS extends Action implements ShouldQueue
{
    use Actionable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Send SMS Message';

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
            try {
                $family->members()->whereNotNull('phone_number')->get()->each(function ($familyMember) use ($family, $fields) {
                    Nexmo::message()->send([
                        'to' => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', trim($familyMember->phone_number)),
                        'from' => config('services.nexmo.sms_from'),
                        'text' => $this->formatMessage($family, $fields->message),
                    ]);
                });

                $this->markAsFinished($family);
            } catch (\Exception $e) {
                $this->markAsFailed($family, $e);
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Textarea::make('Message')->help('{{family}} will be replaced with the name of the family.<br>{{code}} will be replaced with the families invite code.'),
        ];
    }

    /**
     * Formats a message by replacing some variables.
     *
     * @param  \App\Family  $family
     * @param  string  $message
     *
     * @return string
     */
    protected function formatMessage(Family $family, string $message): string
    {
        $message = str_replace('{{family}}', $family->name, $message);
        $message = str_replace('{{ family }}', $family->name, $message);
        $message = str_replace('{{name}}', $family->name, $message);
        $message = str_replace('{{ name }}', $family->name, $message);

        $message = str_replace('{{code}}', optional($family->invite)->code, $message);
        $message = str_replace('{{ code }}', optional($family->invite)->code, $message);
        $message = str_replace('{{invite}}', optional($family->invite)->code, $message);
        $message = str_replace('{{ invite }}', optional($family->invite)->code, $message);

        return $message;
    }

    /**
     * Format the phone number.
     *
     * @param string $phoneNumber
     *
     * @return string
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Strip the spaces.
        $phoneNumber = trim(str_replace(' ', '', $phoneNumber));

        // Replace the leading 0 with +44
        if (Str::startsWith($phoneNumber, '07')) {
            $phoneNumber = Str::replaceFirst('07', '447', $phoneNumber);
        }

        // If the phone number contains (0) then replace it.
        if (Str::contains($phoneNumber, '(0)')) {
            $phoneNumber = Str::replaceFirst('(0)', '', $phoneNumber);
        }

        return $phoneNumber;
    }
}
