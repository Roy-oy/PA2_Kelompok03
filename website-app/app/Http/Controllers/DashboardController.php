<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the puskesmas dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ensure only authenticated users can access the dashboard
        $this->middleware('auth');

        // Get metrics data
        $metrics = $this->getMetrics();
        
        return view('dashboard.index', compact('metrics'));
    }

    /**
     * Display the user profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        // Pass the authenticated user to the profile view
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }
    
    /**
     * Get dashboard metrics based on available data.
     *
     * @return array
     */
    private function getMetrics()
    {
        $now = Carbon::now();
        
        // User metrics
        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', $now->copy()->startOfWeek())->count();
        $newUsersThisMonth = User::where('created_at', '>=', $now->copy()->startOfMonth())->count();
        $activeUsersToday = User::whereDate('last_login_at', $now->toDateString())->count();

        // User growth chart data (last 7 days)
        $userGrowth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $count = User::whereDate('created_at', $date->toDateString())->count();
            $userGrowth[] = [
                'date' => $date->format('d M'),
                'count' => $count
            ];
        }

        // Note: Patient, appointment, department, medication, and performance metrics are not included
        // as their corresponding models/tables are not provided. These should be added once the
        // relevant migrations and models are defined.

        return [
            'total_users' => $totalUsers,
            'new_users_this_week' => $newUsersThisWeek,
            'new_users_this_month' => $newUsersThisMonth,
            'active_users_today' => $activeUsersToday,
            'user_growth' => $userGrowth,
            'counts' => [
                'users' => $totalUsers,
                // Add other counts when models are available (e.g., patients)
            ],
        ];
    }
}