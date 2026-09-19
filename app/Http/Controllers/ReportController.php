<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(string $focus = 'all')
    {
        $year = now()->year;

        // Revenue by month
        $revenueByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueByMonth[] = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $m)
                ->where('status','pagado')
                ->sum('amount');
        }

        // Members by month (new)
        $membersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $membersByMonth[] = Member::whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Attendance by day of week
        $attendanceByDay = Attendance::selectRaw('DAYOFWEEK(check_in) as day, COUNT(*) as cnt')
            ->whereYear('check_in', $year)
            ->groupBy('day')
            ->pluck('cnt','day');

        $days = ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'];
        $attendanceData = [];
        for ($i = 1; $i <= 7; $i++) {
            $attendanceData[] = $attendanceByDay[$i] ?? 0;
        }

        // Payment methods distribution
        $paymentMethods = Payment::selectRaw('payment_method, COUNT(*) as cnt, SUM(amount) as total')
            ->where('status','pagado')
            ->groupBy('payment_method')
            ->get();

        // Top stats
        $stats = [
            'total_revenue'  => Payment::whereYear('payment_date', $year)->where('status','pagado')->sum('amount'),
            'total_payments' => Payment::whereYear('payment_date', $year)->where('status','pagado')->count(),
            'avg_payment'    => Payment::whereYear('payment_date', $year)->where('status','pagado')->avg('amount'),
            'new_members'    => Member::whereYear('created_at', $year)->count(),
        ];

        return view('reports.index', compact(
            'revenueByMonth','membersByMonth','attendanceData','days','paymentMethods','stats','year','focus'
        ));
    }

    public function revenue()   { return $this->index('revenue'); }
    public function members()   { return $this->index('members'); }
    public function attendance(){ return $this->index('attendance'); }
}
