<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test_setting;
use Illuminate\Http\Request;

class TestSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return [
            'status' => 'success',
            'data' => Test_setting::first(),
        ];
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
        //
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
        $validated = $request->validate([
            'auth_token' => ['nullable', 'string', 'min:10'],
            'base_url' => ['nullable', 'string', 'min:10'],
            'test_user_email' => ['nullable', 'email:rfc,dns'],
            'test_user_password' => ['nullable', 'string', 'min:8'],
        ]);
        Test_setting::first() ? Test_setting::first()->update($validated) : Test_setting::create($validated);
        // $test_setting->update($validated);
        return [
            'status' => 'success',
            'message' => 'test settings updated successfully',
            'data' => Test_setting::first(),
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
