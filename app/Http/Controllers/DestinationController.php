<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Destination::paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [];


        Destination::create($this->validateRequestData($request->all(), $rules));

        return response()->json(['message' => 'Destination created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return response()->json(['data' => $destination], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $rules = [];


        $destination->update($this->validateRequestData($request->all(), $rules));

        return response()->json(['message' => 'Destination updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();

        return response()->json(['message' => 'Destination deleted successfully'], 200);
    }
}
