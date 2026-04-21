<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Database\QueryException;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = Attendance::all();

        return response()->json($attendance, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|integer',
            'unique_id' => 'required|string',
            'date'      => 'required|string',
            'time_in' => 'required|string',
            'location_in' => 'required|string',
        ]);
        try {
            Attendance::create($validated);

            return response()->json([
                'isSuccess' => true,
                'message' => 'Berhasil melakukan absensi masuk'
            ], 201);

        } catch (QueryException $e) {
            //untuk $e
            // Log::error($e);
            //Message tidak boleh langsung $e pada tahap develop boleh
            return response()->json([
                'isSuccess' => false,
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|integer',
            'date'      => 'required|string',
            'time_out' => 'required|string',
            'location_out' => 'required|string',
        ]);
        try {
            $attendance = Attendance::where('user_id', $validated['user_id'])->where('date', $validated['date'])->first();

            if(!$attendance){
                return response()->json([
                    'isSuccess' => false,
                    'message' => 'Terjadi kesalahan tidak berhasil menemukan data checkIn absensi'
                ]);
            }
            $attendance->update([
                'time_out' => $validated['time_out'],
                'location_out' => $validated['location_out'],
            ]);

            return response()->json([
                'isSuccess' => true,
                'message' => 'Berhasil melakukan absensi keluar'
            ], 201);

        } catch (\Exception $e) {
            //untuk $e
            // Log::error($e);
            //Message tidak boleh langsung $e karena message ini dikirim ke user pada tahap develop boleh
            return response()->json([
                'isSuccess' => false,
                'message' => $e
            ], 500);
        }
    }

    public function checkAttendance(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'date' => 'required|string',
        ]);
        try{
            $attendance = Attendance::where('user_id', $validated['user_id'])->where('date', $validated['date'])->first();
            if($attendance){
                return response()->json([
                    'isSuccess'=> true,
                ]);
            } else{
                return response()->json([
                    'isSuccess'=> false,
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'isSuccess'=> false,
                'message'=> $e
            ]);
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
