<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{/**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Booking::paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [];


        Booking::create($this->validateRequestData($request->all(), $rules));

        return response()->json(['message' => 'Booking created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return response()->json(['data' => $booking], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $rules = [];


        $booking->update($this->validateRequestData($request->all(), $rules));

        return response()->json(['message' => 'Booking updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }}
