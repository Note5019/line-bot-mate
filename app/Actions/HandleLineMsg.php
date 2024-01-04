<?php

namespace App\Actions;

use App\Enums\ResponseCode;
use App\Models\HandlerResponse;
use App\Models\MyGold;
use Exception;

class HandleLineMsg
{
  protected string $lineMsg = '';
  protected array $rawCmd = [];

  protected ?string $cmd = null;
  protected array $arguments = [];

  public function __construct(string $lineMsg)
  {
    $this->lineMsg = $lineMsg;
  }

  public function execute(): HandlerResponse
  {
    $this->transformCmd();

    return $this->handleCmd();
  }

  public function transformCmd(): void
  {
    $this->rawCmd = explode("\n", str_replace(' ', '', $this->lineMsg));
    $this->cmd = strtolower($this->rawCmd[0]);

    for ($i = 1; $i < count($this->rawCmd); $i++) {
      $arg = explode(':', $this->rawCmd[$i]);
      $this->arguments += [
        $arg[0] => $arg[1],
      ];
    }
  }

  public function handleCmd()
  {
    return match ($this->cmd) {
      'buy' => $this->buyGold(),
      'trends' => '',
      'sell' => $this->sellGold(),
      'remove' => $this->removeGold(),
      'update_target_sell_price' => '',
      'update_target_baht_profit' => '',
      'menu' => $this->displayMenu(),
      'template' => $this->displatTemplate(),
      default => $this->notFoundCmd(),
    };
  }

  public function notFoundCmd(): HandlerResponse
  {
    $message = "ไม่พบคำสั่งนี้";

    NotifyMessage::execute($message);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }

  public function buyGold(): HandlerResponse
  {
    $res = new HandlerResponse();
    try {
      MyGold::create([
        'buy_price' => $this->arguments['buy_price'],
        'value' => $this->arguments['value'],
        'weight' => $this->arguments['weight'],
        'code' => $this->arguments['code'],
        'target_sell_price' => $this->arguments['target_sell_price'] ?? null,
        'target_baht_profit' => $this->arguments['target_baht_profit'] ?? null,
        'sold' => false,
      ]);
      $res->code = ResponseCode::OK;
      $res->topic = '[บันทึก - ซื้อทอง] สำเร็จ';
      $res->message = 'บันทึกข้อมูลลงระบบเรียบร้อย';
    } catch (Exception $e) {
      \Log::error($e->getMessage());

      $res->code = ResponseCode::ERROR;
      $res->topic = '💥 [บันทึก - ซื้อทอง] เกิดข้อผิดพลาด';
      $res->message = 'error: ' . $e->getMessage();
    } finally {
      return $res;
    }
  }

  public function sellGold(): HandlerResponse
  {
    $res = new HandlerResponse();
    try {
      $myGold = MyGold::where('code', $this->arguments['code'])->firstOrFail();
      $myGold->sold = true;
      $myGold->save();

      $res->code = ResponseCode::OK;
      $res->topic = '[บันทึก - ขายทอง] สำเร็จ';
      $res->message = 'บันทึกข้อมูลลงระบบเรียบร้อย';
    } catch (Exception $e) {
      \Log::error($e->getMessage());

      $res->code = ResponseCode::ERROR;
      $res->topic = '💥 [บันทึก - ขายทอง] เกิดข้อผิดพลาด';
      $res->message = 'error: ' . $e->getMessage();
    } finally {
      return $res;
    }
  }

  public function removeGold(): HandlerResponse
  {
    $res = new HandlerResponse();
    try {
      $myGold = MyGold::where('code', $this->arguments['code'])->firstOrFail();
      $myGold->delete();

      $res->code = ResponseCode::OK;
      $res->topic = '[บันทึก - ลบข้อมูล] สำเร็จ';
      $res->message = 'ลบข้อมูลออกจากระบบเรียบร้อย';
    } catch (Exception $e) {
      \Log::error($e->getMessage());

      $res->code = ResponseCode::ERROR;
      $res->topic = '💥 [[บันทึก - ลบข้อมูล] เกิดข้อผิดพลาด';
      $res->message = 'error: ' . $e->getMessage();
    } finally {
      return $res;
    }
  }

  public function displayMenu(): HandlerResponse
  {
    $json = '{"type":"bubble","body":{"type":"box","layout":"vertical","contents":[{"type":"box","layout":"vertical","contents":[{"type":"text","text":"Template","align":"center"},{"type":"box","layout":"horizontal","contents":[{"type":"button","action":{"type":"message","label":"##label_1","text":"##text_1"},"style":"primary"},{"type":"button","action":{"type":"message","label":"##label_2","text":"##text_2"},"style":"link","margin":"md"},{"type":"button","action":{"type":"message","label":"##label_3","text":"##text_3"},"style":"secondary","margin":"md"}],"margin":"md"}]}]}}';
    $json = str_replace('##label_1', 'ซื้อ', $json);
    $json = str_replace('##text_1', "template\\nmenu:buy", $json);
    $json = str_replace('##label_2', 'ลบ', $json);
    $json = str_replace('##text_2', "template\\nmenu:remove", $json);
    $json = str_replace('##label_3', 'ขาย', $json);
    $json = str_replace('##text_3', "template\\nmenu:sell", $json);

    $msg = json_decode($json, true);
    $msgPayload = [
      [
        "type" => "flex",
        "altText" => 'เมนู',
        "contents" => $msg,
      ]
    ];

    PushLineMessage::execute($msgPayload);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }

  public function displatTemplate(): HandlerResponse
  {
    return match ($this->arguments['menu']) {
      'buy' => $this->displayBuyTemplate(),
      'sell' => $this->displaySellTemplate(),
      'remove' => $this->displayRemovelTemplate(),
    };
  }

  public function displayBuyTemplate(): HandlerResponse
  {
    $message = "Buy\nbuy_price:\nvalue:\nweight:\ncode:\ntarget_sell_price:\ntarget_baht_profit:";

    NotifyMessage::execute($message);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }

  public function displaySellTemplate(): HandlerResponse
  {
    $message = "Sell\ncode:";

    NotifyMessage::execute($message);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }

  public function displayRemovelTemplate(): HandlerResponse
  {
    $message = "Remove\ncode:";

    NotifyMessage::execute($message);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }
}
