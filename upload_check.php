<?php
 	session_start();
	session_regenerate_id(true);
	if(isset($_SESSION['login'])==false){
		print'ログインされていません。<br />';
		print'<a href="student_login.html">ログイン画面へ</a>';
		exit();
	}
if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
				$str = "files/".$_SESSION['student_code']."-".$_FILES["file"]["name"];
				$str = mb_convert_encoding($str, "SJIS", "AUTO");
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $str)) {
    echo $_FILES["file"]["name"] . "をアップロードしました。";
  } else {
    echo "ファイルをアップロードできません。";
  }
} else {
  echo "ファイルが選択されていません。";
}
 
?>