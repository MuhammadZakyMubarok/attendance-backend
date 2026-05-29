<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Leaving;
use App\Models\EmployeePermit;
use Illuminate\Database\QueryException;


class RequestController extends Controller
{
    public function fetchRequestData(Request $request){
        try {
            $user = $request->user();

            $attendance = Attendance::query()->where('user_id', '=', $user->id)->get();
            $leaving = Leaving::query()->where('user_id', '=', $user->id)->get();
            $employeePermit = EmployeePermit::query()->where('user_id', '=', $user->id)->get();

            return response()->json([
                'attendance' => $attendance,
                'leaving' => $leaving,
                'employeePermit' => $employeePermit
            ], 200);

        } catch(QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
    }
}
