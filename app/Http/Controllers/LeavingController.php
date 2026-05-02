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
            'unique_id' => 'required|string',
            'long_period' => 'integer',
            'sisa' => 'integer',
            'user_id' => 'required|integer'
        ]);
        try{
            Leaving::create($validated);

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
