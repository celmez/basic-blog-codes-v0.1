<?php
	require_once '../config/Connect.php';
	
	$admin = @$_GET['admin'];

	$adminControl = $functions->adminControl( $admin );
	
	if( $adminControl )
	{
?>
		<form action="" method="POST" autocomplete="off">
			<input type="text" name="title" id="title" placeholder="Yazı Başlığı" />
			<input type="text" name="text" id="text" placeholder="Yazı" />
			<button style="cursor: pointer;" name="share">Paylaş</button>
		</form>
<?php
		$title = 'title';
		$text = 'text';
		$share = $functions->addBlog( $admin, $title, $text );

		if( $share )
		{
			echo $share;
			header("Refresh: 3, url= http://localhost/yc/");
		}
	}
	
	else
	{
		echo $adminControl();
		$functions->go( SITE_URL, 3 );
	}
?>