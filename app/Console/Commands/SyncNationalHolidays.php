<?php

namespace App\Console\Commands;

use App\Models\NationalHolidays;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncNationalHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-national-holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://www.gov.uk/bank-holidays.json');
        if (!$response->ok()) {
            error_log('Could not get national holidays');
            exit(0);
        }
        $nationalHolidays = $response->json();
        foreach ($nationalHolidays['england-and-wales']['events'] as $holiday) {
            NationalHolidays::firstOrCreate([
                "name" => $holiday['title'],
                "date" => $holiday['date']
            ]);
        }
    }
}
