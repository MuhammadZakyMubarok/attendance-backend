<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeePermit;
use Illuminate\Database\QueryException;

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
                'message' => 'Berhasil submit form Employee Permit'
            ], 200);
        }catch(QueryException $e){
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }
}
