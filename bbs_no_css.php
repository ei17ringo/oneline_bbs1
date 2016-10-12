<?php
  // ここにDBに登録する処理を記述する

	//1. データベースに接続
	$dns = 'mysql:dbname=oneline_bbs1;host=localhost';
	$user = 'root';
	$password = '';
	$dbh = new PDO($dns,$user,$password);
	$dbh->query('SET NAMES utf8');

	//POST送信が行われた時
	if (!empty($_POST)){
		$nickname = $_POST['nickname'];
		$comment = $_POST['comment'];

		//2.SQL文作成（INSERT文）
		$sql = "INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,'".$nickname."','".$comment."',now())";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
	
	}
	
	//3.DBの切断
	$dbh = null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
    <form method="post" action="">
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->

</body>
</html>