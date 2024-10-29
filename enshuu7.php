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

  //3月の春分の日（国立天文台より参照）
  function getShunbunDay($year)
  {
    if (1900 <= $year && $year <= 1995) {
      if ($year == 1909) return 20;
      if ($year == 1917) return 20;
      if ($year == 1918) return 20;
      if ($year == 1929) return 20;
      if ($year == 1937) return 20;
      if ($year == 1938) return 20;
      if ($year == 1941) return 20;
      if ($year == 1942) return 20;
      if ($year == 1957) return 20;
      if ($year == 1977) return 20;
      if ($year == 1989) return 20;
      if ($year % 4 == 0) return 20;
      return 21;
    }
    if (1996 <= $year && $year <= 2050) {
      if ($year == 2005) return 21;
      if ($year == 2006) return 21;
      if ($year == 2015) return 20;
      if ($year == 2018) return 21;
      if ($year == 2022) return 21;
      if ($year % 4 == 3) return 21;
      return 20;
    }
    return 20;
  }

  //9月の秋分の日（国立天文台より参照）
  function getShuubunDay($year)
  {
    if (1900 <= $year && $year <= 2050) {
      if ($year == 1992) return 22;
      if ($year == 2045) return 22;
      if ($year == 2049) return 22;
      if ($year % 4 == 0) return 22;
      return 23;
    }
    return 23;
  }

  //元号を求める
  function getGengou($year, $gengous, &$gengou)
  {
    if (!is_numeric($year)) return 0;
    $y = 0;
    foreach ($gengous as $key => $val) {
      if ($key < $year) {
        $gengou = $val;
        $y = $year - $key;
      }
    }
    return $y;
  }

  $gengous = [
    "0644" => "大化",
    "0650" => "白雉",
    "0655" => "斉明",
    "0663" => "天智",
    "0672" => "弘文",
    "0673" => "天武",
    "0685" => "朱鳥",
    "0686" => "文武",
    "0701" => "大宝",
    "0708" => "和銅",
    "0715" => "霊亀",
    "0717" => "養老",
    "0723" => "神亀",
    "0728" => "天平",
    "0748" => "天平感宝",
    "0757" => "天平宝字",
    "0764" => "天平神護",
    "0766" => "神護景雲",
    "0769" => "宝亀",
    "0779" => "天応",
    "0781" => "延暦",
    "0806" => "大同",
    "0811" => "弘仁",
    "0824" => "天長",
    "0835" => "承和",
    "0850" => "嘉祥",
    "0854" => "仁寿",
    "0857" => "斉衡",
    "0859" => "天安",
    "0861" => "貞観",
    "0877" => "元慶",
    "0885" => "仁和",
    "0898" => "寛平",
    "0901" => "延喜",
    "0923" => "延長",
    "0931" => "承平",
    "0938" => "天慶",
    "0947" => "天暦",
    "0957" => "天徳",
    "0961" => "応和",
    "0964" => "康保",
    "0969" => "安和",
    "0971" => "天禄",
    "0974" => "天延",
    "0976" => "貞元",
    "0979" => "天元",
    "0983" => "永観",
    "0985" => "寛和",
    "0988" => "永延",
    "0989" => "永祚",
    "0990" => "正暦",
    "0995" => "長徳",
    "0999" => "長保",
    "1003" => "寛弘",
    "1011" => "長和",
    "1016" => "寛仁",
    "1020" => "治安",
    "1023" => "万寿",
    "1027" => "長元",
    "1036" => "長暦",
    "1039" => "長久",
    "1043" => "寛徳",
    "1045" => "永承",
    "1052" => "天喜",
    "1057" => "康平",
    "1064" => "治暦",
    "1068" => "延久",
    "1073" => "承保",
    "1076" => "承暦",
    "1080" => "永保",
    "1083" => "応徳",
    "1086" => "寛治",
    "1093" => "嘉保",
    "1095" => "永長",
    "1096" => "承徳",
    "1098" => "康和",
    "1105" => "嘉承",
    "1107" => "天永",
    "1112" => "永久",
    "1117" => "元永",
    "1119" => "保安",
    "1123" => "天治",
    "1125" => "大治",
    "1130" => "天承",
    "1131" => "長承",
    "1134" => "保延",
    "1140" => "永治",
    "1141" => "康治",
    "1143" => "天養",
    "1144" => "久安",
    "1150" => "仁平",
    "1153" => "久寿",
    "1155" => "保元",
    "1158" => "平治",
    "1159" => "永暦",
    "1160" => "応保",
    "1162" => "長寛",
    "1165" => "仁安",
    "1168" => "嘉応",
    "1170" => "承安",
    "1174" => "安元",
    "1176" => "治承",
    "1180" => "養和",
    "1181" => "寿永",
    "1183" => "元暦",
    "1184" => "文治",
    "1189" => "建久",
    "1198" => "正治",
    "1200" => "建仁",
    "1203" => "元久",
    "1205" => "建永",
    "1206" => "承元",
    "1210" => "建暦",
    "1213" => "建保",
    "1218" => "承久",
    "1221" => "貞応",
    "1223" => "元仁",
    "1224" => "嘉禄",
    "1226" => "安貞",
    "1228" => "寛喜",
    "1231" => "貞永",
    "1232" => "天福",
    "1233" => "文暦",
    "1234" => "嘉禎",
    "1237" => "暦仁",
    "1238" => "延応",
    "1239" => "仁治",
    "1242" => "寛元",
    "1246" => "宝治",
    "1248" => "建長",
    "1255" => "康元",
    "1256" => "正嘉",
    "1258" => "正元",
    "1259" => "文応",
    "1260" => "弘長",
    "1263" => "文永",
    "1274" => "建治",
    "1277" => "弘安",
    "1287" => "正応",
    "1292" => "永仁",
    "1298" => "正安",
    "1301" => "乾元",
    "1302" => "嘉元",
    "1305" => "徳治",
    "1307" => "延慶",
    "1310" => "応長",
    "1311" => "正和",
    "1316" => "文保",
    "1318" => "元応",
    "1320" => "元亨",
    "1323" => "正中",
    "1325" => "嘉暦",
    "1328" => "元徳",
    "1330" => "元弘",
    "1333" => "建武",
    "1335" => "延元",
    "1393" => "興国",
    "1345" => "正平",
    "1369" => "建徳",
    "1371" => "文中",
    "1374" => "天授",
    "1378" => "康暦",
    "1380" => "弘和",
    "1383" => "元中",
    "1386" => "嘉慶",
    "1388" => "康応",
    "1389" => "明徳",
    "1393" => "応永",
    "1427" => "正長",
    "1428" => "永享",
    "1440" => "嘉吉",
    "1443" => "文安",
    "1448" => "宝徳",
    "1451" => "享徳",
    "1454" => "康正",
    "1456" => "長禄",
    "1459" => "寛正",
    "1465" => "文正",
    "1466" => "応仁",
    "1468" => "文明",
    "1486" => "長享",
    "1488" => "延徳",
    "1491" => "明応",
    "1500" => "文亀",
    "1503" => "永正",
    "1520" => "大永",
    "1527" => "享禄",
    "1531" => "天文",
    "1554" => "弘治",
    "1557" => "永禄",
    "1569" => "元亀",
    "1572" => "天正",
    "1592" => "文禄",
    "1595" => "慶長",
    "1614" => "元和",
    "1623" => "寛永",
    "1643" => "正保",
    "1647" => "慶安",
    "1651" => "承応",
    "1654" => "明暦",
    "1657" => "万治",
    "1660" => "寛文",
    "1672" => "延宝",
    "1681" => "天和",
    "1683" => "貞享",
    "1687" => "元禄",
    "1703" => "宝永",
    "1710" => "正徳",
    "1715" => "享保",
    "1735" => "元文",
    "1740" => "寛保",
    "1743" => "延享",
    "1747" => "寛延",
    "1750" => "宝暦",
    "1763" => "明和",
    "1771" => "安永",
    "1780" => "天明",
    "1788" => "寛政",
    "1803" => "文化",
    "1817" => "文政",
    "1829" => "天保",
    "1843" => "弘化",
    "1847" => "嘉永",
    "1853" => "安政",
    "1859" => "万延",
    "1860" => "文久",
    "1863" => "元治",
    "1864" => "慶応",
    "1867" => "明治",
    "1911" => "大正",
    "1925" => "昭和",
    "1988" => "平成",
    "2018" => "令和",
  ];

  $dayErr = false;
  if (!is_null($_GET["year"])) {
    $year = h($_GET["year"]);
    $month = filter_input(INPUT_GET, "month", FILTER_VALIDATE_INT);
    if (is_numeric($year)) {
      if ($year <= 0) {
        $errMsg1 = "年は正の整数で入力してください。";
        $dayErr = true;
      }
    } else {
      $year = trim($year);
      $gen = mb_substr($year, 0, 2, "UTF-8");
      $yy = mb_substr($year, 2, 3, "UTF-8");
      $yy = mb_convert_kana($yy, "ask", "UTF-8");
      $gengouMatch = false;
      if (is_numeric($yy)) {
        foreach ($gengous as $key => $val) {
          if ($val == $gen) {
            $year = $key + $yy;
            $gengouMatch = true;
          }
        }
        if (!$gengouMatch) {
          $errMsg1 = "その元号は登録されていません。";
          $dayErr = true;
        }
      } else {
        $gen = mb_substr($year, 0, 4, "UTF-8");
        $yy = mb_substr($year, 4, 2, "UTF-8");
        $yy = mb_convert_kana($yy, "ask", "UTF-8");
        $gengouMatch = false;
        if (is_numeric($yy)) {
          foreach ($gengous as $key => $val) {
            if ($val == $gen) {
              $year = $key + $yy;
              $gengouMatch = true;
            }
          }
          if (!$gengouMatch) {
            $errMsg1 = "その元号は登録されていません。";
            $dayErr = true;
          }
        } else {
          $errMsg1 = "正しい年を入力してください。";
          $dayErr = true;
        }
      }
    }
    if ($month < 1 || 12 < $month) {
      $errMsg2 = "月は 1～12 の範囲で入力してください。";
      $dayErr = true;
    }
  }
  print("<p>");
  $y = getGengou($year, $gengous, $gengou);
  if ($y) print($gengou . " " . $y . " 年<br>");
  print(h($year) . " 年 " . h($month) . " 月 のカレンダー");
  print("</p>");
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
  <form method="get">
    <label>年：</label><input type="text" name="year" value="<?= h($year) ?>"><br>
    <p><?= $errMsg1 ?></p>
    <label>月：</label><input type="number" name="month" value="<?= h($month) ?>"><br>
    <p><?= $errMsg2 ?></p>
    <input type="submit" value="実行"><br>
  </form>
</body>

</html>