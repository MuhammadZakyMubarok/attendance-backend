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
            'dt_mulai' => 'nullable|string',
            'dt_selesai' => 'nullable|string',
            'jam_mulai' => 'nullable|string',
            'jam_selesai' => 'nullable|string',
            'long_period' => 'string',
            'permit_statement' => 'string',
        ]);
        try{
            $user = $request->user();

            $data = array_merge($validated, [
                'user_id' => $user->id,
                'unique_id' => $user->unique_id,
            ]);

            EmployeePermit::create($data);
            return response()->json([
                'message' => 'Berhasil submit form Employee Permit'
            ], 200);
        }catch(QueryException $e){
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'               => 'required|integer',
            'dt_permit'        => 'required|string',
            'needs'            => 'string',
            'purpose'          => 'string',
            'dt_mulai'         => 'nullable|string',
            'dt_selesai'       => 'nullable|string',
            'jam_mulai'        => 'nullable|string',
            'jam_selesai'      => 'nullable|string',
            'long_period'      => 'string',
            'permit_statement' => 'string',
        ]);

        try {
            $user = $request->user();

            $permitId = $validated['id'];
            unset($validated['id']); 

            $updated = EmployeePermit::query()
                ->where('id', $permitId)
                ->where('user_id', $user->id)
                ->update($validated);

            if (!$updated) {
                return response()->json([
                    'message' => 'Data permit tidak ditemukan atau Anda tidak memiliki akses.'
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil mengubah data'
            ], 200);

        } catch(QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
    }

    public function getFormNumber(Request $request)
    {
        try {
            $user = $request->user();

            $formNumber = EmployeePermit::query()->where('user_id', '=', $user->id)
                    ->orderByDesc('id')
                    ->value('formNumber');

            return response()->json([
                'formNumber' => $formNumber ?? null
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
    }
}
