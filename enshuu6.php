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
  function h($str)
  {
    return htmlspecialchars($str);
  }

  //日付と祝日名を返す
  function getHoliday($holidays, $month, $day)
  {
    foreach ($holidays as $key => $val) {
      if (sprintf("%02d%02d", $month, $day) == $key) {
        $m = preg_replace('/^0+/', ' ', sprintf("%02d", $month));
        $d = preg_replace('/^0+/', ' ', sprintf("%02d", $day));
        return "$m/$d $val<br>";
      }
    }
    return 0;
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
            $m = preg_replace('/^0+/', ' ', sprintf("%02d", $month));
            $d = preg_replace('/^0+/', ' ', sprintf("%02d", $day));
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
    while (date('w', strtotime(sprintf("%04d-%02d-%02d", $year, $month, $day))) != 1) $day++;
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

  $dayErr = false;
  if (!is_null($_GET["year"])) {
    $year = filter_input(INPUT_GET, "year", FILTER_VALIDATE_INT);
    $month = filter_input(INPUT_GET, "month", FILTER_VALIDATE_INT);
    if ($year <= 0) {
      $errMsg1 = "年は正の整数で入力してください。";
      $dayErr = true;
    }
    if ($month < 1 || 12 < $month) {
      $errMsg2 = "月は 1～12 の範囲で入力してください。";
      $dayErr = true;
    }
  }
  print("<p>" . h($year) . " 年 " . h($month) . " 月 のカレンダー</p>");
  if (!($dayErr || is_null($_GET["year"]))) {
    $weeks = ['日', '月', '火', '水', '木', '金', '土'];
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
    $holidays[sprintf("03%02d", getShunbunDay($year))] = "春分の日";
    $holidays[sprintf("09%02d", getShuubunDay($year))] = "秋分の日";
    $holidays[sprintf("01%02d", getMondayDay($year, $month, 2))] = "成人の日";
    $holidays[sprintf("07%02d", getMondayDay($year, $month, 3))] = "海の日";
    $holidays[sprintf("09%02d", getMondayDay($year, $month, 3))] = "敬老の日";
    $holidays[sprintf("10%02d", getMondayDay($year, $month, 2))] = "体育の日";
    ksort($holidays);
    // print_r($holidays);
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
    $lastDay = date('t', strtotime(sprintf("%04d-%02d-01", $year, $month)));
    $firstYoubiNum = date('w', strtotime(sprintf("%04d-%02d-01", $year, $month)));
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
        if ($day > $lastDay) {
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
  <form action="enshuu6.php" method="get">
    <label>年：</label><input type="number" name="year" value="<?= h($year) ?>"><br>
    <p><?= $errMsg1 ?></p>
    <label>月：</label><input type="number" name="month" value="<?= h($month) ?>"><br>
    <p><?= $errMsg2 ?></p>
    <input type="submit" value="実行"><br>
  </form>
</body>

</html>