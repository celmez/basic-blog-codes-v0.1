<style type="text/css">
	* {
		box-sizing: border-box;
		list-style: none;
		font-family: 'helvetica';
	}
</style>
<?php
	require_once '../config/Connect.php';
	
	$json = json_decode( $_COOKIE['login'], true );
?>
<br>
<?php
	$cekTitle = $connect->db->prepare("SELECT * FROM yazilar WHERE yazi_url = ?");
	$cekTitle->execute(
		array(
			@$_GET['blog']
		)
	);
	$count = $cekTitle->rowCount();
		
	if( $count != 0 )
	{
		foreach( $cekTitle as $rowTitle )
		{
			$cekName = $connect->db->prepare("SELECT * FROM uyeler WHERE k_adi = ?");
			$cekName->execute(
				array(
					$rowTitle['yazan_admin_k_adi']
				)
			);
			$counter = $cekName->rowCount();

			if( $counter != 0 )
			{
				foreach( $cekName as $rowName )
				{
?>
<title> <?= $rowName['isim']." - ".$rowTitle['yazi']; ?> </title>
<?php
				}
			}
		}
	}
?>
<?php
	$blog = @$_GET['blog'];
	$user = $_SERVER['REMOTE_ADDR'];
	
	$functions->blogViewer( $user, $blog );
	
	if( $blog )
	{
		$functions->blogControl( $blog );
?>
		<br>
		<br>
		<hr>
		
		<h2> Yorumlar ( <b> <?= $functions->commentCount( $blog ); ?> </b> ) </h2>
		<hr>
		<form action="" method="POST" autocomplete="off">
			<input type="text" name="comment" id="comment" placeholder="Yorumunuz" />
				<br>
				<br>
			<button style="cursor: pointer;" name="send_comment">Gönder</button>
		</form>
		<?php
			$cekUserCek = $connect->db->prepare("SELECT * FROM uyeler WHERE id=:id");
			$cekUserCek->execute(
				array(
					'id' => $json['id']
				)
			);
			$count = $cekUserCek->rowCount();
			
			if( $count != 0 )
			{
				$fetchs = $cekUserCek->fetch( PDO::FETCH_ASSOC );
				$comment = 'comment';
				$nick = $fetchs['k_adi'];
				
				if( isset( $_POST['send_comment'] ) )
				{
					$functions->shareComment( $blog, $nick, $comment );
					$functions->go( SITE_URL.$blog );
				}
			}
		?>
		<hr>
		<?php
			$cekUserNick = $connect->db->prepare("SELECT * FROM uyeler WHERE id=:id");
			$cekUserNick->execute(
				array(
					'id' => $json['id']
				)
			);
			$fetchs = $cekUserNick->fetch( PDO::FETCH_ASSOC );
			$nick = $fetchs['k_adi'];
			
			$functions->showComment( $blog, $nick );
		?>
<?php
	}

	else
	{
		echo '<b>Böyle bir yazı yok</b>';
	}
?>