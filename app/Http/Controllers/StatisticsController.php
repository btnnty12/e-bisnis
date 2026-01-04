<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * =========================
     * STATISTIK PER EVENT
     * SUMBER DATA: INTERACTIONS (MOOD)
     * =========================
     */
    public function perEvent(Request $request)
    {
        $eventId = $request->query('event_id');

        // ===== DETAIL 1 EVENT =====
        if ($eventId) {
            $event = Event::findOrFail($eventId);

            $totalInteractions = Interaction::where('event_id', $eventId)->count();

            $uniqueUsers = Interaction::where('event_id', $eventId)
                ->whereNotNull('user_id')
                ->distinct()
                ->count('user_id');

            $byMood = Interaction::where('event_id', $eventId)
                ->select(
                    'mood_id',
                    DB::raw('COUNT(*) as total_interactions'),
                    DB::raw('COUNT(DISTINCT user_id) as unique_users')
                )
                ->with('mood:id,mood_name')
                ->groupBy('mood_id')
                ->get()
                ->map(fn ($i) => [
                    'mood_name'           => $i->mood->mood_name ?? 'Unknown',
                    'total_interactions' => $i->total_interactions,
                    'unique_users'       => $i->unique_users,
                ]);

            return response()->json([
                'event' => [
                    'id'         => $event->id,
                    'event_name' => $event->event_name,
                ],
                'total_interactions' => $totalInteractions,
                'unique_users'       => $uniqueUsers,
                'by_mood'            => $byMood,
            ]);
        }

        // ===== LIST SEMUA EVENT =====
        $events = Event::orderBy('id')->get();

        $eventStats = $events->map(function ($e) {
            $totalInteractions = Interaction::where('event_id', $e->id)->count();

            $uniqueUsers = Interaction::where('event_id', $e->id)
                ->whereNotNull('user_id')
                ->distinct()
                ->count('user_id');

            $byMood = Interaction::where('event_id', $e->id)
                ->select(
                    'mood_id',
                    DB::raw('COUNT(*) as total_interactions'),
                    DB::raw('COUNT(DISTINCT user_id) as unique_users')
                )
                ->with('mood:id,mood_name')
                ->groupBy('mood_id')
                ->get()
                ->map(fn ($i) => [
                    'mood_name'           => $i->mood->mood_name ?? 'Unknown',
                    'total_interactions' => $i->total_interactions,
                    'unique_users'       => $i->unique_users,
                ]);

            return [
                'event' => [
                    'id'         => $e->id,
                    'event_name' => $e->event_name,
                ],
                'total_interactions' => $totalInteractions,
                'unique_users'       => $uniqueUsers,
                'by_mood'            => $byMood,
            ];
        });

        return response()->json(['events' => $eventStats]);
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

    $eventStats = $events->map(function ($e) {
        $totalInteractions = Interaction::where('event_id', $e->id)->count();

        $uniqueUsers = Interaction::where('event_id', $e->id)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $byMood = Interaction::where('event_id', $e->id)
            ->select(
                'mood_id',
                DB::raw('COUNT(*) as total_interactions'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users')
            )
            ->with('mood:id,mood_name')
            ->groupBy('mood_id')
            ->get()
            ->map(fn ($i) => [
                'mood_name'           => $i->mood->mood_name ?? 'Unknown',
                'total_interactions' => $i->total_interactions,
                'unique_users'       => $i->unique_users,
            ]);

        return [
            'event'              => $e,
            'total_interactions' => $totalInteractions,
            'unique_users'       => $uniqueUsers,
            'by_mood'            => $byMood,
        ];
    });

    // ✅ FIX DI SINI
    return view('statistics.index', compact('events', 'eventStats'));
}

    /**
     * =========================
     * BEFORE & AFTER STATISTIK (BERDASARKAN MOOD)
     * =========================
     */
    public function beforeAfter(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $date = $request->date
            ? Carbon::parse($request->date)->startOfDay()
            : now()->startOfDay();

        $before = $this->calculateBeforeAfter(null, $date);
        $after  = $this->calculateBeforeAfter($date, null);

        $change = [
            'interactions_change' => $this->calculateChange(
                $before['total_interactions'],
                $after['total_interactions']
            ),
            'users_change' => $this->calculateChange(
                $before['unique_users'],
                $after['unique_users']
            ),
        ];

        return response()->json([
            'date'   => $date->format('Y-m-d'),
            'before' => $before,
            'after'  => $after,
            'change' => $change,
        ]);
    }

    /**
     * =========================
     * BEFORE & AFTER MOOD (API ALIAS)
     * =========================
     */
    public function beforeAfterMood(Request $request)
    {
        // Alias to beforeAfter as it already returns mood data
        return $this->beforeAfter($request);
    }

    /**
     * =========================
     * HELPER: HITUNG DATA (MOOD-BASED)
     * =========================
     */
    private function calculateBeforeAfter($start = null, $end = null)
    {
        $query = Interaction::query();

        if ($start) {
            $query->where('created_at', '>=', $start);
        }

        if ($end) {
            $query->where('created_at', '<', $end);
        }

        $totalInteractions = $query->count();

        $uniqueUsers = (clone $query)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $byMood = (clone $query)
            ->select(
                'mood_id',
                DB::raw('COUNT(*) as total_interactions'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users')
            )
            ->with('mood:id,mood_name')
            ->groupBy('mood_id')
            ->get()
            ->map(fn ($i) => [
                'mood_name'           => $i->mood->mood_name ?? 'Unknown',
                'total_interactions' => $i->total_interactions,
                'unique_users'       => $i->unique_users,
            ]);

        return [
            'total_interactions' => $totalInteractions,
            'unique_users'       => $uniqueUsers,
            'by_mood'            => $byMood,
        ];
    }

    /**
     * =========================
     * HELPER: HITUNG PERUBAHAN %
     * =========================
     */
    private function calculateChange($before, $after)
    {
        if ($before == 0) {
            return $after > 0 ? 100 : 0;
        }

        return round((($after - $before) / $before) * 100, 2);
    }
}