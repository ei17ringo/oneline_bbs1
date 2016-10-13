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

	//SQL文の作成(SELECT文)
	$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';

	// SELECT文実行
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//変数にDBから取得したデータを格納

	//格納する変数の初期化
	$posts = array();

	//繰り返し文でデータの取得
	while(1){
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($rec == false){
			//データを最後まで取得したので終了
			break;
		}

		//取得したデータを配列に格納しておく
		$posts[] = $rec;
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
    <!-- <ul>
    	 <li><?php //echo $posts[0]['nickname']; ?> <?php //echo $posts[0]['comment']; ?> 2016/10/13</li>
    	<li>testname 一言つぶやき 2016/10/12</li>
    	<li>テスト太郎 コメント 2016/10/11</li>
    </ul> -->
    <ul>
    	<?php 
    		foreach ($posts as $post_each) {
    			echo '<li>';
    			echo $post_each['nickname'].' ';
    			echo $post_each['comment'].' ';

    			//一旦日付型に変換
    			$created = strtotime($post_each['created']);

    			//書式を変換
    			$created = date('Y/m/d',$created);

    			//echo $post_each['created'];
    			echo $created;
    			echo '</li>';
    		}
    	?>
    </ul>
</body>
</html>