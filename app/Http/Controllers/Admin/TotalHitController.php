<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BnCategory;
use App\Models\BnContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalHitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function bnContentTopHitList(Request $request)
    {
        $categories = BnCategory::all();
        $searchCategory = $request->category_id;
        $last7days = $request->days;

        // This is the fastest way to get total hits using raw+laravel mix sql query.
        // 385 ms - best.
        $bnContents = BnContent::query()->with('category', 'subCategory', 'totalHits')
            ->leftJoin('bn_total_hits', 'bn_contents.id', '=', 'bn_total_hits.bn_content_id')
            ->select([
                'bn_contents.id',
                'bn_contents.heading',
                'bn_contents.category_id',
                'bn_contents.sub_category_id',
                'bn_contents.created_at',
                DB::raw('COALESCE(bn_total_hits.total_hits, 0) as hits')
            ])
            // ✅ if last_7_days is true, filter contents from last 7 days
            ->when($last7days, function ($query) use ($last7days) {
                $query->where('bn_contents.created_at', '>=', Carbon::now()->subDays($last7days));
            })
            ->when($searchCategory, function ($query, $searchCategory) {
                $query->where('bn_contents.category_id', $searchCategory);
            })
            ->orderByDesc('hits')
            ->paginate(30)
            ->withQueryString();

        return view('admin.total-hit.index', [
            'bnContents' => $bnContents,
            'categories' => $categories,
        ]);
    }
}
