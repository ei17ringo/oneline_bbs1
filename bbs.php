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

  //action = deleteがGET送信で送られてきた時
  if (!empty($_GET) && ($_GET['action'] == 'delete')){
    //2.SQL文作成（DELETE文）
    $sql = "DELETE FROM `posts` WHERE `id`=".$_GET['id'];
    //var_dump($sql);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    //二重に実行されないように、最初のURLへリダイレクト
    header('Location: bbs.php');
    exit;
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

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>
      </div>

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
        
        <?php 
        foreach ($posts as $post_each) { 

          //一旦日付型に変換
          $created = strtotime($post_each['created']);

          //書式を変換
          $created = date('Y/m/d',$created);

          ?>

          <article class="timeline-entry">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <i class="fa fa-cogs"></i>
                  </div>
                  <div class="timeline-label">
                      <h2><a href="#"><?php echo $post_each['nickname']; ?></a> <span><?php echo $created; ?></span></h2>
                      <p><?php echo $post_each['comment']; ?></p>
                      <a href="bbs.php?id=<?php echo $post_each['id']; ?>&action=delete"><i class="fa fa-trash"></i></a>
                  </div>
              </div>

          </article>

        
          <?php } ?>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>

        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>



