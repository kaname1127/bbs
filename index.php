<?php
  include 'includes/login.php';
  $fp = fopen('info.txt', 'r'); // ファイルを開く
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>エンジニア交流サイト</title>
</head>
<body>
  <h1>エンジニア交流サイト</h1>
  <p><a href="album.php">アルバム</a></p>
  <p><a href="bbs.php">掲示板</a></p>
  <p><a href="logout.php">ログアウト</a></p>
  <h2>お知らせ</h2>
  <?php
    // ファイルが正しく開けたとき
    if ($fp) {
        $title = fgets($fp); // ファイルから1行読み込む
        if ($title) {
            echo '<a href="info.php">'.$title.'</a>';
        } else {
            // ファイルの中身が空のとき
            echo 'お知らせはありません。';
        }
        fclose($fp);
    } else {
        // ファイルが開けなかったとき
        echo 'お知らせはありません。';
    }
  ?>
</body>
</html>
