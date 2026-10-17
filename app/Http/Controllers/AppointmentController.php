<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isStudent()) {
            $appointments = Appointment::query()
                ->where('student_id', $user->id)
                ->with('staff')
                ->latest()
                ->get();
            $staff = User::query()
                ->staffResponsibleFor($user->program?->college_id)
                ->orderBy('name')
                ->get();
        } elseif ($user->isCounselor()) {
            $appointments = Appointment::query()
                ->where('staff_id', $user->id)
                ->whereHas('student', function (Builder $students) use ($user): void {
                    $students->studentsVisibleTo($user);
                })
                ->with(['student.program.college', 'staff'])
                ->latest()
                ->get();
            $staff = collect();
        } else {
            $appointments = Appointment::query()
                ->with(['student.program.college', 'staff'])
                ->latest()
                ->get();
            $staff = collect();
        }

        return view('appointments.index', compact('appointments', 'staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = $request->user();
        abort_unless($student->isStudent(), 403);

        $validated = $request->validate([
            'staff_id' => ['required', 'integer', 'exists:users,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'purpose' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $staffMember = User::query()
            ->staffResponsibleFor($student->program?->college_id)
            ->findOrFail($validated['staff_id']);

        Appointment::create([
            'student_id' => $student->id,
            'staff_id' => $staffMember->id,
            'scheduled_at' => $validated['scheduled_at'],
            'purpose' => $validated['purpose'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')->with('status', 'Appointment requested successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $user = $request->user();

        if (! $user->isDirector()) {
            abort_unless(
                $user->isCounselor()
                    && $appointment->staff_id === $user->id
                    && $appointment->student?->isVisibleTo($user),
                404,
            );
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,completed,rejected'],
        ]);

        $appointment->update(['status' => $validated['status']]);

        return back()->with('status', 'Appointment status updated successfully.');
    }
}
