<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
// use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $employee = Employee::query()
            ->where('email', $validated['email'])
            ->first();

        if (! $employee || ! Hash::check($validated['password'], $employee->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $employee
            ->createToken($validated['device_name'] ?? 'mobile')
            ->plainTextToken;

        return response()->json([
            'message' => 'berhasil melakukan login',
            'token' => $token,
            'name' => $employee->name,
            'phone' => $employee->phone,
            'role' => $employee->role,
            'position' => $employee->position,
            'department' => $employee->department,
            'image_url' => $employee->image_url,
            'unique_id' => $employee->unique_id,
        ], 200);
    }

    public function me(Request $request) 
    {
        /** @var \App\Models\Employee $employee */
        $employee = $request->user();

        return response()->json([
            'message' => 'data employee berhasil diambil',
            'name' => $employee->name,
            'phone' => $employee->phone,
            'role' => $employee->role,
            'position' => $employee->position,
            'department' => $employee->department,
            'image_url' => $employee->image_url,
            'unique_id' => $employee->unique_id,
            'email' => $employee->email,
        ], 200);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\Employee $employee */
        $employee = $request->user();

        $currentToken = $employee->currentAccessToken();

        if ($currentToken) {
            $employee->tokens()->whereKey($currentToken->id)->delete();
        }

        return response()->json([
            'message' => 'berhasil logout',
        ], 200);
    }
}