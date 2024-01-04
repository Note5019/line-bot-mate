<?php

namespace App\Console\Commands;

use App\Actions\HandleLineMsg;
use Illuminate\Console\Command;

class TestHandler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handler:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test handler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $msg = 'menu';
        $handler = new HandleLineMsg($msg);
        $res = $handler->execute();
    }
}
