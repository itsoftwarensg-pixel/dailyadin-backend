<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Election;

class ElectionController extends Controller
{
    public function electionData()
    {
        $dataset = Election::where('name', 'national_election_2026')
            ->where('status',1)
            ->first();

        if(!$dataset){
            return response()->json([
                'status'=>false,
                'message'=>'Dataset not found'
            ],404);
        }

        return response()->json([
            'status'=>true,
            'title'=>$dataset->title,
            'slug'=>$dataset->slug,
            'data'=>json_decode($dataset->data)
        ]);
    }
}
