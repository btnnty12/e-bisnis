<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Event;
use App\Models\Mood;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Statistik sebelum dan sesudah tanggal tertentu
     */
    public function beforeAfter(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);

        // Statistik sebelum tanggal
        $before = [
            'total_interactions' => Interaction::where('created_at', '<', $date)->count(),
            'by_mood' => Interaction::where('created_at', '<', $date)
                ->select('mood_id', DB::raw('count(*) as total'))
                ->with('mood:id,mood_name')
                ->groupBy('mood_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'mood_name' => $item->mood->mood_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'by_menu' => Interaction::where('created_at', '<', $date)
                ->select('menu_id', DB::raw('count(*) as total'))
                ->with('menu:id,menu_name')
                ->groupBy('menu_id')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'menu_name' => $item->menu->menu_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'unique_users' => Interaction::where('created_at', '<', $date)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // Statistik sesudah tanggal
        $after = [
            'total_interactions' => Interaction::where('created_at', '>=', $date)->count(),
            'by_mood' => Interaction::where('created_at', '>=', $date)
                ->select('mood_id', DB::raw('count(*) as total'))
                ->with('mood:id,mood_name')
                ->groupBy('mood_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'mood_name' => $item->mood->mood_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'by_menu' => Interaction::where('created_at', '>=', $date)
                ->select('menu_id', DB::raw('count(*) as total'))
                ->with('menu:id,menu_name')
                ->groupBy('menu_id')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'menu_name' => $item->menu->menu_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'unique_users' => Interaction::where('created_at', '>=', $date)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // Perhitungan perubahan
        $change = [
            'interactions_change' => $this->calculateChange($before['total_interactions'], $after['total_interactions']),
            'users_change' => $this->calculateChange($before['unique_users'], $after['unique_users']),
        ];

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'before' => $before,
            'after' => $after,
            'change' => $change,
        ]);
    }

    /**
     * Statistik per event
     */
    public function perEvent(Request $request)
    {
        $eventId = $request->query('event_id');

        if ($eventId) {
            // Statistik untuk event tertentu
            $event = Event::findOrFail($eventId);
            
            $stats = [
                'event' => [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'description' => $event->description,
                ],
                'total_interactions' => Interaction::where('event_id', $eventId)->count(),
                'by_mood' => Interaction::where('event_id', $eventId)
                    ->select('mood_id', DB::raw('count(*) as total'))
                    ->with('mood:id,mood_name')
                    ->groupBy('mood_id')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'mood_name' => $item->mood->mood_name ?? 'Unknown',
                            'total' => $item->total,
                        ];
                    }),
                'by_menu' => Interaction::where('event_id', $eventId)
                    ->select('menu_id', DB::raw('count(*) as total'))
                    ->with('menu:id,menu_name')
                    ->groupBy('menu_id')
                    ->orderByDesc('total')
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'menu_name' => $item->menu->menu_name ?? 'Unknown',
                            'total' => $item->total,
                        ];
                    }),
                'unique_users' => Interaction::where('event_id', $eventId)
                    ->distinct('user_id')
                    ->count('user_id'),
                'daily_interactions' => Interaction::where('event_id', $eventId)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date')
                    ->get(),
            ];

            return response()->json($stats);
        } else {
            // Statistik untuk semua event
            $events = Event::withCount('interactions')
                ->orderBy('id', 'desc')
                ->get();

            $stats = $events->map(function ($event) {
                return [
                    'event' => [
                        'id' => $event->id,
                        'event_name' => $event->event_name,
                    ],
                    'total_interactions' => $event->interactions_count,
                    'by_mood' => Interaction::where('event_id', $event->id)
                        ->select('mood_id', DB::raw('count(*) as total'))
                        ->with('mood:id,mood_name')
                        ->groupBy('mood_id')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'mood_name' => $item->mood->mood_name ?? 'Unknown',
                                'total' => $item->total,
                            ];
                        }),
                ];
            });

            return response()->json([
                'events' => $stats,
            ]);
        }
    }

    /**
     * Halaman statistik (view)
     */
    public function index()
    {
        $events = Schema::hasTable('events') 
            ? Event::orderBy('id', 'desc')->get() 
            : collect([]);
        return view('statistics.index', compact('events'));
    }

    /**
     * Statistik sebelum dan sesudah untuk web (tanpa auth)
     */
    public function beforeAfterWeb(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);

        // Statistik sebelum tanggal
        $before = [
            'total_interactions' => Interaction::where('created_at', '<', $date)->count(),
            'by_mood' => Interaction::where('created_at', '<', $date)
                ->select('mood_id', DB::raw('count(*) as total'))
                ->with('mood:id,mood_name')
                ->groupBy('mood_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'mood_name' => $item->mood->mood_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'by_menu' => Interaction::where('created_at', '<', $date)
                ->select('menu_id', DB::raw('count(*) as total'))
                ->with('menu:id,menu_name')
                ->groupBy('menu_id')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'menu_name' => $item->menu->menu_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'unique_users' => Interaction::where('created_at', '<', $date)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // Statistik sesudah tanggal
        $after = [
            'total_interactions' => Interaction::where('created_at', '>=', $date)->count(),
            'by_mood' => Interaction::where('created_at', '>=', $date)
                ->select('mood_id', DB::raw('count(*) as total'))
                ->with('mood:id,mood_name')
                ->groupBy('mood_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'mood_name' => $item->mood->mood_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'by_menu' => Interaction::where('created_at', '>=', $date)
                ->select('menu_id', DB::raw('count(*) as total'))
                ->with('menu:id,menu_name')
                ->groupBy('menu_id')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'menu_name' => $item->menu->menu_name ?? 'Unknown',
                        'total' => $item->total,
                    ];
                }),
            'unique_users' => Interaction::where('created_at', '>=', $date)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // Perhitungan perubahan
        $change = [
            'interactions_change' => $this->calculateChange($before['total_interactions'], $after['total_interactions']),
            'users_change' => $this->calculateChange($before['unique_users'], $after['unique_users']),
        ];

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'before' => $before,
            'after' => $after,
            'change' => $change,
        ]);
    }

    /**
     * Statistik per event untuk web (tanpa auth)
     */
    public function perEventWeb(Request $request)
    {
        $eventId = $request->query('event_id');

        if ($eventId) {
            // Statistik untuk event tertentu
            $event = Event::findOrFail($eventId);
            
            $stats = [
                'event' => [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'description' => $event->description,
                ],
                'total_interactions' => Interaction::where('event_id', $eventId)->count(),
                'by_mood' => Interaction::where('event_id', $eventId)
                    ->select('mood_id', DB::raw('count(*) as total'))
                    ->with('mood:id,mood_name')
                    ->groupBy('mood_id')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'mood_name' => $item->mood->mood_name ?? 'Unknown',
                            'total' => $item->total,
                        ];
                    }),
                'by_menu' => Interaction::where('event_id', $eventId)
                    ->select('menu_id', DB::raw('count(*) as total'))
                    ->with('menu:id,menu_name')
                    ->groupBy('menu_id')
                    ->orderByDesc('total')
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'menu_name' => $item->menu->menu_name ?? 'Unknown',
                            'total' => $item->total,
                        ];
                    }),
                'unique_users' => Interaction::where('event_id', $eventId)
                    ->distinct('user_id')
                    ->count('user_id'),
                'daily_interactions' => Interaction::where('event_id', $eventId)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date')
                    ->get(),
            ];

            return response()->json($stats);
        } else {
            // Statistik untuk semua event
            $events = Event::withCount('interactions')
                ->orderBy('id', 'desc')
                ->get();

            $stats = $events->map(function ($event) {
                return [
                    'event' => [
                        'id' => $event->id,
                        'event_name' => $event->event_name,
                    ],
                    'total_interactions' => $event->interactions_count,
                    'by_mood' => Interaction::where('event_id', $event->id)
                        ->select('mood_id', DB::raw('count(*) as total'))
                        ->with('mood:id,mood_name')
                        ->groupBy('mood_id')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'mood_name' => $item->mood->mood_name ?? 'Unknown',
                                'total' => $item->total,
                            ];
                        }),
                ];
            });

            return response()->json([
                'events' => $stats,
            ]);
        }
    }

    /**
     * Helper untuk menghitung perubahan persentase
     */
    private function calculateChange($before, $after)
    {
        if ($before == 0) {
            return $after > 0 ? 100 : 0;
        }
        return round((($after - $before) / $before) * 100, 2);
    }
}
