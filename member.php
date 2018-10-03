<?php
  include 'includes/login.php';

  // DB接続
  $dsn = 'mysql:host=localhost;dbname=engineer;charset=utf8';
  $user = 'root';
  $password = 'mysql';

  try {
      $db = new PDO($dsn, $user, $password);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      $stmt = $db->prepare(
      'SELECT users.id, users.name AS login_name, profiles.name AS name, body, mail
       FROM users, profiles
       WHERE users.id=profiles.id'
    );
      $stmt->execute();
  } catch (PDOException $e) {
      die('エラー：'.$e->getMessage());
  }
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>エンジニア交流サイト</title>
</head>
<body>
  <h1>エンジニア交流サイト</h1>

  <h2>メンバー一覧</h2>
  <table border="1">
    <tr>
      <th>ID</th>
      <th>ログイン名</th>
      <th>氏名</th>
      <th>自己紹介</th>
      <th>メールアドレス</th>
    </tr>
  <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['login_name']; ?></td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo nl2br($row['body']); ?></td>
      <td><?php echo $row['mail']; ?></td>
    </tr>
  <?php endwhile; ?>
  </table>
</body>
</html>
