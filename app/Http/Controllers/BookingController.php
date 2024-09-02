<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $rules = [
        'user_d' => 'required|exists:users,id',
        'tour_id' => 'required|exists:tours,id',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::user()->role == 'user') {
            $data = Booking::where('user_id', Auth::user()->id)->paginate(10);
        } else {
            $data = Booking::paginate(10);
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $booking = Booking::create($this->validateRequestData($request->all(), $this->rules));

        $ticket_no = now()->format('Y-m-d H:i:s').bin2hex(random_bytes(10));

        $booking->tickets()->create(['ticket_no' => $ticket_no]);

        return response()->json([
            'data' => $ticket_no,
            'message' => 'Booking created successfully'
        ], 201);
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
    public function update(Request $request, Booking $booking) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
