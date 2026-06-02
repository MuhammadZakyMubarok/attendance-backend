<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Leaving;

class LeavingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaving = Leaving::all();
        return response()->json($leaving, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave' => 'required|string',
            'dt_leave' => 'required|string',
            'dt_mulai' => 'required|string',
            'dt_selesai' => 'required|string',
            'long_period' => 'integer',
            'sisa' => 'integer',
            'formNumber' => 'required|integer'
        ]);
        
        try{
            $user = $request->user();
            // ini jika satu satu
            // $validated['user_id'] = $user->id;
            // $validated['unique_id'] = $user->unique_id;

            // ini jika digabungkan
            $data = array_merge($validated, [
                'user_id'   => $user->id,
                'unique_id' => $user->unique_id,
            ]);
            
            Leaving::create($data);

            return response()->json([
                'message' => 'Berhasil submit form Leaving'
            ], 200);
        } catch(QueryException $e){
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        };
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'leave' => 'string',
            'dt_leave' => 'string',
            'dt_mulai' => 'string',
            'dt_selesai' => 'string',
            'long_period' => 'integer',
            'sisa' => 'integer',
        ]);
        
        try{
            $user = $request->user();

            $leavingId = $validated['id'];
            unset($validated['id']);
            
            $updated = Leaving::query()->where('id',$leavingId)
                ->where('user_id', $user->id)
                ->update($validated);

            if(!$updated){
                return response()->json([
                    'message' => 'Data permit tidak ditemukan atau Anda tidak memiliki akses.'
                ], 404);
            }

            if($request->latestDataLeaveBalance){
                Leaving::query()
                    ->where('user_id', $user->id)
                    ->orderByDesc('id')
                    ->first()
                    ?->update([ // tanda ? sebagai null safe operator
                        'sisa' => $request->latestDataLeaveBalance
                    ]);
            }

            return response()->json([
                'message' => 'Berhasil mengubah data'
            ], 200);

        } catch(QueryException $e){
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        };
    }

    public function getRemainingLeaveBalance(Request $request)
    {
        try {
            $user = $request->user();

            $sisa = Leaving::query()->where('user_id', '=', $user->id)
                ->orderByDesc('id')
                ->value('sisa');

            return response()->json([
                'sisa' => $sisa ?? null
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
    }

    public function getFormNumber(Request $request)
    {
        try {
            $user = $request->user();

            $formNumber = Leaving::query()->where('user_id', '=', $user->id)
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
