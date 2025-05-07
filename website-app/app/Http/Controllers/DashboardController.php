<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Pasien;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard puskesmas
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get metrics data
        $metrics = $this->getMetrics();
        
        return view('dashboard.index', compact('metrics'));
    }

    /**
     * Menampilkan profil pengguna
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('dashboard.profile');
    }
    
    /**
     * Get dashboard metrics
     * 
     * @return array
     */
    private function getMetrics()
    {
        $now = Carbon::now();
        
        // User metrics
        $totalUsers = User::count();
        $totalDoctors = User::where('user_type', 'doctor')->count();
        $totalNurses = User::where('user_type', 'nurse')->count();
        $totalStaff = User::where('user_type', 'staff')->count();
        
        // Activity metrics
        $newUsersThisWeek = User::where('created_at', '>=', $now->copy()->startOfWeek())->count();
        $newUsersThisMonth = User::where('created_at', '>=', $now->copy()->startOfMonth())->count();
        
        // Active users
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
        
        // Staff distribution by role
        $staffByRole = [
            'doctors' => $totalDoctors,
            'nurses' => $totalNurses,
            'staff' => $totalStaff,
            'admin' => User::where('user_type', 'admin')->count(),
        ];
        
        // Patient metrics (simulated data for now)
        $patientMetrics = [
            'total_patients' => 1234,
            'new_patients_today' => 15,
            'new_patients_week' => 87,
            'new_patients_month' => 350,
            'returning_patients_today' => 30,
            'returning_patients_week' => 120,
        ];
        
        // Appointment metrics (simulated data)
        $appointmentMetrics = [
            'today' => 45,
            'scheduled_today' => 35,
            'completed_today' => 20,
            'cancelled_today' => 5,
            'this_week' => 240,
            'next_week' => 180,
        ];
        
        // Department visit distribution (simulated data)
        $departmentVisits = [
            ['name' => 'Umum', 'count' => 45, 'color' => 'rgba(59, 130, 246, 0.7)'],
            ['name' => 'Gigi', 'count' => 23, 'color' => 'rgba(139, 92, 246, 0.7)'],
            ['name' => 'Anak', 'count' => 28, 'color' => 'rgba(245, 158, 11, 0.7)'],
            ['name' => 'KIA', 'count' => 19, 'color' => 'rgba(16, 185, 129, 0.7)'],
            ['name' => 'Mata', 'count' => 17, 'color' => 'rgba(236, 72, 153, 0.7)'],
        ];
        
        // Medication inventory (simulated data)
        $medicationMetrics = [
            'total_medications' => 523,
            'low_stock' => 15,
            'out_of_stock' => 3,
            'expiring_soon' => 8,
        ];
        
        // Daily patient visits (simulated data for chart)
        $dailyPatientVisits = [
            ['hour' => '08:00', 'count' => 8],
            ['hour' => '09:00', 'count' => 14],
            ['hour' => '10:00', 'count' => 19],
            ['hour' => '11:00', 'count' => 15],
            ['hour' => '12:00', 'count' => 9],
            ['hour' => '13:00', 'count' => 7],
            ['hour' => '14:00', 'count' => 12],
            ['hour' => '15:00', 'count' => 10],
            ['hour' => '16:00', 'count' => 6],
        ];
        
        // Performance metrics (simulated data)
        $performanceMetrics = [
            'avg_wait_time' => 15, // in minutes
            'avg_consultation_time' => 20, // in minutes
            'patient_satisfaction' => 4.7, // out of 5
            'staff_efficiency' => 87, // percentage
        ];
        
        // Monthly patients (simulated data for 6 months)
        $monthlyPatients = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthlyPatients[] = [
                'month' => $month->format('M'),
                'count' => rand(800, 1300) // Random count between 800-1300
            ];
        }
        
        return [
            'total_users' => $totalUsers,
            'total_doctors' => $totalDoctors,
            'total_nurses' => $totalNurses,
            'total_staff' => $totalStaff,
            'new_users_this_week' => $newUsersThisWeek,
            'new_users_this_month' => $newUsersThisMonth,
            'active_users_today' => $activeUsersToday,
            'user_growth' => $userGrowth,
            'staff_by_role' => $staffByRole,
            'patient_metrics' => $patientMetrics,
            'appointment_metrics' => $appointmentMetrics,
            'department_visits' => $departmentVisits,
            'medication_metrics' => $medicationMetrics,
            'daily_patient_visits' => $dailyPatientVisits,
            'performance_metrics' => $performanceMetrics,
            'monthly_patients' => $monthlyPatients,
            'counts' => [
                'pasiens' => Pasien::count(),
                'doctors' => $totalDoctors,
                'nurses' => $totalNurses,
                'staff' => $totalStaff,
                'admin' => User::where('user_type', 'admin')->count(),
                'app_users' => User::where('user_type', 'app_user')->count(),
            ],
        ];
    }
} 