<?php
//試験1　おみくじプログラム

$rnd = mt_rand(1, 100);
if ($rnd <= 10) {
  print("大吉");
} elseif ($rnd <= 35) {
  print("中吉");
} elseif ($rnd <= 70) {
  print("小吉");
} elseif ($rnd <= 95) {
  print("吉");
} else {
  print("凶");
}
