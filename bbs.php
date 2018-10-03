<?php
  include 'includes/login.php';
    $num = 10; //1Pに表示するコメント数

    //DB接続
    $dsn = 'mysql:host=localhost;dbname=engineer;charset=utf8';
    $user = 'root';
    $password = 'mysql';

  // ページ数が指定されているとき
  $page = 0;
  if (isset($_GET['page']) && $_GET['page'] > 0) {
      $page = intval($_GET['page']) - 1;
  }

  try {
      $db = new PDO($dsn, $user, $password);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      // プリペアドステートメントを作成
      $stmt = $db->prepare(
      'SELECT * FROM bbs ORDER BY date DESC LIMIT :page, :num'
    );
      // パラメータを割り当て
      $page = $page * $num;
      $stmt->bindParam(':page', $page, PDO::PARAM_INT);
      $stmt->bindParam(':num', $num, PDO::PARAM_INT);
      // クエリの実行
      $stmt->execute();
  } catch (PDOException $e) {
      echo 'エラー：'.$e->getMessage();
  }
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>エンジニア掲示板</title>
</head>
<body>
    <h1>エンジニア掲示板</h1>
    <a href="index.php">トップページに戻る</a>

    <form action="write.php" method="post">
        <p>名前：<input type="text" name="name" value="<?php echo isset($_COOKIE['name']) ? $_COOKIE['name'] : ''; ?>"></p>
        <p>タイトル：<input type="text" name="title"></p>
        <p>本文</p><textarea name="body"></textarea>
        <p>削除パスワード（数字4桁）：<input type="text" name="pass"></p>
        <p><input type="submit" value="書き込む"></p>
        <input type="hidden" name="token" value="<?php echo sha1(session_id()); ?>">
    </form>
    <hr>
<?php
  while ($row = $stmt->fetch()):
    $title = $row['title'] ? $row['title'] : '（無題）';
?>
    <p>名前：<?php echo $row['name']; ?></p>
    <p>タイトル：<?php echo $title; ?></p>
    <!-- XSS対策 --> 
    echo nl2br(htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8'), false);
    <p><?php echo $row['date']; ?></p>

    <form action="delete.php" method="post">
      <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
      削除パスワード：<input type="password" name="pass">
      <input type="submit" value="削除">
    </form>
<?php
  endwhile;

  // ページ数の表示
  try {
      // プリペアドステートメント作成
      $stmt = $db->prepare('SELECT COUNT(*) FROM bbs');
      // クエリの実行
      $stmt->execute();
  } catch (PDOException $e) {
      echo 'エラー：'.$e->getMessage();
  }

  // コメントの件数を取得
  $comments = $stmt->fetchColumn();
  // ページ数を計算
  $max_page = ceil($comments / $num);
  echo '<p>';
  for ($i = 1; $i <= $max_page; ++$i) {
      echo '<a href="bbs.php?page='.$i.'">'.$i.'</a>&nbsp;';
  }
  echo '</p>';
?>
</body>
</html>
