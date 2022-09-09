<?php
	require_once 'config/Connect.php';
	
	if( !isset( $_COOKIE['login'] ) )
	{
		$functions->go( SITE_URL );
		exit();
	}
	
	$json = json_decode( $_COOKIE['login'], true );
	$id = @$_GET['id'];

	echo $functions->signOut( $id, $json['id'] );
?>