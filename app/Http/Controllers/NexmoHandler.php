<?php

namespace App\Http\Controllers;

use App\Family;
use App\Notifications\WeddingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Nexmo\Laravel\Facade\Nexmo;
use Nexmo\Message\InboundMessage;

class NexmoHandler
{
    public function __invoke(Request $request)
    {
        $message = InboundMessage::createFromGlobals();

        $messageText = strtoupper(trim($message->getBody()));
        $messageFrom = $message->getFrom();

        $family = Family::with(['members', 'numbers' => function ($query) use ($messageFrom) {
            $query->where('family_numbers.phone_number', '=', "+{$messageFrom}");
        }])->first();

        if ($messageText === 'EVENING YES') {
            $this->handleEveningAttendance($family);

            Nexmo::message()->send([
                'to' => $messageFrom,
                'from' => Config::get('services.nexmo.sms_from'),
                'text' => 'Thanks! We can\'t wait to party some more with you!',
            ]);
        }

        return response('ok');
    }

    protected function handleEveningAttendance(Family $family)
    {
        $family->members->each(function ($familyMember) {
            $familyMember->update(['attending_evening' => true]);
        });
    }
}
