<?php
  if (isset($_POST['data'])) {
      // 新規登録の場合

      // データの受信
      $data = $_POST['data'];
      // ログインユーザ名、ログインパスワード、氏名がなければ登録不可
      if (empty($data['login_name']) || empty($data['password']) || empty($data['name'])) {
          header('Location: regist.php');
          exit();
      }

      // DB接続
      $dsn = 'mysql:host=localhost;dbname=enginner;charset=utf8';
      $user = 'root';
      $password = 'mysql';

      try {
          $db = new PDO($dsn, $user, $password);
          $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          // usersテーブルにデータを挿入
          $stmt = $db->prepare(
        'INSERT INTO users (name, password) VALUES(:name, :pass)'
      );
          $stmt->bindParam(':name', $data['login_name'], PDO::PARAM_STR);
          $stmt->bindParam(':pass', sha1($data['password']), PDO::PARAM_STR);
          $stmt->execute();

          // usersテーブルに挿入したデータのIDを取得
          $id = intval($db->lastInsertId());

          // usersテーブルに挿入したレコードのIDを元にprofilesテーブルにデータを挿入
          $stmt = $db->prepare(
        'INSERT INTO profiles(id, name, body, mail)
         VALUES(:id, :name, :body, :mail)'
      );
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
          $stmt->bindParam(':body', $data['body'], PDO::PARAM_STR);
          $stmt->bindParam(':mail', $data['mail'], PDO::PARAM_STR);
          $stmt->execute();

          header('Location: login.php');
          exit();
      } catch (PDOException $e) {
          die('エラー：'.$e->getMessage());
      }
  } else {
      // 登録用フォームを表示する場合
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>エンジニア交流サイト</title>
</head>
<body>
  <h1>エンジニア交流サイト</h1>

  <h2>ユーザ新規登録</h2>
  <form action="regist.php" method="post">
    <p>ログインユーザ名：<input type="text" name="data[login_name]" /></p>
    <p>パスワード：<input type="password" name="data[password]" /></p>
    <p>氏名：<input type="text" name="data[name]" /></p>
    <p>自己紹介</p>
    <textarea name="data[body]"></textarea>
    <p>メールアドレス：<input type="text" name="data[mail]" /></p>
    <p><input type="submit" value="登録" /></p>
  </form>

</body>
</html>

<?php
  } ?>
