<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // Store a new address
    public function store(Request $request)
    {
        $request->validate([
            'marker' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'name' => 'required|string',
            'post_code' => 'required|string',
        ]);

        $address = Address::create([
            'user_id' => auth()->id(),
            'marker' => $request->marker,
            'address' => $request->address,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'name' => $request->name,
            'post_code' => $request->post_code,
        ]);

        return response()->json([
            'message' => 'Address added successfully.',
            'address' => $address,
        ], 201);
    }

    // Retrieve all addresses for the authenticated user
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->get();

        return response()->json([
            'message' => 'Addresses retrieved successfully.',
            'addresses' => $addresses,
        ], 200);
    }
}
