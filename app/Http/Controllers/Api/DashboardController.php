<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Display tranding product

        $search = $request->search_name;

        $query = Product::query()->with('category');
        if ($search) {
            $query
                ->where('product_name', 'like', '%' . $search . '%')
                ->orWhere('brand_name', 'like', '%' . $search . '%')
                // ->orWhere('category.name', 'like', '%' . $search . '%');
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }

        $treanding_product_list = $query
            ->where('status', 'published')
            ->with('category', 'product_image')
            ->orderBy('view_count', 'desc')
            ->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $treanding_product_list
        ]);
    }

    public function user_overview(Request $request)
    {
        if ($request->filled('year')) {
            $year = $request->year;
        } else {
            $year = date('Y');  // Set the year to the current year if request data is null
        }

        $monthlyData = User::select(
            DB::raw('(count(email)) as count'),
            DB::raw('MONTHNAME(created_at) as month_name'),
            DB::raw('MONTH(created_at) as month_number')
        )
            ->whereYear('created_at', $year)
            ->groupBy('month_name', 'month_number')
            ->orderBy('month_number')
            ->get()
            ->toArray();

        // Calculate total count of users across all months
        $totalCount = collect($monthlyData)->sum('count');

        // Calculate the average count of users per month
        $numMonths = count($monthlyData);
        $averageCount = $numMonths > 0 ? $totalCount / $numMonths : 0;

        return response()->json([
            'status' => 'success',
            'monthly_progress' => $averageCount,
            'monthly_total' => $numMonths,
            'monthly_visitor' => $monthlyData,
        ]);
    }

    /**
     * Display counting data
     */
    public function counting_dashboar()
    {
        $total_product = Product::count();
        $total_brand = Product::distinct('brand_name')->count();
        $total_user = User::count();
        return response()->json([
            'status' => 'success',
            'total_product' => $total_product,
            'total_brand' => $total_brand,
            'total_user' => $total_user
        ]);
    }

    /**
     * Show the notification
     */
    public function showAdminNotifications()
    {
        // Retrieve notifications where the type is AdminNotification
        $adminNotifications = DB::table('notifications')
            ->where('type', 'App\Notifications\AdminNotification')
            ->paginate(10);

        $decodedNotifications = $adminNotifications->map(function ($notification) {
            $notification->data = json_decode($notification->data);
            return $notification;
        });

        // You can then use $adminNotifications in your view or return it as JSON
        return Response::json([
            'admin_notifications' => $decodedNotifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
