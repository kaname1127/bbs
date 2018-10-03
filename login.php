<?php
  session_start();  // セッション開始

  if (isset($_SESSION['id'])) {
      // セッションにユーザIDがある=ログインしている
      // トップページに遷移する
      header('Location: index.php');
  } elseif (isset($_POST['name']) && isset($_POST['password'])) {
      // ログインしていないがユーザ名とパスワードが送信されたとき

      // DB接続
      $dsn = 'mysql:host=localhost;dbname=engineer;charset=utf8';
      $user = 'root';
      $password = 'mysql';

      try {
          $db = new PDO($dsn, $user, $password);
          $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          // プリペアドステートメントを作成
          $stmt = $db->prepare(
        'SELECT * FROM users WHERE name=:name AND password=:pass'
      );

          // パラメータを割り当て
          $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
          $stmt->bindParam(':pass', sha1($_POST['password']), PDO::PARAM_STR);

          //クエリの実行
          $stmt->execute();

          if ($row = $stmt->fetch()) {
              // ユーザが存在してる場合、セッションにユーザIDをセット
              $_SESSION['id'] = $row['id'];
              // セッションハイジャック対策として、セッションID再作成
              session_regenerate_id(true);
              header('Location: index.php');
              exit();
          } else {
              // 1レコードも取得できなかったとき
              // ユーザ名・パスワードが間違っている可能性あり
              // 再度ログインフォームを表示
              header('Location: login.php');
              exit();
          }
      } catch (PDOException $e) {
          die('エラー：'.$e->getMessage());
      }
  } else {
      // ログインしていない場合はログインフォームを表示
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>エンジニア交流サイト</title>
</head>
<body>
  <h1>エンジニア交流サイト</h1>

  <h2>ログイン</h2>
  <form action="login.php" method="post">
    <p>ユーザ名：<input type="text" name="name"></p>
    <p>パスワード：<input type="password" name="password"></p>
    <p><input type="submit" value="ログイン"></p>
  </form>

</body>
</html>
<?php
  } ?>
