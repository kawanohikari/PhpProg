<?php

//変数設定
// $win;
// $draw;
// $loss;
// $player1;
// $player2;
// $result;

//XSS対策
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

// 引数に「&」が付いているので、受け取った引数の値は関数の中で処理された値が帰る
function playerChoise(&$win, &$draw, &$loss)
{
    $player1 = $_GET["janken"];
    $player2 = rand(1, 3);

    $player1 = intval($player1);
    echo "p1" . $player1 . "\n";
    echo "p2" . $player2 . "\n";
    $result = determineWinner($player1, $player2, $win, $draw, $loss);
    return $result;
}

$win = $_GET["win"] ?? 0;       //NULLならば 0にする
$draw = $_GET["draw"] ?? 0;     //NULLならば 0にする
$loss = $_GET["loss"] ?? 0;     //NULLならば 0にする



// 勝敗を判定する関数
// 「&」を付けた引数は、関数の外に値を返すことができる。
function determineWinner($player1, $player2, &$win, &$draw, &$loss)
{
    echo "win=" . $win . "\n";
    echo "d=" . $draw . "\n";
    echo "lo=" . $loss . "\n";

    if ($player1 != 0) {
        if ($player1 == $player2) {
            $draw++;
            echo "引き分け回数：" . $draw . "\n";
            return "引き分けです！";
        } elseif (
            ($player1 == 1 && $player2 == 2) || // ぐーがちょきに勝つ
            ($player1 == 2 && $player2 == 3) || // ちょきがぱーに勝つ
            ($player1 == 3 && $player2 == 1)    // ぱーがぐーに勝つ
        ) {
            $win++;
            echo "勝った回数：" . $win . "\n";
            return "あなたの勝ちです！";
        } else {
            $loss++;
            echo "負けた回数：" . $loss . "\n";
            return "ＰＣの勝ちです！";
        }
    }
}

//関数の中にしっかり勝負数の値を渡してあげる必要がある。これらの値は帰ってくる。
$result = playerChoise($win, $draw, $loss);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけん大会</title>
    <style>
        input.cnt {
            width: 40px;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <h1>★じゃんけん大会★</h1>
        <form method="get">
            <label><input type="radio" name="janken" value="1" selected>1:グー</label>
            <label><input type="radio" name="janken" value="2">2:チョキ</label>
            <label><input type="radio" name="janken" value="3">3:パー</label>
            <input type="submit" value="勝負！"><br><br>


            <?php if (is_null($_GET["janken"])): ?>
                勝ち：<input type="text" name="win_null" value="0" class="cnt">
                負け：<input type="text" name="loss_null" value="0" class="cnt">
                引き分け：<input type="text" name="draw_null" value="0" class="cnt">

            <?php else: ?>
                勝ち：<input type="text" name="win" value="<?= h($win) ?>" class="cnt">
                負け：<input type="text" name="loss" value="<?= h($loss) ?>" class="cnt">
                引き分け：<input type="text" name="draw" value="<?= h($draw) ?>" class="cnt">

            <?php endif; ?>


            <p class="result"><?php echo $result ?></p>
        </form>
    </div>
</body>

</html>