<?php

namespace App\Http\Controllers\API;

use App\Helpers\BengaliDateHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DateTimeController extends Controller
{
    /**
     * Get current date and time
     * @return: ২২ ডিসেম্বর, ২০২৫, ৮ পৌষ ১৪৩২
     */
    public function index()
    {
        $now = Carbon::now('Asia/Dhaka');

        return response()->json([
            'success' => true,
            'data' =>
                BengaliDateHelper::formatEnglishToBnDate($now->toDateTimeString()). ', ' .
                BengaliDateHelper::formatBanglaDateMonthYear($now->toDateTimeString())
        ]);
    }
}
