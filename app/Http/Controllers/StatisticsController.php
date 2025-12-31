<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Event;
use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * =========================
     * BEFORE & AFTER (REAL TIME PER HARI)
     * =========================
     */
    public function beforeAfter(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date)->startOfDay();
        $beforeDate = (clone $date)->subDay();

        $before = $this->calculateBeforeAfter($beforeDate);
        $after = $this->calculateBeforeAfter($date);

        $change = [
            'interactions_change' => $this->calculateChange($before['total_interactions'], $after['total_interactions']),
            'users_change' => $this->calculateChange($before['unique_users'], $after['unique_users']),
        ];

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'before_date' => $beforeDate->format('Y-m-d'),
            'before' => $before,
            'after' => $after,
            'change' => $change,
        ]);
    }

    private function calculateBeforeAfter($date)
    {
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $totalInteractions = Interaction::whereBetween('created_at', [$start, $end])->count();
        $byMood = Interaction::whereBetween('created_at', [$start, $end])
            ->select('mood_id', DB::raw('count(*) as total'))
            ->with('mood:id,mood_name')
            ->groupBy('mood_id')
            ->get()
            ->map(fn ($i) => [
                'mood_name' => $i->mood->mood_name ?? 'Unknown',
                'total' => $i->total,
            ]);
        $uniqueUsers = Interaction::whereBetween('created_at', [$start, $end])->distinct('user_id')->count('user_id');

        return [
            'total_interactions' => $totalInteractions,
            'by_mood' => $byMood,
            'unique_users' => $uniqueUsers,
        ];
    }

    /**
     * =========================
     * BEFORE & AFTER MOOD (USER_MOODS)
     * =========================
     */
    public function beforeAfterMood(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date)->startOfDay();
        $beforeDate = (clone $date)->subDay();

        $before = $this->calculateMoodBeforeAfter($beforeDate);
        $after = $this->calculateMoodBeforeAfter($date);

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'before_date' => $beforeDate->format('Y-m-d'),
            'before' => $before,
            'after' => $after,
        ]);
    }

    private function calculateMoodBeforeAfter($date)
    {
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $moodStats = DB::table('user_moods as um')
            ->join('moods as m', 'um.mood_id', '=', 'm.id')
            ->select('m.mood_name', DB::raw('count(*) as total'))
            ->whereBetween('um.created_at', [$start, $end])
            ->groupBy('m.mood_name')
            ->get()
            ->keyBy('mood_name');

        return $moodStats->map(fn($item) => $item->total);
    }

    /**
     * =========================
     * STATISTIK PER EVENT
     * =========================
     */
    public function perEvent(Request $request)
    {
        $eventId = $request->query('event_id');

        if ($eventId) {
            $event = Event::withCount('interactions')->findOrFail($eventId);

            return response()->json([
                'event' => [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'description' => $event->description,
                ],
                'total_interactions' => $event->interactions_count,
                'by_mood' => Interaction::where('event_id', $eventId)
                    ->select('mood_id', DB::raw('count(*) as total'))
                    ->with('mood:id,mood_name')
                    ->groupBy('mood_id')
                    ->get()
                    ->map(fn ($i) => [
                        'mood_name' => $i->mood->mood_name ?? 'Unknown',
                        'total' => $i->total,
                    ]),
                'unique_users' => Interaction::where('event_id', $eventId)
                    ->distinct('user_id')
                    ->count('user_id'),
                'daily_interactions' => Interaction::where('event_id', $eventId)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date')
                    ->get(),
            ]);
        }

        $events = Event::withCount('interactions')->get();

        return response()->json([
            'events' => $events->map(fn ($e) => [
                'event' => [
                    'id' => $e->id,
                    'event_name' => $e->event_name,
                ],
                'total_interactions' => $e->interactions_count,
            ])
        ]);
    }

    /**
     * =========================
     * VIEW STATISTIK
     * =========================
     */
    public function index()
    {
        $events = Schema::hasTable('events')
            ? Event::orderBy('id', 'desc')->get()
            : collect([]);

        return view('statistics.index', compact('events'));
    }

    /**
     * =========================
     * BEFORE & AFTER (WEB TANPA AUTH)
     * =========================
     */
    public function beforeAfterWeb(Request $request)
    {
        return $this->beforeAfter($request);
    }

    /**
     * =========================
     * PER EVENT (WEB TANPA AUTH)
     * =========================
     */
    public function perEventWeb(Request $request)
    {
        return $this->perEvent($request);
    }

    /**
     * =========================
     * HELPER PERSENTASE
     * =========================
     */
    private function calculateChange($before, $after)
    {
        if ($before == 0) return $after > 0 ? 100 : 0;
        return round((($after - $before) / $before) * 100, 2);
    }
}