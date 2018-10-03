<?php
  include 'includes/login.php';
  $fp = fopen('info.txt', 'r'); // ファイルを開く
  $line = array();  // ファイル内容を1行1要素に格納する配列
  // ファイルが正しく開けたとき
  if ($fp) {
      while (!feof($fp)) {
          $line[] = fgets($fp);
      }
      fclose($fp);
  }
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>エンジニア交流サイト</title>
</head>
<body>
  <h1>エンジニア交流サイト</h1>
  <p><a href="index.php">トップページへ戻る</a></p>
  <h2>お知らせ</h2>
  <?php
   if (count($line)){
    for ($i = 0; $i < count($line); $i++){
      if ($i == 0){ // はじめの行はタイトル
        echo '<h3>' . $line[0] . '</h3>';
      } else if ($i == 1){  // Google Map API使ってみる。2行目は緯度経度
        echo '<img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $line[1] .
             '&markers=size:mid|color:blue|label:T|'.$line[1].'&zoom=15&size=400x400&sensor=false" /><br />';
      } else {
        echo $line[$i] . '<br />';
      }
    }
  } else {
    // ファイルの中身が空だったとき
    echo 'お知らせはありません。';
  ?>
</body>
</html>