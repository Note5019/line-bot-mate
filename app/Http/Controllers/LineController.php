<?php

namespace App\Http\Controllers;

use App\Actions\HandleLineMsg;
use App\Actions\NotifyError;
use App\Actions\NotifyMessage;
use App\Enums\ResponseCode;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function handle(Request $request)
    {
        $handler = new HandleLineMsg($request->input());
        $res = $handler->execute();

        if ($res->code > ResponseCode::OK) {
            NotifyError::execute($res->topic, $res->message);
        } else {
            NotifyMessage::execute($res->topic, $res->message);
        }

        return ['success' => true];
    }
}
