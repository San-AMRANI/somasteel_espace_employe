<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{
    public function index()
    {
        $users = User::where('responsable_hiarchique', Auth::user()->matricule)->get(['id', 'nom', 'prénom', 'profile_picture' ]);
        $shifts = Shift::all();

        $today = Carbon::today();
        if (Auth::user()->isRH()) {
            $résultatData = Attendance::select( 'users.service', 'shifts.name as shift_name', 'users.nom', 'users.prénom', 'attendances.status', 'attendances.date')
                ->join('shifts', 'attendances.shift_id', '=', 'shifts.id')
                ->join('users', 'attendances.user_id', '=', 'users.id')
                ->whereDate('date', $today)
                ->orderBy('shifts.name')
                ->get();
        }elseif (Auth::user()->isResponsable()) {
            $résultatData = Attendance::select('shifts.name as shift_name', 'users.nom', 'users.prénom', 'attendances.status', 'attendances.date')
                ->join('shifts', 'attendances.shift_id', '=', 'shifts.id')
                ->join('users', 'attendances.user_id', '=', 'users.id')
                ->where('responsable_hiarchique', Auth::user()->matricule)
                ->whereDate('date', $today)
                ->orderBy('shifts.name')
                ->get();
        }
        $attendances = Attendance::whereDate('date', $today)->get();
        // dd($attendances);
        return view('absence.absenceDec', compact('users', 'shifts', 'résultatData', 'attendances'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
        ]);

        try{
            foreach ($request->employee_ids as $userId) {
                $status = $request->input('status_' . $userId . '_' . $request->shift_id);
                Attendance::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'shift_id' => $request->shift_id,
                        'date' => now()->toDateString(),
                    ],
                    ['status' => $status]
                );
            }

            return redirect()->back()->with('succes', 'List de Présence est enregistré avec succès.');

        }catch(Exception $e){
            return redirect()->back()->withInput()->with('error', 'Un erreur ce produit vouliez vérifier votre choix!.');
        }
    }
}
