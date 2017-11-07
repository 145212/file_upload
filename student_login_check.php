<?php
try {

	require_once('common.php');

	$post=sanitize($_POST);
	$student_code=$post['code'];
	$pass=$post['pass'];
/*
	//接続DB指定
	$ldapHost = "10.3.200.21";
	$basedn = "ou=user,dc=bunri-u,dc=ac,dc=jp";
	$filter = "(uid=" . $student_code . ")";

	$conn = ldap_connect( "$ldapHost" );
	if( !$conn ){
		// サーバへの接続に失敗
		echo "LDAPサーバに接続できません。";
		exit;
	}

	// ユーザを検索するために匿名でバインドする
	$ldapBind = ldap_bind( $conn );

	// ユーザの検索をする
	$result = ldap_search( $conn, $basedn, $filter );
	$info = ldap_get_entries( $conn, $result );

	if( !$info[ 'count' ]){
			
	// ユーザの検索に失敗
	// デモなのでエラーの詳細を出していますが、
	// 不正アタック対策のため「認証できませんでした」
	// にするのが無難です
	//学籍番号とパスワードが違う場合または学籍番号のみ違う場合
	  	print '<div id="wrap">';
				echo "<h3>学籍番号かパスワードが間違っています。</h3>";
				print ' <p class="back"><a href="student_login.html">戻る</a></p>';
		print '</div>';
				return false;
			exit;
	}

	// ユーザのDNを取得する
	// 複数個あっても１つ目だけを利用する。
	$userdn = $info[ 0 ][ 'dn' ];

	// ユーザのDNを使って認証開始
	$authBind = ldap_bind( $conn, $userdn, $pass );

	if( $authBind ){
		$userattr = ldap_explode_dn( $userdn, 1 );
		$userou = $userattr[ 1 ];

		if( $userou == "student" ){
*/			session_start(); //セッションを開始
			$_SESSION['login']=1;
			$_SESSION['student_code']=$student_code;
/*
			//データベースアクセス
			$dsn='mysql:dbname=web_questionnaire;host=localhost';
			$user='root';
			$password='tomo8080';
			//$user='koba3327';
			//$password='TBU1314-2';
			$dbh=new PDO($dsn,$user,$password);
			$dbh->query('SET NAMES utf8');

			//生徒名取得
			$sql='SELECT student_name FROM student_data WHERE student_code=?';
			$stmt=$dbh->prepare($sql);
			$data[0]=$_SESSION['student_code'];
			$stmt->execute($data);
			$rec=$stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['student_name']=$rec['student_name'];	
*/
			header("Location: top.php");
/*	
			return true;
		}
	}else{
	print '<div id="wrap">';
		echo "<h3>学籍番号かパスワードが間違っています。</h3>";
		print ' <p class="back"><a href="student_login.html">戻る</a></p>';
		print '</div>';
	return false;
	}
*/
}catch (Exception $e){
	print'ただいま障害により大変ご迷惑をおかけしております。';
	exit();
}

?>