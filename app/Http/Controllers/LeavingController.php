<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                'isSuccess' => true,
                'message' => 'Berhasil submit form Leaving'
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'isSuccess' => false,
                'message' => $e
            ], 201);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
