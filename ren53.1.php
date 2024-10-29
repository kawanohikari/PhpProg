<?php
// 日付と曜日と時刻関数

function getWeekday($date)
{
  $datetime = new DateTime($date);
  $weekdays = ["日", "月", "火", "水", "木", "金", "土"];
  return $weekdays[$datetime->format('w')];
}

// 例: 2024-10-17 の曜日を求める
$date = "2024-10-18";
echo $date . " は " . getWeekday($date) . "曜日です。";
// 出力: 2024-10-17 は 木曜日です。


date_default_timezone_set('Asia/Tokyo');
echo date("Y-m-d H:i:s");
echo " " . getWeekday(date("Y-m-d")) . "曜日です。";

date('w', strtotime("2024-10-18"));
