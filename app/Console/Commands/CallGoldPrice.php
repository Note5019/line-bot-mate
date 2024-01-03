<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CallGoldPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:call-gold-price';

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
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get('https://apicheckprice.huasengheng.com/api/values/getprice', []);
        $data = $response->json();
        foreach($data as $row) {
            $this->info("{$row['GoldType']} {$row["GoldCode"]}: buy [{$row["Buy"]}], sell [{$row["Sell"]}]");
        }
    }
}
