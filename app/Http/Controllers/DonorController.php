<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonorController extends Controller
{
    // Show blade view
    public function index()
    {
        return view('donors.index');
    }

    // Store donor (AJAX)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'blood_group' => 'required|string|max:5',
            'state'       => 'required|string|max:255',
            'city'        => 'required|string|max:255',
            'mobile'      => 'required|string|max:20',
            'address'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $donor = Donor::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'donor'  => $donor
        ]);
    }

    // Search donors (AJAX)
    public function search(Request $request)
    {
        $query = Donor::query();

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $results = $query
            ->orderBy('created_at', 'desc')
            ->limit(500)
            ->get(['name', 'blood_group', 'city', 'state', 'mobile']);

        return response()->json([
            'status'  => 'success',
            'results' => $results
        ]);
    }
}
