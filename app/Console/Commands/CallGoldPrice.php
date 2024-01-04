<?php

namespace App\Console\Commands;

use App\Actions\FetchGoldPrice;
use App\Actions\SaveGoldPrice;
use App\Models\Gold;
use Illuminate\Console\Command;

class CallGoldPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gold:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch gold price and save to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = FetchGoldPrice::execute();

        $rawGold = collect($data)->filter(function ($item) {
            return $item['GoldType'] === Gold::GOLD_TYPE && $item['GoldCode'] === Gold::GOLD_CODE;
        })->first();

        SaveGoldPrice::execute($rawGold);
    }
}
