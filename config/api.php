<?php
	require_once '../config/Connect.php';

	switch( @$_GET['mode'] )
	{
		case 'like':
			$like = $_POST['like'];
			$user = $_SERVER['REMOTE_ADDR'];
			
			$controlLike = $connect->db->prepare("SELECT * FROM begeniler WHERE yaziyi_begenen=:ya_bg AND begenilen_yazi=:bg_yazi");
			$controlLike->execute(
				array(
					'ya_bg' => $user,
					'bg_yazi' => $like
				)
			);
			$count = $controlLike->rowCount();
			
			if( $count != 0 )
			{
				//
			}
			
			else
			{
				$add = $connect->db->prepare("INSERT INTO begeniler SET yaziyi_begenen = ?, begenilen_yazi = ?");
				$ok = $add->execute(
					array(
						$user,
						$like
					)
				);
				
				if( $ok )
				{
					$update = $connect->db->prepare("UPDATE yazilar SET yazi_begeni = yazi_begeni + 1 WHERE yazi_url = ?");
					$okay = $update->execute(
						array(
							$like
						)
					);
					
					if( $okay )
					{
						//
					}
				}
			}
		break;
	}
?>