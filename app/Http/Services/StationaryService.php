<?php

namespace  App\Http\Services;

use App\Models\Stationaries;
use App\Models\User;
use App\Notifications\SupervisorApprovedStationaryNotification;
use App\Notifications\UserStationaryNotification;
use Illuminate\Support\Facades\Notification;

class StationaryService 
{
    public function sendEmailAfterCreateStationary($from, $code)
    {
        $from_staff = User::findOrFail($from);
        $to_supervisor = User::role('Supervisor')->where('department_id', $from_staff->department_id)->get();
        Notification::send($to_supervisor, new UserStationaryNotification($from_staff, $code));
    }

    public function sendEmailAfterApprovingStationary($from, $code)
    {
        $from_supervisor = User::findOrFail($from);
        $stationary = Stationaries::where('kode', $code)->first();
        $to_user = User::findOrFail($stationary->id_user);
        Notification::send($to_user, new SupervisorApprovedStationaryNotification($from_supervisor, $code));
    }
}