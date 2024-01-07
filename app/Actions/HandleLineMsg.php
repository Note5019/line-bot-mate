<?php

namespace App\Actions;

use App\Enums\ResponseCode;
use App\Models\Gold;
use App\Models\HandlerResponse;
use App\Models\MyGold;
use Exception;
use Illuminate\Database\Eloquent\Collection;

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
      'trends' => '', // graph 
      'sell' => $this->sellGold(),
      'remove' => $this->removeGold(),
      'update_target_sell_price' => '',
      'update_target_baht_profit' => '',
      'menu' => $this->displayMenu(),
      'template' => $this->displatTemplate(),
      'gold_price' => $this->displayGoldPrice(),
      'my_gold' => $this->displayMyGold(),
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
    $json = '{"type":"bubble","body":{"type":"box","layout":"vertical","contents":[{"type":"box","layout":"vertical","contents":[{"type":"text","text":"Transaction Template","align":"center"},{"type":"box","layout":"horizontal","contents":[{"type":"button","action":{"type":"message","label":"##label_1","text":"##text_1"},"style":"primary"},{"type":"button","action":{"type":"message","label":"##label_2","text":"##text_2"},"style":"link","margin":"md"},{"type":"button","action":{"type":"message","label":"##label_3","text":"##text_3"},"style":"secondary","margin":"md"}],"margin":"md"}]},{"type":"separator","margin":"md"},{"type":"box","layout":"vertical","contents":[{"type":"text","align":"center","text":"Action"},{"type":"box","layout":"vertical","contents":[{"type":"button","action":{"type":"message","label":"##label_4","text":"##text_4"},"style":"primary"},{"type":"button","action":{"type":"message","label":"##label_5","text":"##text_4"},"style":"secondary","margin":"md"}],"margin":"md"}],"margin":"md"}]}}';
    $json = str_replace('##label_1', 'ซื้อ', $json);
    $json = str_replace('##text_1', "template\\nmenu:buy", $json);
    $json = str_replace('##label_2', 'ลบ', $json);
    $json = str_replace('##text_2', "template\\nmenu:remove", $json);
    $json = str_replace('##label_3', 'ขาย', $json);
    $json = str_replace('##text_3', "template\\nmenu:sell", $json);
    $json = str_replace('##label_4', 'ราคาทอง', $json);
    $json = str_replace('##text_4', "gold_price", $json);
    $json = str_replace('##label_5', 'ทองของฉัน', $json);
    $json = str_replace('##text_5', 'my_gold', $json);

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

  public function displayGoldPrice(): HandlerResponse
  {
    $json = '{"type":"bubble","header":{"type":"box","layout":"vertical","contents":[{"type":"text","text":"##topic","size":"lg"}],"backgroundColor":"#80BCBD"},"body":{"type":"box","layout":"vertical","contents":[{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"ซื้อ"},{"type":"text","align":"end","text":"##buy_icon"},{"type":"text","text":"##buy","contents":[],"align":"end"}]},{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"ขาย"},{"type":"text","text":"##sell_icon","align":"end"},{"type":"text","text":"##sell","contents":[],"align":"end"}]}],"backgroundColor":"#AAD9BB"},"footer":{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"##datetime","size":"xxs"}],"backgroundColor":"#F9F7C9"},"styles":{"hero":{"separator":true}}}';

    $data = FetchGoldPrice::execute();

    $rawGold = collect($data)->filter(function ($item) {
      return $item['GoldType'] === Gold::GOLD_TYPE && $item['GoldCode'] === Gold::GOLD_CODE;
    })->first();
    $topic = "ราคาทองคำ {$rawGold['GoldType']}-{$rawGold['GoldCode']}%";
    $buyIcon = ($rawGold['BuyChange'] > 0 ? '🟢' : '🔴') . ' ' . $rawGold['BuyChange'];
    $sellIcon = ($rawGold['SellChange'] > 0 ? '🟢' : '🔴') . ' ' . $rawGold['SellChange'];;
    $json = str_replace('##topic', $topic, $json);
    $json = str_replace('##buy_icon', $buyIcon, $json);
    $json = str_replace('##buy', $rawGold['Buy'], $json);
    $json = str_replace('##sell_icon', $sellIcon, $json);
    $json = str_replace('##sell', $rawGold['Sell'], $json);
    $json = str_replace('##datetime', $rawGold['StrTimeUpdate'], $json);

    $msg = json_decode($json, true);
    $msgPayload = [
      [
        "type" => "flex",
        "altText" => 'ราคาทอง ณ',
        "contents" => $msg,
      ]
    ];

    PushLineMessage::execute($msgPayload);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }

  public function displayMyGold(): HandlerResponse
  {
    $currentGold = Gold::orderBy('id', 'desc')->first();
    $jsonWrapper = '{"type":"bubble","body":{"type":"box","layout":"vertical","contents":[{"type":"box","layout":"vertical","contents":[{"type":"text","text":"รายการทองของฉัน"},{"type":"separator","margin":"md"}]}##replace]},"footer":{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"ปัจจุบันทองราคา"},{"type":"text","text":"##gold_price","align":"end"}]}}';
    $jsonWrapper = str_replace('##gold_price', $currentGold->buy, $jsonWrapper);

    $myGold = MyGold::where('sold', false)->get();
    $temp = [];
    foreach ($myGold as $my) {
      array_push($temp, $this->buildMyGoldDetail($my, $currentGold));
    }
    if (empty($temp)) {
      $notFoundJson = '{"type":"box","layout":"vertical","contents":[{"type":"text","text":"ไม่พบข้อมูลทองคำ","align":"center"},{"type":"separator","margin":"md"}],"margin":"md"}';
      $jsonWrapper = str_replace('##replace', ',' . $notFoundJson, $jsonWrapper);
    } else {
      $jsonWrapper = str_replace('##replace', ',' . implode(',', $temp), $jsonWrapper);
    }

    $msgObject = json_decode($jsonWrapper, true);
    $msgPayload = [
      [
        "type" => "flex",
        "altText" => 'รายการทองของฉัน',
        "contents" => $msgObject,
      ]
    ];

    PushLineMessage::execute($msgPayload);
    $res = new HandlerResponse();
    $res->code = ResponseCode::OK_NO_RESPONSE;
    return $res;
  }

  public function buildMyGoldDetail(MyGold $myGold, Gold $currentGold): string
  {
    $profit = (($currentGold->buy - $myGold->buy_price) / Gold::GOLD_WEIGHT) * $myGold->weight;
    $jsonDetail = '{"type":"box","layout":"vertical","contents":[{"type":"text","text":"##code"},{"type":"box","layout":"vertical","contents":[{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"##buy"},{"type":"text","text":"##value","align":"end"}]},{"type":"box","layout":"horizontal","contents":[{"type":"text","text":"กำไร","align":"end"},{"type":"text","text":"##change","align":"end","color":"##color"}]}]},{"type":"separator","margin":"md"}],"margin":"md"}';
    $jsonDetail = str_replace('##code', '#' . $myGold->code, $jsonDetail);
    $jsonDetail = str_replace('##buy', $myGold->buy_price, $jsonDetail);
    $jsonDetail = str_replace('##value', $myGold->value, $jsonDetail);
    $jsonDetail = str_replace('##change', ($profit > 0 ? '🟢 ' : '🔴 ') .  bcdiv($profit, 1, 2), $jsonDetail);
    $jsonDetail = str_replace('##color', $profit > 0 ? '#219C90' : '#CE5A67', $jsonDetail);
    return $jsonDetail;
  }
}
