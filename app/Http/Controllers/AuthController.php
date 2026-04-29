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
                ], 401);
            }

            $deviceName = $validated['device_name'] ?? 'mobile';

            // Jika masih ada token aktif dari device lain, jangan izinkan login
            $hasTokenOnOtherDevice = $employee->tokens()
                ->where('name', '!=', $deviceName)
                ->exists();

            if ($hasTokenOnOtherDevice) {
                return response()->json([
                    'message' => 'Akun ini sudah login di perangkat lain. Silakan logout terlebih dahulu.',
                ], 409);
            }

            // Jika token lama dari device yang sama ada, overwrite dengan cara hapus token lama
            $employee->tokens()
                ->where('name', $deviceName)
                ->delete();

            $token = $employee
                ->createToken($deviceName)
                ->plainTextToken;

            return response()->json([
                'token' => $token,
                ...$employee->toArray(),
            ], 200);

        } catch (QueryException $e) {
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