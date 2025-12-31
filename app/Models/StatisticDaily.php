<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StatisticDaily;
use App\Models\Interaction;
use Carbon\Carbon;
use DB;

class GenerateDailyStatistics extends Command
{
    protected $signature = 'statistics:daily {date?}';
    protected $description = 'Generate daily mood statistics';

    public function handle()
    {
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->toDateString()
            : now()->subDay()->toDateString();

        $stats = Interaction::select(
                'mood_id',
                DB::raw('COUNT(*) as total_interactions'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users')
            )
            ->whereDate('created_at', $date)
            ->groupBy('mood_id')
            ->get();

        foreach ($stats as $stat) {
            StatisticDaily::updateOrCreate(
                [
                    'stat_date' => $date,
                    'mood_id' => $stat->mood_id,
                ],
                [
                    'total_interactions' => $stat->total_interactions,
                    'unique_users' => $stat->unique_users,
                ]
            );
        }

        $this->info("Statistik tanggal {$date} berhasil dibuat");
    }
}