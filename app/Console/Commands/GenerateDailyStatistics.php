<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StatisticDaily;
use App\Models\Interaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateDailyStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Bisa dipanggil:
     * php artisan app:generate-daily-statistics
     * php artisan app:generate-daily-statistics 2025-01-01
     */
    protected $signature = 'app:generate-daily-statistics {date?}';

    /**
     * The console command description.
     */
    protected $description = 'Generate statistik interaksi harian per mood';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil tanggal (default: kemarin)
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->toDateString()
            : now()->subDay()->toDateString();

        $this->info("Generate statistik untuk tanggal: {$date}");

        // Ambil data dari tabel interactions
        $stats = Interaction::select(
                'mood_id',
                DB::raw('COUNT(*) as total_interactions'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users')
            )
            ->whereDate('created_at', $date)
            ->groupBy('mood_id')
            ->get();

        if ($stats->isEmpty()) {
            $this->warn('Tidak ada data interaksi pada tanggal tersebut.');
            return Command::SUCCESS;
        }

        // Simpan ke statistics_daily
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

        $this->info('Statistik harian berhasil disimpan.');
        return Command::SUCCESS;
    }
}