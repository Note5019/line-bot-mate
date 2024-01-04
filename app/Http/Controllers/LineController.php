<?php

namespace App\Http\Controllers;

use App\Actions\HandleLineMsg;
use App\Actions\NotifyError;
use App\Actions\NotifyMessage;
use App\Enums\ResponseCode;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function handleMsg(Request $request)
    {
        $input = $request->input();
        $msg = $input['events'][0]['message']['text'];
        $handler = new HandleLineMsg($msg);
        $res = $handler->execute();
        
        match (true) {
            $res->code->value > ResponseCode::OK->value =>  NotifyError::execute($res->topic, $res->message),
            $res->code->value == ResponseCode::OK->value => NotifyMessage::execute("$res->topic, $res->message"),
            default => null,
        };

        return ['success' => true];
    }
}
