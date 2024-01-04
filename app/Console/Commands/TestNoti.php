<?php

namespace App\Console\Commands;

use App\Actions\NotifyError;
use App\Actions\NotifyMessage;
use Illuminate\Console\Command;

class TestNoti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'noti:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test send noti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $topic = 'หัวเรื่อง';
        $message = 'ข้อความ';
        // NotifyError::execute($topic, $message);
        NotifyMessage::execute("$topic, $message");
    }
}
