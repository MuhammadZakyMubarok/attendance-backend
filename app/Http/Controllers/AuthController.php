<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $employee = Employee::query()
            ->where('email', $validated['email'])
            ->first();

            if (! $employee || ! Hash::check($validated['password'], $employee->password)) {
                return response()->json([
                    'message' => 'Email atau password salah.',
                ]);
            }

            if ($employee->tokens()->exists()) {
                return response()->json([
                    'message' => 'Akun ini sudah login di perangkat lain. Silakan logout terlebih dahulu.',
                ], 409);
            }

            $token = $employee
                ->createToken($validated['device_name'] ?? 'mobile')
                ->plainTextToken;

            return response()->json([
                'token' => $token,
                ...$employee->toArray(),
            ], 200);
        } catch(QueryException $e){
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    public function checkSessionToken(Request $request)
    {
        /** @var \App\Models\Employee|null $employee */
        $employee = $request->user();

        if (! $employee) {
            return response()->json([
                'message' => 'Token tidak valid',
            ], 401);
        }

        try{
            $currentToken = $employee->currentAccessToken();

            if ($currentToken && isset($currentToken->id)) {
                $employee->tokens()
                    ->whereKey($currentToken->id)
                    ->update([
                        'last_used_at' => now(),
                    ]);
            }

            return response()->json($employee, 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\Employee $employee */
        $employee = $request->user();

        if (! $employee) {
            return response()->json([
                'message' => 'Token tidak valid',
            ], 401);
        }

        try{
            $currentToken = $employee->currentAccessToken();

            if ($currentToken) {
                $employee->tokens()->whereKey($currentToken->id)->delete();
            }

            return response()->json([
                'message' => 'berhasil logout',
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
            ], 500);
        }
    }
}