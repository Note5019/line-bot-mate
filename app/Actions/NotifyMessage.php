<?php

namespace App\Actions;

use App\Actions\PushLineMessage;

class NotifyMessage
{
  public static function execute(string $message): void
  {
    $msgPayload = [
      [
        "type" => "text",
        "text" => "$message",
      ]
    ];

    PushLineMessage::execute($msgPayload);
    \Log::info('push msg!');
  }
}
