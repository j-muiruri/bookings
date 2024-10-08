<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    protected $rules = [
        'name' => 'string|required',
        'description' => 'string|required',
        'price' => 'numeric|required',
        'slots' => 'integer|required',
        'destination_id' => 'required|exists:destinations,id',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Tour::paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Tour::create($this->validateRequestData($request->all(), $this->rules));

        return response()->json(['message' => 'Tour created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tour $tour)
    {
        return response()->json(['data' => $tour], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tour $tour)
    {

        $tour->update($this->validateRequestData($request->all(), $this->rules));

        return response()->json(['message' => 'Tour updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();

        return response()->json(['message' => 'Tour deleted successfully'], 200);
    }
}
