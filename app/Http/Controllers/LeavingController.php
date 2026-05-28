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

    /**
     * Display the specified resource.
     */
    public function fetchData(Request $request)
    {
        try {
            $user = $request->user();

            $leaving = Leaving::query()->where('user_id', '=', $user->id)->get();

            return response()->json($leaving, 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
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
                    ->orderByDesc($user->id)
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
