<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Quotes;
use App\Models\Userseps;

class SqlLaravel extends Controller
{
    public function getUsersEps()
    {
        $userseps = Userseps::with('healthcenter')->orderBy('name', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved and sorted by name',
            'users' => $userseps,
            'code' => 200
        ], 200);
    }


    public function getQuotesUser()
    {
        $quotes = Quotes::with('userseps')->orderBy('date', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Quotes retrieved and sorted by date',
            'quotes' => $quotes,
            'code' => 200
        ], 200);
    }
    public function getDoctorsSpecialty()
    {
        $doctors = Doctors::with('specialtydoctors')->orderBy('name', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Doctors retrieved and sorted by name',
            'doctors' => $doctors,
            'code' => 200
        ], 200);
    }
    public function getDoctorsQuotes()
    {
        $quotes = Quotes::with('doctors')->orderBy('date', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Quotes retrieved and sorted by date',
            'quotes' => $quotes,
            'code' => 200
        ], 200);
    }
    public function getdActivedDctors()
    {
        $doctoresActivos = Doctors::where('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Active doctors retrieved',
            'doctors' => $doctoresActivos,
            'code' => 200
        ], 200);
    }
}
