<?php
	session_start();
	session_regenerate_id(true);
	if(isset($_SESSION['login'])==false){
		print'ログインされていません。<br />';
		print'<a href="student_login.html">ログイン画面へ</a>';
		exit();
	}

	require_once('common.php');	
	$post=sanitize($_POST);

	$dsn='mysql:dbname=file_upload;host=localhost';
	$user='root';
	$password='tomo8080';
	//$user='koba3327';
	//$password='TBU1314-2';
	$dbh=new PDO($dsn,$user,$password);
	$dbh->query('SET NAMES utf8');

	//自身が採点したかどうか
	$sql='SELECT student_code FROM grading_data1 WHERE student_code=? AND subjects_code=?';
	$stmt=$dbh->prepare($sql);
	$data[0]=$_SESSION['student_code'];
	$data[1]=$_SESSION['subjects_code'];
	$stmt->execute($data);
	$rec=$stmt->fetch(PDO::FETCH_ASSOC);
	if($rec){
		print 'すでに採点しています。';
		print'<input  type="button"onclick="history.back()"value="戻る">';
		exit();
	}

	//テーブルロック
	$sql='LOCK TABLES grading_data1 WRITE';
	$stmt=$dbh->prepare($sql);
	$stmt->execute();

	for($i=1;$i<=3;$i++){
		$sql='INSERT INTO grading_data1(student_code,subjects_code,answer_stu_code,points) VALUES(?,?,?,?)';
		$stmt=$dbh->prepare($sql);
		$data[2]=$post['student'.$i.''];
		$data[3]=$post['grading'.$i.''];
		$stmt->execute($data);
		array_splice($data, 2, 2);
	}

	//テーブルアンロック
	$sql='UNLOCK TABLES';
	$stmt=$dbh->prepare($sql);
	$stmt->execute();
	print '送信しました。<br>';
	print'<a href="top.php">トップへ</a>';
	//データベース切断
	$dbh=null;

	$dir = "C:/xampp/htdocs/file_upload/files2" ;
	//$dir = "./files2" 
	$files = scandir($dir);
	for($i=2;$i<Count($files);$i++){
		$files[$i] = mb_convert_encoding($files[$i], "UTF-8", "SJIS");
		$number=intval(mb_substr($files[$i],0,2));
		if($post['file_num']==$number){
			unlink("files2/".mb_convert_encoding($files[$i-2], "SJIS","UTF-8"));
			unlink("files2/".mb_convert_encoding($files[$i-1], "SJIS","UTF-8"));
			unlink("files2/".mb_convert_encoding($files[$i], "SJIS","UTF-8"));
			break;
		}
	}
?>
</body>
</html>