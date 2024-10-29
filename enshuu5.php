<!-- カレンダーWebアプリ -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>カレンダー</title>
  <style>
    td {
      text-align: right;
    }

    .red {
      color: red;
    }

    .blue {
      color: blue;
    }
  </style>
</head>

<body>
  <?php

  //入力チェック
  $dayErr = false;
  if (!(is_null($_GET["year"]) && is_null($_GET["month"]))) {
    $year = filter_input(INPUT_GET, "year", FILTER_VALIDATE_INT);
    $month = filter_input(INPUT_GET, "month", FILTER_VALIDATE_INT);
    if (yearErr($year)) {
      $errMsg1 = "年は正の整数で入力してください。";
      $dayErr = true;
    }
    if (monthErr($month)) {
      $errMsg2 = "月は 1～12 の範囲で入力してください。";
      $dayErr = true;
    }
  }

  function h($str)
  {
    return htmlspecialchars($str);
  }

  //年範囲チェック
  function yearErr($year)
  {
    return $year < 1;
  }

  //月範囲チェック
  function monthErr($month)
  {
    return $month < 1 || 12 < $month;
  }

  //日付と祝日名を返す
  function getHoliday($holidays, $month, $day)
  {
    foreach ($holidays as $key => $val) {
      $mm =  sprintf("%02d", $month);
      $dd =  sprintf("%02d", $day);
      $mmdd =  $mm . $dd;
      if ($mmdd == $key) {
        $m = preg_replace('/^0+/', ' ', $mm);
        $d = preg_replace('/^0+/', ' ', $dd);
        return "$m/$d $val<br>";
      }
    }
    return 0;
  }

  //月初日の曜日を得る
  function getFirstYoubiNum($year, $month)
  {
    return date('w', strtotime(sprintf('%04d', $year) . sprintf('%02d', $month) . "01"));
  }

  //月末日を得る
  function getLastDay($year, $month)
  {
    return date('t', strtotime(sprintf('%04d', $year) . sprintf('%02d', $month) . "01"));
  }

  //日付に色を付ける
  function paintColor($holidays, $month, $day, $w, &$furikaeKyuujitu, &$shukujitus)
  {
    $holiday = getHoliday($holidays, $month, $day);
    switch ($w) {
      case 0:
        if ($holiday) {
          $shukujitus[] = $holiday;
          $furikaeKyuujitu = true;
        }
        print(" class='red' ");
        break;
      case 6:
        if ($holiday) {
          $shukujitus[] = $holiday;
          print(" class='red' ");
        } else {
          print(" class='blue' ");
        }
        break;
      default:
        if ($holiday) {
          $shukujitus[] = $holiday;
          print(" class='red' ");
        } else {
          if ($furikaeKyuujitu) {
            $furikaeKyuujitu = false;
            $m = sprintf("%02d", $month);
            $d = sprintf("%02d", $day);
            $m = preg_replace('/^0+/', ' ', $m);
            $d = preg_replace('/^0+/', ' ', $d);
            $shukujitus[] = "$m/$d 振替休日<br>";
            print(" class='red' ");
          }
        }
    }
    return 0;
  }

  //第n月曜日の日付を得る
  function getMondayDay($year, $month, $n)
  {
    $day = 1;
    while (date('w', strtotime(sprintf("%04d", $year) . sprintf("%02d", $month) . sprintf("%02d", $day))) != 1) $day++;
    return 7 * ($n - 1) + $day;
  }

  //3月の春分の日
  function getShunbunDay($year)
  {
    return $year % 4 == 1 ? 21 : 20;
  }

  //9月の秋分の日
  function getShuubunDay($year)
  {
    return $year % 4 == 2 || $year % 4 == 3 ? 22 : 23;
  }

  //曜日
  $weeks = ['日', '月', '火', '水', '木', '金', '土'];

  //祝日
  $holidays = [
    "0101" => "元日",
    "0211" => "建国記念の日",
    "0223" => "天皇誕生日",
    "0429" => "昭和の日",
    "0503" => "憲法記念日",
    "0504" => "みどりの日",
    "0505" => "こどもの日",
    "0811" => "山の日",
    "1103" => "文化の日",
    "1123" => "勤労感謝の日",
  ];

  //その他の祝日をセット
  $holidays["03" . sprintf("%02d", getShunbunDay($year))] = "春分の日";
  $holidays["09" . sprintf("%02d", getShuubunDay($year))] = "秋分の日";
  $holidays["01" . sprintf("%02d", getMondayDay($year, $month, 2))] = "成人の日";
  $holidays["07" . sprintf("%02d", getMondayDay($year, $month, 3))] = "海の日";
  $holidays["09" . sprintf("%02d", getMondayDay($year, $month, 3))] = "敬老の日";
  $holidays["10" . sprintf("%02d", getMondayDay($year, $month, 2))] = "体育の日";
  ksort($holidays);
  // print_r($holidays);

  //当月1日の曜日と月末日を得る
  if (!$dayErr) {
    $firstYoubiNum = getFirstYoubiNum($year, $month);
    $monthEnd = getLastDay($year, $month);
  }

  print("<p>" . h($year) . " 年 " . h($month) . " 月 のカレンダー</p>");
  if (!$dayErr && !(is_null($_GET["year"]) && is_null($_GET["month"]))) {
    print("<table>");
    print("<tr>");
    foreach (range(0, 6) as $w) {
      print("<td");
      if ($w == 0) print(" class='red' ");
      if ($w == 6) print(" class='blue' ");
      print(">$weeks[$w]</td>");
    }
    print("</tr>");
    print("<tr>");
    $day = 1;
    $furikaeKyuujitu = false;
    $firstYoubiNum = getFirstYoubiNum($year, $month);
    for ($w = 0; $w < $firstYoubiNum; $w++) {
      print("<td></td>");
    }
    for ($w = $firstYoubiNum; $w < 7; $w++) {
      print("<td");
      paintColor($holidays, $month, $day, $w, $furikaeKyuujitu, $shukujitus);
      print(">$day</td>");
      $day++;
    }
    print("</tr>");
    foreach (range(2, 6) as $row) {
      print("<tr>");
      foreach (range(0, 6) as $w) {
        print("<td");
        paintColor($holidays, $month, $day, $w, $furikaeKyuujitu, $shukujitus);
        print(">$day</td>");
        $day++;
        if ($day > getLastDay($year, $month)) {
          break 2;
        }
      }
      print("</tr>");
    }
    print("</table>");
    print("<p>");
    foreach ($shukujitus as $shukujitu) {
      print($shukujitu);
    }
    print("</p>");
  }
  ?>

  <br>
  <form method="get">
    <label>年：</label><input type="number" name="year" value="<?= h($year) ?>"><br>
    <p><?= $errMsg1 ?></p>
    <label>月：</label><input type="number" name="month" value="<?= h($month) ?>"><br>
    <p><?= $errMsg2 ?></p>
    <input type="submit" value="実行"><br>
  </form>
</body>

</html>