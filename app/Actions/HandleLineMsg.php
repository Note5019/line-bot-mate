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
      'buy' => $this->buyGold($this->arguments),
      'trends' => '',
      'sell' => '',
      'remove' => '',
      'update_target_sell_price' => '',
      'update_target_baht_profit' => '',
      'menu' => '',
    };
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
      $res->topic = '[ซื้อทอง] สำเร็จ';
      $res->message = 'บันทึกข้อมูลลงระบบเรียบร้อย, ขอให้โชคดีจ้า 🍀🍀🍀';
    } catch (Exception $e) {
      \Log::error($e->getMessage());

      $res->code = ResponseCode::ERROR;
      $res->topic = '💥 [ซื้อทอง] เกิดข้อผิดพลาด';
      $res->message = 'error: ' . $e->getMessage();
    } finally {
      return $res;
    }
  }
}
