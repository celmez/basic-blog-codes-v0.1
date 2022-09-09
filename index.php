<?php
	require_once 'config/Connect.php';
	
	if( !isset( $_COOKIE['login'] ) )
	{
?>
		<form action="" method="POST" autocomplete="off">
			<input type="text" name="name" id="name" placeholder="Adınız Soyadınız" />
				<br>
				<br>
			<input type="text" name="nick" id="nick" placeholder="Kullanıcı Adınız" />
				<br>
				<br>
			<input type="text" name="email" id="email" placeholder="E-Posta Adresiniz" />
				<br>
				<br>
			<input type="password" name="password" id="password" placeholder="Parolanız" />
				<br>
				<br>
			<input type="password" name="re_password" id="re-password" placeholder="Parolanızı Yeniden Yazınız" />
				<br>
				<br>
			<button style="cursor: pointer;" name="sign">
				Üye Ol
			</button>
		</form>
		<?php
			if( isset( $_POST['sign'] ) )
			{
				$name = 'name';
				$nick = 'nick';
				$email = 'email';
				$password = 'password';
				$re_password = 're_password';

				echo $functions->signUp( $name, $nick, $email, $password, $re_password );
			}
		?>
		<br>
		<hr>
		<form action="" method="POST" autocomplete="off">
			<input type="text" name="nick" id="nick" placeholder="Kullanıcı Adınız" />
				<br>
				<br>
			<input type="password" name="password" id="password" placeholder="Parolanız" />
				<br>
				<br>
			<button style="cursor: pointer;" name="login">
				Giriş Yap
			</button>
		</form>
<?php
		if( isset( $_POST['login'] ) )
		{
			$nick = 'nick';
			$password = 'password';
			
			echo $functions->signIn( $nick, $password );
		}
	}
	
	elseif( $_COOKIE['login'] )
	{
		$json = json_decode( $_COOKIE['login'], true );
		
?>
		<a href="<?= SITE_URL."admin/".$json['nick']; ?>">
			<?= $json['nick']; ?>
		</a>
<?php
		echo '<br />';
		echo '<a href="'.SITE_URL.'logout?id='.$json["id"].'">Çıkış Yap</a>';
?>
		<br>
		<br>
		<hr>
		<br>
		<br>
<?php
		$functions->showBlog();
	}
?>