<?php

namespace  App\Http\Services;

use App\Models\Stationaries;
use App\Models\User;
use App\Notifications\SupervisorApprovedStationaryNotification;
use App\Notifications\UserStationaryNotification;
use Illuminate\Support\Facades\Notification;

class StationaryService 
{
    public function sendEmailAfterCreateStationary($from, $to, $stationary_number)
    {
        $from_staff = User::findOrFail($from);
        $to_supervisor = User::findOrFail($to);
        Notification::send($to_supervisor, new UserStationaryNotification($from_staff, $stationary_number));
    }

    public function sendEmailAfterApprovingStationary($from, $stationary_number)
    {
        $from_supervisor = User::findOrFail($from);
        $stationary = Stationaries::where('kode', $stationary_number)->first();
        $to_user = User::findOrFail($stationary->id_user);
        Notification::send($to_user, new SupervisorApprovedStationaryNotification($from_supervisor, $stationary_number));
    }
}