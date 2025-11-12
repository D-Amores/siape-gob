<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceReport;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{
    public function index()
    {
        return view('user.user_reports');
    }

    public function userReportsApi(Request $request)
    {
        $personnelId = Auth::user()->personnel_id;

        $reports = MaintenanceReport::where('reported_by', $personnelId)
            ->with([
                'asset', 
                'status',
                'logs.personnel', 
                'reporter'
            ])
            ->orderBy('reported_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }
}
