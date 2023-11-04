<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('client:id,first_name,last_name')
            ->latest()
            ->paginate(4)
            ->through(function ($appointment) {
                return [
                    'status' => [
                        'name' => $appointment->status->name,
                        'color' => $appointment->status->color(),
                    ],
                    'client' => $appointment->client,
                    'start_time' => $appointment->start_time,
                    'end_date' => $appointment->end_time,
                    'id' => $appointment->id,
                ];
            });
        return $appointments;
    }
}
