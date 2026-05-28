<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * Tidak memakai middleware auth:sanctum sesuai kebutuhanmu.
     */
    public function index()
    {
        $attendance = Attendance::all();

        return response()->json($attendance, 200);
    }

    /**
     * Store a newly created attendance check-in.
     */
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'date'        => 'required|date_format:Y-m-d',
            'time_in'     => 'required|string',
            'location_in' => 'required|string',
        ]);

        try {
            $authUser = $request->user();

            $existingAttendance = Attendance::query()
                ->where('user_id', '=', $authUser->id)
                ->where('date', '=', $validated['date'])
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'message' => 'Anda sudah melakukan absen masuk pada hari ini',
                ], 409);
            }

            Attendance::create([
                'user_id'     => $authUser->id,
                'unique_id'   => $authUser->unique_id,
                'date'        => $validated['date'],
                'time_in'     => $validated['time_in'],
                'location_in' => $validated['location_in'],
            ]);

            return response()->json([
                'message' => 'Berhasil melakukan absensi masuk',
            ], 201);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    /**
     * Update attendance check-out.
     */
    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'date'         => 'required|date_format:Y-m-d',
            'time_out'     => 'required|string',
            'location_out' => 'required|string',
        ]);

        try {
            $authUser = $request->user();

            $attendance = Attendance::query()
                ->where('user_id', '=', $authUser->id)
                ->where('date', '=', $validated['date'])
                ->first();

            if (!$attendance) {
                return response()->json([
                    'message' => 'Anda belum melakukan absen masuk pada hari ini',
                ], 404);
            }

            if ($attendance->time_out !== null) {
                return response()->json([
                    'message' => 'Anda sudah melakukan absen keluar pada hari ini',
                ], 409);
            }

            $attendance->update([
                'time_out'     => $validated['time_out'],
                'location_out' => $validated['location_out'],
            ]);

            return response()->json([
                'message' => 'Berhasil melakukan absensi keluar',
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    /**
     * Check current user's attendance status.
     */
    public function checkAttendance(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        try {
            $authUser = $request->user();

            $attendance = Attendance::query()
                ->where('user_id', '=', $authUser->id)
                ->where('date', '=', $validated['date'])
                ->first();

            if (!$attendance) {
                return response()->json([
                    'status'  => 'notCheckedIn',
                    'message' => 'Anda belum melakukan absen masuk pada hari ini',
                ], 200);
            }

            if ($attendance->time_out === null) {
                return response()->json([
                    'status'  => 'checkedIn',
                    'message' => 'Anda belum melakukan absen keluar pada hari ini',
                ], 200);
            }

            return response()->json([
                'status'  => 'completed',
                'message' => 'Anda sudah melakukan absen masuk dan keluar pada hari ini',
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    /**
     * Fetch authenticated user's attendance history.
     */
    public function fetchData(Request $request)
    {
        try {
            $authUser = $request->user();

            $attendance = Attendance::query()
                ->where('user_id', '=', $authUser->id)
                ->get();

            return response()->json($attendance, 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    public function todayAttendance(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        try {
            $authUser =  $request->user();

            $attendance = Attendance::query()
                ->where('user_id', '=', $authUser->id)
                ->where('date', '=', $validated['date'])
                ->first();
            
            if ($attendance) {
                return response()->json($attendance, 200);
            } else{
                return response()->json([
                    'message' => 'Tidak berhasil menemukan data Attendance hari ini',
                ], 404);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    public function fetchWeeklyData(Request $request)
    {
        try {
            $authUser = $request->user();
            
            $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

            $attendance = Attendance::query()
                ->where('user_id', '=', $authUser->id)
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->get();

            $formattedData = $attendance->map(function ($item) {
                return [
                    'date'    => $item->date,
                    'timeIn'  => $item->time_in,
                    'timeOut' => $item->time_out,
                ];
            });

            return response()->json($formattedData, 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
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