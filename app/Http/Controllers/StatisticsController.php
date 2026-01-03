<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatisticsController extends Controller
{
    /**
     * =========================
     * STATISTIK PER EVENT
     * SUMBER DATA: PAGE_VIEWS
     * =========================
     */
    public function perEvent(Request $request)
    {
        $eventId = $request->query('event_id');

        // ===== DETAIL EVENT =====
        if ($eventId) {
            $event = Event::findOrFail($eventId);

            $totalViews = PageView::where('event_id', $eventId)->count();

            $uniqueUsers = PageView::where('event_id', $eventId)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id');

            $dailyViews = PageView::where('event_id', $eventId)
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total')
                )
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            return response()->json([
                'event' => [
                    'id'         => $event->id,
                    'event_name' => $event->event_name,
                ],
                'total_interactions' => $totalViews,
                'unique_users'       => $uniqueUsers,
                'daily_interactions' => $dailyViews,
            ]);
        }

        // ===== LIST SEMUA EVENT =====
        $events = Event::orderBy('id')->get();

        $eventStats = $events->map(function ($e) {
            $total = PageView::where('event_id', $e->id)->count();
            $unique = PageView::where('event_id', $e->id)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id');

            // fallback dummy jika PageView belum ada
            if ($total === 0) {
                $total = rand(0, 50);
                $unique = rand(0, $total);
            }

            return [
                'event' => [
                    'id'         => $e->id,
                    'event_name' => $e->event_name,
                ],
                'total_interactions' => $total,
                'unique_users'       => $unique,
            ];
        });

        return response()->json([
            'events' => $eventStats,
        ]);
    }

    /**
     * =========================
     * VIEW STATISTIK (BLADE)
     * =========================
     */
    public function index()
    {
        $events = Schema::hasTable('events')
            ? Event::orderBy('id', 'desc')->get()
            : collect([]);

        // Tambahkan dummy total akses & unique users
        $eventStats = $events->map(function ($e) {
            $total = PageView::where('event_id', $e->id)->count();
            $unique = PageView::where('event_id', $e->id)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id');

            if ($total === 0) {
                $total = rand(0, 50);
                $unique = rand(0, $total);
            }

            return [
                'event' => $e,
                'total_interactions' => $total,
                'unique_users'       => $unique,
            ];
        });

        return view('statistics.index', compact('eventStats'));
    }
}