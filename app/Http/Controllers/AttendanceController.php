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
                'message' => 'Berhasil melakukan absensi masuk'
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
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
                    'message' => 'Terjadi kesalahan tidak berhasil menemukan data checkIn absensi'
                ], 401);
            }
            $attendance->update([
                'time_out' => $validated['time_out'],
                'location_out' => $validated['location_out'],
            ]);

            return response()->json([
                'message' => 'Berhasil melakukan absensi keluar'
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
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
                    'message'=> 'Anda belum melakukan absen keluar pada hari ini',
                ], 200);
            } else{
                return response()->json([
                    'message'=> 'Anda belum melakukan absen masuk pada hari ini',
                ], 200);
            }
        } catch(QueryException $e){
            return response()->json([
                'message'=> $e->errorInfo
            ], 500);
        };
    }

    public function fetchData(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $authUser = $request->user();

            if ((int) $authUser->id !== (int) $validated['user_id']) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke data ini',
                ], 403);
            }

            $attendance = Attendance::where('user_id', $validated['user_id'])->get();

            if ($attendance->isEmpty()) {
                return response()->json([
                    [],
                ], 404);
            }

            return response()->json([
                $attendance,
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
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
