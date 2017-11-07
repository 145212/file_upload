<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
	print'ログインされていません。<br />';
	print'<a href="student_login.html">ログイン画面へ</a>';
	exit();
}else{
	print'ログイン中　　';
	print'<a href="student_logout.php">ログアウト</a><br />';
	print'<br />';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ファイルのアップロード</title>
</head>
<body>
<?php

	$_SESSION['subjects_code']=1;
	$dsn='mysql:dbname=file_upload;host=localhost';
	$user='root';
	$password='tomo8080';
	//$user='koba3327';
	//$password='TBU1314-2';
	$dbh=new PDO($dsn,$user,$password);
	$dbh->query('SET NAMES utf8');

	//自身が採点したかどうか
	$sql='SELECT student_code FROM grading_data1 WHERE student_code=?';
	$stmt=$dbh->prepare($sql);
	$data[0]=$_SESSION['student_code'];
	$stmt->execute($data);
	$rec=$stmt->fetch(PDO::FETCH_ASSOC);
	//データベース切断
	$dbh=null;
	print'トップメニュー<br />';
	print'<br />';
	print'<a href="upload.php">アップロード</a><br />';
	print'<br />';
	if($rec){
		print'<a href="grading.php">採点済</a><br>';
	}else{
		print'<a href="grading.php">採点する</a><br>';
	}
?>
</body>
</html>