<?php

namespace App\Http\Controllers;
use App\Models\User;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnuaireController extends Controller
{
    public function index(){
        // $departments = DB::table('SELECT DISTINCT service FROM users WHERE service != '' AND service IS NOT NULL;');
        $departments = DB::table('services')
                    ->where('nomService', '!=', '')
                    ->whereNotNull('nomService')
        			->orderBy('nomService', 'asc')
                    ->pluck('nomService');

        return view("annuaire.index",[
            "departments"=> $departments,
        ]);
    }

    public function showDepartment($depart){

        $users = User::where('service', $depart)
            ->select('id', 'nom', 'prénom', 'matricule', 'fonction', 'service AS depart', 'profile_picture')
            ->get();
        $responsables = DB::table('responsables')->get();
        $directeurs = DB::table('directeurs')->get();

        return view("annuaire.showDepartment", [
            'employees' => $users,
            'depart' => $depart,
            'responsables'=> $responsables,
            'directeurs'=> $directeurs
        ]);
    }
    public function showEmployee($depart, $employee_nom, $employee_id)
    {
        //employe infos
        $employee = User::findOrFail($employee_id);
    
        //leur responsable
        $responsable = User::where('matricule', $employee->responsable_hiarchique)->first(['nom', 'prénom']);
        
        //leur dir
        $directeur = User::where('matricule', $employee->directeur)->first(['nom', 'prénom']);

        //les service a choisir
        $services = DB::table('services')->pluck('nomService');

        //les resp & dir a choisi
        $responsables = DB::table('responsables')->get();
        $directeurs = DB::table('directeurs')->get();
        
        $employee->resp_matricule = $employee->responsable_hiarchique;
        $employee->dir_matricule = $employee->directeur;

        $employee->responsable_hiarchique = $responsable ? ($responsable->nom . ' ' . $responsable->prénom) : null;
        $employee->directeur = $directeur ? ($directeur->nom . ' ' . $directeur->prénom) : null;
        
        if ($employee->service !== $depart) {
            abort(404, 'Employee not found in this department');
        }
        return view('annuaire.showEmployee', compact('employee', 'services', 'responsables', 'directeurs'));
    }


    // public function editEmp($depart, $employee_nom, $employee_id){
    //     $employee = User::findOrFail($employee_id);
    //     return view('annuaire.editEmployee', compact('employee'));
    // }

    public function updateEmp($employee_id, Request $request){
        try {
            $rules = [
                'nom' => 'required|string|max:100',
                'prénom' => 'required|string|max:100',
                'email' => 'nullable|email|max:255',
                'matricule' => 'required|integer',
                'fonction' => 'required|string|max:255',
                'service' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'solde_conge' => 'required|numeric',
                'responsable_hiarchique' => 'nullable|string|max:255',
                'directeur' => 'nullable|string|max:255',
            ];

            // Validate the request data
            $validatedData = $request->validate($rules);
            // dd($request, $validatedData);

            // Find the employee
            $employee = User::findOrFail($employee_id);

            // Update the employee with validated data
            $employee->update($validatedData);
            $employee->refresh();

            // Redirect back with a success message
            return redirect()->route('annuaire.depart', $employee->service)->with('succes', 'Informations modifiées avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error lors de la modification, veuillez vérifié les Information saisi');
        }
    }

    public function storeEmployee(Request $request)
    {
        // Validate the incoming request
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prénom' => 'required|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users',
                'matricule' => 'required|string|max:50|unique:users',
                'fonction' => 'string|max:255',
                'service' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'solde_conge' => 'max:100',
                'responsable_hiarchique' => 'nullable|string|max:255',
                'directeur' => 'nullable|string|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Create the new employee
            $user = new User();
            $user->nom = $validatedData['nom'];
            $user->prénom = $validatedData['prénom'];
            $user->email = $validatedData['email'];
            $user->matricule = $validatedData['matricule'];
            $user->fonction = $validatedData['fonction'];
            $user->service = $validatedData['service'];
            $user->type = $validatedData['type'];
            $user->solde_conge = $validatedData['solde_conge'] ?? 0;
            $user->responsable_hiarchique = $validatedData['responsable_hiarchique'] ?? null;
            $user->directeur = $validatedData['directeur'] ?? null;
            $user->password = bcrypt($validatedData['password']);
            $user->save();

            return redirect()->route('annuaire.depart', $validatedData['service'])->with('succes', 'Employee crée avec succès.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création vouliez ressayer plus tard! <br>'.$e->getMessage());
        }
    }


    public function destroyEmp($employee_id)
    {
        try {
            $employee = User::findOrFail($employee_id);
            $depart = $employee->service;
            $employee->delete();
    
            return redirect()->route('annuaire.depart', $depart)->with('succes', 'Employee Supprimé avec succès!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error lors de la Suppression <br>' . $e->getMessage());
        }
    }

    public function changePassword($employee_id, Request $request){
        try{
            $validatedData = $request->validate([
                'new_password'=> 'required|min:8|max:16',
                'confirm_new_password'=> 'required|same:new_password',
            ]);
            $user = User::findOrFail($employee_id);
            $user->update([
                'password'=> bcrypt($validatedData['new_password']),
            ]);
            return redirect()->back()->with('succes', 'Mot de passe changé avec succès.');
        }catch (Exception $e) {
            return redirect()->back()->withInput()->with('error',   'Erreur lors de la modification'.$e->getMessage());
        }
    }

    public function storeDepart(Request $request){
        try {
            
            $validatedData = $request->validate([
                'nomService' => 'required|string|max:255',
            ]);
            DB::table('services')->insert([
                'nomService' => $validatedData['nomService']
            ]);
            return redirect()->back()->with('succes', 'Département crée avec succès.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Un erreur ce produit lors de la création.' . $e->getMessage());
        }
    }

}
