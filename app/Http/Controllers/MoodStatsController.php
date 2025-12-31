<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoodStatsController extends Controller
{
    public function getStats(Request $request)
    {
        $date = $request->query('date'); // ex: 2025-12-30
        if (!$date) {
            return response()->json(['error' => 'Tanggal harus diisi'], 400);
        }

        // --- BEFORE: mood terakhir tiap user sebelum tanggal ---
        $beforeData = DB::table('mood as m1')
            ->select('m1.mood', DB::raw('count(*) as total'))
            ->whereDate('m1.created_at', '<', $date)
            ->whereRaw('m1.created_at = (SELECT MAX(m2.created_at) 
                                        FROM mood m2 
                                        WHERE m2.user_id = m1.user_id 
                                          AND m2.created_at < ?)', [$date])
            ->groupBy('m1.mood')
            ->get()
            ->keyBy('mood');

        // --- AFTER: mood pada tanggal itu ---
        $afterData = DB::table('mood')
            ->select('mood', DB::raw('count(*) as total'))
            ->whereDate('created_at', $date)
            ->groupBy('mood')
            ->get()
            ->keyBy('mood');

        return response()->json([
            'before' => $beforeData->map(fn($item) => $item->total),
            'after'  => $afterData->map(fn($item) => $item->total),
        ]);
    }
}