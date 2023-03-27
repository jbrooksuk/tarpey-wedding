<?php

namespace App\Http\Controllers;

use App\Family;
use App\Invite;
use App\Announcement;
use App\FamilyMember;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Nexmo\Laravel\Facade\Nexmo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class AppController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function handleInvite(Request $request)
    {
        if ($invite = Invite::where('code', '=', $request->get('invite'))->first()) {
            return redirect(URL::signedRoute('invite', ['invite' => $request->get('invite')]));
        }

        return back()->withErrors(['We couldn\'t find your invite, please try again.']);
    }

    public function invite(Request $request)
    {
        // We're using signatures so that people don't hack other invites.
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        // If we have a matching invite, add it to the session and show the invite page!
        if ($invite = Invite::where('code', '=', $request->get('invite'))->first()) {
            $announcements = Announcement::forFamily($invite->family);

            $request->session()->put(['invite' => $request->get('invite')]);

            // If the invite does not have an opened at time, then update it.
            if (!$invite->opened_at) {
                $invite->update(['opened_at' => now()]);
            }

            return view('invite', ['family' => $invite->family, 'announcements' => $announcements]);
        }

        abort(401);
    }

    public function rsvp(Request $request, Family $family)
    {
        $rules = [
            'members' => ['required', 'array'],
            'members.*.attending' => [Rule::requiredIf(function () use ($family) {
                return $family->evening === false;
            })],
            'members.*.attending_evening' => [Rule::requiredIf(function () use ($family) {
                return $family->evening === true;
            })],
        ];

         foreach ($request->members as $key => $value) {
             $rules['members.'.$key.'.starter_food_choice'] = Rule::requiredIf(function () use ($family, $request, $key) {
                return $family->evening === false && (bool) $request->members[$key]['attending'] === true && (bool) $request->members[$key]['child'] === false;
             });

             $rules['members.'.$key.'.main_food_choice'] = Rule::requiredIf(function () use ($family, $request, $key) {
                 return $family->evening === false && (bool) $request->members[$key]['attending'] === true && (bool) $request->members[$key]['child'] === false;
             });

             $rules['members.'.$key.'.dessert_food_choice'] = Rule::requiredIf(function () use ($family, $request, $key) {
                 return $family->evening === false && (bool) $request->members[$key]['attending'] === true && (bool) $request->members[$key]['child'] === false;
             });

             $rules['members.'.$key.'.dietary_requirements'] = Rule::requiredIf(function () use ($family, $request, $key) {
                 return $family->evening === false && (int) $request->members[$key]['main_food_choice'] === 3;
             });
         }

        $request->validate($rules, [
            'members.*.starter_food_choice.required' => 'Please select a starter.',
            'members.*.main_food_choice.required' => 'Please select a main course.',
            'members.*.dessert_food_choice.required' => 'Please select a dessert.',
            'members.*.dietary_requirements.required' => 'Please provide dietary requirements for any alternative meal options.',
        ]);

        collect($request->members)->each(function ($member) use ($family) {
            $isAttending = false;
            if ($family->evening === false) {
                $isAttending = (int) $member['attending'];
            } else {
                $isAttending = (int) $member['attending_evening'];
            }

            if (!$isAttending) {
                $member['starter_food_choice'] = null;
                $member['main_food_choice'] = null;
                $member['dessert_food_choice'] = null;
            }

            $family->members()->where('id', '=', Arr::get($member, 'id'))->first()->update($member);
        });

        if (!config('app.debug')) {
            // Send a notification to us to say that we've had an RSVP update.
            Nexmo::message()->send([
                'to' => '+447527547073',
                'from' => config('services.nexmo.sms_from'),
                'text' => "The {$family->name} family have sent an RSVP!",
            ]);
            Nexmo::message()->send([
                'to' => '+447940177394',
                'from' => config('services.nexmo.sms_from'),
                'text' => "The {$family->name} family have sent an RSVP!",
            ]);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
