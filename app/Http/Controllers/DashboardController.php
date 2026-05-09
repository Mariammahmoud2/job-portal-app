<?php

namespace App\Http\Controllers;

use App\Models\job_vacancie;    
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = job_vacancie::with('company');

         $query->when($request->type, function ($q, $type) {
            return $q->where('type', $type);
        });

         $query->when($request->search, function ($q, $search) {
            return $q->where(function($sub) use ($search) {
                $sub->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function($comp) use ($search) {
                        $comp->where('name', 'like', '%' . $search . '%');
                    });
            });
        });

         $jobs = $query->latest()->paginate(5)->withQueryString();

        return view('dashboard', compact('jobs'));
    }
}