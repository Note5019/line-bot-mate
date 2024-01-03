<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
class BuildGoldLineMessage
{
  public static function execute(string $topic, string $buyPrice, string $sellPrice, string $date): array
  {

    return [
      "type" => "bubble",
      "size" => "giga",
      "header" => [
        "type" => "box",
        "layout" => "vertical",
        "contents" => [
          [
            "type" => "text",
            "text" => "ราคาทองคำ {$topic}",
            "size" => "lg"
          ]
        ],
        "backgroundColor" => "#80BCBD"
      ],
      "body" => [
        "type" => "box",
        "layout" => "vertical",
        "contents" => [
          [
            "type" => "box",
            "layout" => "horizontal",
            "contents" => [
              [
                "type" => "text",
                "text" => "ซื้อ"
              ],
              [
                "type" => "text",
                "align" => "end",
                "text" => "🔺" //TODO
              ],
              [
                "type" => "text",
                "text" => $buyPrice,
                "contents" => [],
                "align" => "end"
              ]
            ]
          ],
          [
            "type" => "box",
            "layout" => "horizontal",
            "contents" => [
              [
                "type" => "text",
                "text" => "ขาย"
              ],
              [
                "type" => "text",
                "text" => "🔻", //TODO
                "align" => "end"
              ],
              [
                "type" => "text",
                "text" => $sellPrice,
                "contents" => [],
                "align" => "end"
              ]
            ]
          ]
        ],
        "backgroundColor" => "#AAD9BB"
      ],
      "footer" => [
        "type" => "box",
        "layout" => "horizontal",
        "contents" => [
          [
            "type" => "text",
            "text" => $date,
            "size" => "sm"
          ]
        ],
        "backgroundColor" => "#F9F7C9"
      ],
      "styles" => [
        "hero" => [
          "separator" => true
        ]
      ]
    ];
  }
}
