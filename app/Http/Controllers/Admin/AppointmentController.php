<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with('client:id,first_name,last_name')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', AppointmentStatus::from($request->status));
            })
            ->latest()
            ->paginate()
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

    public function fetchAppointments()
    {
        $cases = AppointmentStatus::cases();

        return collect($cases)->map(function ($status) {
            return [
                'name' => $status->name,
                'value' => $status->value,
                'color' => AppointmentStatus::from($status->value)->color(),
                'count' => Appointment::where('status', $status->value)->count(),
            ];
        });
    }

    public function createAppointment(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'client' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        return Appointment::create([
            'client_id' => 1,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => AppointmentStatus::SCHEDULE,
        ]);
    }

    public function editAppointment(Appointment $appointment)
    {
        return $appointment;
    }

    public function updateAppointment(Appointment $appointment, Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'client' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        return $appointment->update([
            'client_id' => 2,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => AppointmentStatus::SCHEDULE,
        ]);
    }

    public function deleteAppointment(Appointment $appointment)
    {
        $appointment->delete();
        return response()->noContent();
    }
}
