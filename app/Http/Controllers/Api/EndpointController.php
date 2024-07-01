<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Endpoint;
use App\Models\Test_setting;
use Illuminate\Http\Request;

class EndpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $endpoints = Endpoint::get();

        return [
            'status' => 'success',
            'endpoints count' => $endpoints->count(),
            'data' => $endpoints,
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:180', 'unique:endpoints,name'],
            'link' => ['required', 'string', 'min:10', 'max:180', 'unique:endpoints,link']
        ]);
        $endpoint = Endpoint::create($validated);
        return [
            'status' => 'success',
            'message' => 'endpoint successfully created',
            'data' => $endpoint,
        ];
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
        $request->merge(['id' => $id]);

        $validated = $request->validate([
            'id' => ['required', 'uuid', 'exists:endpoints,id'],
            'name' => ['nullable', 'string', 'min:15', 'max:180'],
            'link' => ['nullable', 'string', 'min:15', 'max:180']
        ]);

        $endpoint = Endpoint::find($id);
        $endpoint->update($validated);
        return [
            'status' => 'success',
            'message' => 'endpoint successfully updated',
            'data' => $endpoint,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $endpoint = Endpoint::findOrFail($id);
        $endpoint->delete();
        return [
            'status' => 'success',
            'message' => "endpoint ($endpoint->name) successfully deleted",
        ];
    }
}
