<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeePermit;

class EmployeePermitController extends Controller
{
    public function index(){
        $employeePermit = EmployeePermit::all();
        return response()->json($employeePermit, 200);
    }
    public function store(Request $request){
        $validated = $request->validate([
            'dt_permit' => 'required|string',
            'needs' => 'string',
            'purpose' => 'string',
            'dt_mulai' => 'string',
            'dt_selesai' => 'string',
            'jam_mulai' => 'string',
            'jam_selesai' => 'string',
            'long_period' => 'integer',
            'permit_statement' => 'string',
            'user_id' => 'required|integer',
            'unique_id' => 'required|string',
        ]);
        try{
            EmployeePermit::create($validated);
            return response()->json([
                'isSuccess' => true,
                'message' => 'Berhasil submit form Employee Permit'
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'isSuccess' => false,
                "message" => $e
            ], 500);
        }
    }
}
