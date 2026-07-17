<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomesticTravel;
use App\Models\TravelExpenses;
use App\Models\TravelPurposes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class DomesticTravelController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'created_at' => 'required|string',
            'pm_id' => 'required|int',
            'approved_by' => 'required|int', 

            'purposes'   => 'required|array|min:1',
            'purposes.*.purpose' => 'required|string',

            'expenses'   => 'required|array|min:1',
            'expenses.*.needs'    => 'required|string',
            'expenses.*.days'     => 'required|numeric',
            'expenses.*.unit'     => 'required|string',
            'expenses.*.cost'     => 'required|numeric',
            'expenses.*.remark'   => 'nullable|string', // nullable jika boleh kosong
            'expenses.*.quantity' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $user = $request->user();

            $validated['applicant_id'] = $user->id;

            $travel = DomesticTravel::create($validated);
            
            $purposesData = [];
            foreach ($validated['purposes'] as $item) {
                $purposesData = [
                    'travel_id'  => $travel->id, // Ambil ID dari data travel yang baru dibuat[cite: 4]
                    'purpose'    => $item['purpose'],
                ];
            }
            TravelPurposes::insert($purposesData);

            $expensesData = [];
            foreach ($validated['expenses'] as $expense) {
                $expensesData = [
                    'travel_id'  => $travel->id, //[cite: 3]
                    'needs'      => $expense['needs'], //[cite: 3]
                    'days'       => $expense['days'], //[cite: 3]
                    'unit'       => $expense['unit'], //[cite: 3]
                    'cost'       => $expense['cost'], //[cite: 3]
                    'remark'     => $expense['remark'] ?? null, //[cite: 3]
                    'quantity'   => $expense['quantity'], //[cite: 3]
                ];
            }
            TravelExpenses::insert($expensesData);

            DB::commit();

            return response()->json([
                'message' => 'Berhasil submit Domestic Traveling'
            ],200);
        } catch (QueryException $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->errorInfo,
            ],500);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ],500);
        }
    }
}
