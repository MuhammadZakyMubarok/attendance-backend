<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Leaving;
use App\Models\EmployeePermit;
use App\Models\Employee;
use Illuminate\Database\QueryException;


class RequestController extends Controller
{
    public function fetchEmployeeRequestData(Request $request){
        try {
            $column = $request->filled('unique_id') ? 'unique_id' : 'user_id';
            $value = $request->filled('unique_id') ? $request->unique_id : $request->user()->id;
            
            $attendance = Attendance::query()->where($column, '=', $value)->get();
            $leaving = Leaving::query()->where($column, '=', $value)->get();
            $employeePermit = EmployeePermit::query()->where($column, '=', $value )->get();
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

    public function fetchAllRequestData(){
        try {
            $attendance = Attendance::query()->get();
            $leaving = Leaving::query()->get();
            $employeePermit = EmployeePermit::query()->get();

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

    public function fetchUniqueId()
    {
        try {
            $staffUniqueId = Employee::query()->where('role', 'staff')->pluck('unique_id');
            $hrdUniqueId = Employee::query()->where('role', 'hrd')->pluck('unique_id');

            return response()->json([
                'staffUniqueId' => $staffUniqueId,
                'hrdUniqueId' => $hrdUniqueId
            ],200);
        } catch(QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
    }
}
