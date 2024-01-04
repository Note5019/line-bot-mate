<?php

namespace App\Observers;

use App\Actions\CheckMyGold;
use App\Actions\NotifySellMyGold;
use App\Actions\ValidGoldPriceChanged;
use App\Models\Gold;

class GoldObserver
{
    public function created(Gold $gold): void
    {
        if (!ValidGoldPriceChanged::execute($gold)) {
            return;
        }

        $records = CheckMyGold::execute($gold);
        if (count($records) > 0) {
            NotifySellMyGold::execute($records, $gold);
        }
    }

    /**
     * Handle the Gold "updated" event.
     */
    public function updated(Gold $gold): void
    {
        //
    }

    /**
     * Handle the Gold "deleted" event.
     */
    public function deleted(Gold $gold): void
    {
        //
    }

    /**
     * Handle the Gold "restored" event.
     */
    public function restored(Gold $gold): void
    {
        //
    }

    /**
     * Handle the Gold "force deleted" event.
     */
    public function forceDeleted(Gold $gold): void
    {
        //
    }
}
