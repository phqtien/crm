<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        return view('/home');
    }

    public function statistify(Request $request)
    {
        $statistics = $request->input('statistics', 'month');
        $time = $request->input('time', date('Y'));

        switch ($statistics) {
            case 'month':
                $months = collect(range(1, 12))->map(function ($month) {
                    return ['month' => $month, 'total_revenue' => 0];
                });

                $report = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_revenue')
                    ->whereYear('created_at', $time)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                $report = $months->map(function ($month) use ($report) {
                    $data = $report->firstWhere('month', $month['month']);
                    $month['total_revenue'] = $data ? $data->total_revenue : 0;
                    return (object) $month;
                });
                break;

            case 'quarter':
                $quarters = collect(range(1, 4))->map(function ($quarter) {
                    return ['quarter' => $quarter, 'total_revenue' => 0];
                });

                $report = Order::selectRaw('QUARTER(created_at) as quarter, SUM(total_amount) as total_revenue')
                    ->whereYear('created_at', $time)
                    ->groupBy('quarter')
                    ->orderBy('quarter')
                    ->get();

                // Cập nhật doanh thu vào mảng quý
                $report = $quarters->map(function ($quarter) use ($report) {
                    $data = $report->firstWhere('quarter', $quarter['quarter']);
                    $quarter['total_revenue'] = $data ? $data->total_revenue : 0;
                    return (object) $quarter;
                });
                break;

            case 'year':
                $report = Order::selectRaw('YEAR(created_at) as year, SUM(total_amount) as total_revenue')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                break;

            default:
                return response()->json(['error' => 'Invalid statistics type'], 400);
        }

        return response()->json(['report' => $report]);
    }
}
