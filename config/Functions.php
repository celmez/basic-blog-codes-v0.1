<style type="text/css">
	* {
		text-decoration: none;
	}
	.alert-danger {
		border: 1px solid #c80b30;
		background-color: #c80b30;
		color: #f3f4f5;
		padding: 10px 20px;
		user-select: none;
		-ms-user-select: none;
		-moz-user-select: none;
		-webkit-user-select: none;
		font-family: 'helvetica';
		text-align: center;
	}
	.alert-success {
		border: 1px solid #4db732;
		background-color: #4db732;
		color: #f3f4f5;
		padding: 10px 20px;
		user-select: none;
		-ms-user-select: none;
		-moz-user-select: none;
		-webkit-user-select: none;
		font-family: 'helvetica';
		text-align: center;
	}
</style>
<?php
	define( 'KEY' , 'yc+_+blog+_+cype+_+wall+_+2022+-*' );
	define( 'CIPHER', 'AES-128-ECB' );

	class Functions extends Connect
    {
		public function encrypt( $data )
		{
			return openssl_encrypt( $data, CIPHER, KEY );
		}
		
		public function decrypt( $data )
		{
			return openssl_decrypt( $data, CIPHER, KEY );
		}

        public function formControl( $value )
        {
            return strip_tags(trim(htmlspecialchars( $_POST[$value] )));
        }

        public function sef( $seo )
        {
            $seo = mb_strtolower( $seo, 'UTF-8' );
            $seo = str_replace(
                [ 'ı', 'ö', 'ç', 'ş', 'ü', 'ğ' ],
                [ 'i', 'o', 'c', 's', 'u', 'g' ],
                $seo
            );
            $seo = preg_replace( '/[^a-z0-9]/', '-', $seo );
            $seo = preg_replace( '/-+/', '-', $seo );

            return trim( $seo, '-' );
        }
		
		public function setCookie( $cookieName, $cookieArray = [], $cookieTime )
        {
            setcookie( $cookieName, $cookieArray, $cookieTime, '/' );
        }

        public function deleteCookie( $cookieNameDelete, $cookieArrayDelete = [], $cookieTimeDelete )
        {
            setcookie( $cookieNameDelete, $cookieArrayDelete, $cookieTimeDelete, '/' );
        }
		
		public function go( $url, $time = 0 )
		{
			if( $time != 0 )
			{
				header("Refresh: $url; url= $url");
			}
			
			else
			{
				header("Location: $url");
			}
		}

        public function addBlog( $addBlogAdmin, $blogTitle, $blogText )
        {
            if( isset( $_POST['share'] ) )
            {
                $title = $this->formControl( $blogTitle );
                $text = $this->formControl( $blogText );
                $url = $this->sef( $title );
                $date = date("d.m.Y H:i:s");

                if( empty( $title ) || empty( $text ) )
                {
                    return $dataError = '<div class="alert-danger">Yazı paylaşmak için lütfen bütün alanları doldurunuz!!!</div><br />';
                    exit();
                }

                else
                {
                    $controlAdmin = $this->db->prepare("SELECT * FROM admin WHERE admin_k_adi=:a_k_adi");
                    $controlAdmin->execute(
                        array(
                            'a_k_adi' => $addBlogAdmin
                        )
                    );
                    $count = $controlAdmin->rowCount();

                    if( $count != 0 )
                    {
                        $controlText = $this->db->prepare("SELECT * FROM yazilar WHERE yazi_url=:text_uri ");
						$controlText->execute(
							array(
								'text_uri' => $url
							)
						);
						$counterText = $controlText->rowCount();
						
						if( $counterText == 0 )
						{
							$send = $this->db->prepare("INSERT INTO yazilar SET yazan_admin_k_adi = ?, yazi = ?, yazi_baslik = ?, yazi_url = ?, yazi_tarihi = ?");
							$ok = $send->execute(
								array(
									$addBlogAdmin,
									$text,
									$title,
									$url,
									$date
								)
							);

							if( $ok )
							{
								return $dataSuccess = '<div class="alert-success">Yazınız başarılı bir şekilde paylaşıldı</div><br />';
							}

							else
							{
								return $dataError = '<div class="alert-danger">Yazınız paylaşılırken bir hata oluştu!!!</div><br />';
								exit();
							}
						}
						
						else
						{
							return $dataError = '<div class="alert-danger"><b>Böyle bir yazı olduğu için maalesef bir daha paylaşamazsınız!!!</b></div><br />';
							exit();
						}
                    }

                    else
                    {
                        return $dataError = 'Maalesef admin olmadığınız için paylaşım yapamazsınız!!!';
                        exit();
                    }
                }
            }
        }
        public function showBlog()
        {
            $cek = $this->db->prepare("SELECT * FROM yazilar ORDER BY ID DESC");
            $cek->execute();
            $counter = $cek->rowCount();

            if( $counter != 0 )
            {
                foreach( $cek as $row )
                {
?>
                    <ul>
                        <li>
                            <a href="#">
                                <?= $row['yazan_admin_k_adi']; ?>
                            </a>
                        </li>
                        <li>
                            <h2>
                                <?= $row['yazi_baslik']; ?>
                            </h2>
                        </li>
                        <li>
                            <?= $row['yazi_tarihi']; ?>
                        </li>
						<li>
							<p title="<?= $row['yazi_goruntulenme']." görüntülenme"; ?>"> <?= $row['yazi_goruntulenme']." görüntülenme"; ?> </p>
						</li>
						<br>
						<li>
                            <a title="<?= $row['yazi_baslik']; ?>" href="<?= SITE_URL.$row['yazi_url']; ?>">
                                <?= 'Yazıya Git'; ?>
                            </a>
                        </li>
                    </ul>
					<br>
					<hr>
<?php
                }
            }

            else
            {
                return $dataError = 'Henüz bir yazı yok!!!';
                exit();
            }
        }
		
		public function blogControl( $blogUrl )
		{
			$controlBlog = $this->db->prepare("SELECT * FROM yazilar WHERE yazi_url=:text_url");
			$controlBlog->execute(
				array(
					'text_url' => $blogUrl
				)
			);
			$count = $controlBlog->rowCount();
			
			if( $count != 0 )
			{
				foreach( $controlBlog as $blogControl )
				{
?>
					<ul>
						<li>
							<h2>
								<?= $blogControl['yazi_baslik']; ?>
							</h2>
						</li>
						<li>
							<p>
								<?= $blogControl['yazi']; ?>
							</p>
						</li>
						<li>
							<p> <?= $blogControl['yazi_tarihi']; ?> </p>
						</li>
						<br>
						<li>
							<p> <?= $blogControl['yazi_begeni']." beğeni"; ?> </p>
							<p title="<?= $blogControl['yazi_goruntulenme']." görüntülenme"; ?>"> <?= $blogControl['yazi_goruntulenme']." görüntülenme"; ?> </p>
						</li>
						<br>
						<li>
							<button style="cursor: pointer;" class="like" id="<?= $blogControl['yazi_url']; ?>">
								Beğen
							</button>
						</li>
						<br>
						<br>
						<li>
							<span> Yazan:  </span>
							<a href="<?= SITE_URL.$blogControl['yazan_admin_k_adi']; ?>">
								<?= "@".$blogControl['yazan_admin_k_adi']; ?>
							</a>
						</li>
					</ul>

					<script type="text/javascript">
						$(document).ready(function()
						{
							$('.like').click(function()
							{
								var like = $(this).attr('id')
								var data = "like="+like
								
								$.ajax
								(
									{
										type: 'POST',
										url: 'http://localhost/yc/config/api.php?mode=like',
										data: data,
										success: function(e)
										{
											window.location.href = 'http://localhost/yc/'+like
										}
									}
								)
							})
						})
					</script>
<?php
				}
			}
		}

		public function shareComment( $commentUrl, $commentUser, $comme )
		{
			$comment = $this->formControl( $comme );
			$date = date("d.m.Y H:i:s");
				
			if( empty( $comment ) || empty( $commentUser ) )
			{
				return $dataError = '<div class="alert-danger">Yorum paylaşmak için lütfen bütün alanları doldurunuz!!!</div>';
				exit();
			}
			
			else
			{
				$controlComment = $this->db->prepare("SELECT * FROM yazilar WHERE yazi_url=:y_y_u");
				$controlComment->execute(
					array(
						'y_y_u' => $commentUrl
					)
				);
				$count = $controlComment->rowCount();

				if( $count != 0 )
				{
					$doCom = $this->db->prepare("INSERT INTO yorumlar SET yorum_yapan_k_adi = ?, yorum_yapilan_yazi = ?, yorum = ?, yorum_tarihi = ?");
					$okay = $doCom->execute(
						array(
							$commentUser,
							$commentUrl,
							$comment,
							$date
						)
					);
						
					if( $okay )
					{
						$doComUpdate = $this->db->prepare("UPDATE yazilar SET yazi_yorum = yazi_yorum + 1 WHERE yazi_url = ?");
						$doComUpdate->execute(
							array(
								$commentUrl
							)
						);
						return $dataSuccess = '<div class="alert-success">Başarılı bir şekilde yorum yaptınız...</div>';
					}
						
					else
					{
						return $dataError = '<div class="alert-danger">Yorum yaparken beklenmedik bir hata oluştu!!!</div>';
						exit();
					}
				}
			}
		}

		public function showComment( $doCommentUrl, $iamnot )
		{
			$cekComment = $this->db->prepare("SELECT * FROM yorumlar WHERE yorum_yapilan_yazi=:y_y_y ORDER BY ID DESC");
			$cekComment->execute(
				array(
					'y_y_y' => $doCommentUrl
				)
			);
			$count = $cekComment->rowCount();
			
			if( $count != 0 )
			{
				foreach( $cekComment as $rowComment )
				{
?>
					<ul>
						<li>
							<p>
								<?php if( $rowComment['yorum_yapan_k_adi'] == $iamnot ) { ?>
									<b> <?= $rowComment['yorum_yapan_k_adi']; ?> </b>
								<?php } else { ?>
									<?= $rowComment['yorum_yapan_k_adi']; ?>
								<?php } ?>
							</p>
						</li>
						<li>
							<p> <?= $rowComment['yorum']; ?> </p>
						</li>
						<li>
							<p> <?= $rowComment['yorum_tarihi']; ?> </p>
						</li>
					</ul>
					<hr>
<?php
				}
			}
		}
		
		public function commentCount( $commentNumber )
		{
			$theComment = $this->db->prepare("SELECT * FROM yorumlar WHERE yorum_yapilan_yazi=:y_y_y");
			$theComment->execute(
				array(
					'y_y_y' => $commentNumber
				)
			);
			$count = $theComment->rowCount();
			
			echo $count;
		}
		
		public function blogViewer( $viewUser, $viewsBlog )
		{
			$controlViewer = $this->db->prepare("SELECT * FROM goruntulenmeler WHERE goruntuleyen_k_adi=:g_k_adi AND goruntulenen_yazi=:g_y");
			$controlViewer->execute(
				array(
					'g_k_adi' => $viewUser,
					'g_y' => $viewsBlog
				)
			);
			$count = $controlViewer->rowCount();
			
			if( $count != 0 )
			{
				//
			}
			
			else
			{
				$updateShow = $this->db->prepare("INSERT INTO goruntulenmeler SET goruntuleyen_k_adi = ?, goruntulenen_yazi = ?");
				$ok = $updateShow->execute(
					array(
						$viewUser,
						$viewsBlog
					)
				);
				
				if( $ok )
				{
					$updateShow2 = $this->db->prepare("UPDATE yazilar SET yazi_goruntulenme = yazi_goruntulenme+1 WHERE yazi_url = ?");
					$okay = $updateShow2->execute(
						array(
							$viewsBlog
						)
					);
					
					if( $okay )
					{
						//
					}
				}
			}
		}
		
		public function signUp( $signName, $signNick, $signEmail, $signPassword, $signRePassword )
		{
			$name = $this->formControl( $signName );
			$nick = $this->formControl( $signNick );
			$email = $this->formControl( $signEmail );
			$password = $this->formControl( $signPassword );
			$re_password = $this->formControl( $signRePassword );
			$hash = $this->encrypt( $password );
			$date = date("d.m.Y H:i:s");

			if( empty( $name ) || empty( $nick ) || empty( $email ) || empty( $password ) || empty( $re_password ) )
			{
				return $dataError = '<div class="alert-danger">Üye olmak için lütfen bütün alanları doldurunuz!!!</div><br />';
				exit();
			}

			elseif( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
			{
				return $dataError = '<div class="alert-danger">Üye olmak için girmiş olduğunuz e-posta adresiniz uygun formatta değil ( "Örnek: ornek@gmail.com" )</div>br />';
				exit();
			}

			elseif( $password != $re_password )
			{
				return $dataError = '<div class="alert-danger">Girmiş olduğunuz parolalar birbirleriyle maalesef uyuşmamakta!!!</div><br />';
				exit();
			}
				
			else
			{
				$sign = $this->db->prepare("SELECT * FROM uyeler WHERE eposta=:email OR k_adi=:nick");
				$sign->execute(
					array(
						'email' => $email,
						'nick' => $nick
					)
				);
				$count = $sign->rowCount();
					
				if( $count != 0 )
				{
					return $dataError = '<div class="alert-danger">Maalesef bu bilgilere ait bir kullanıcı blog sistemimizde kayıtlı!!!</div>br />';
					exit();
				}
					
				else
				{
					$add = $this->db->prepare("INSERT INTO uyeler SET isim = ?, k_adi = ?, eposta = ?, sifre = ?, tarih = ?");
					$ok = $add->execute(
						array(
							$name,
							$nick,
							$email,
							$hash,
							$date
						)
					);
					
					$login = $this->db->query("SELECT * FROM uyeler WHERE k_adi = '$nick' AND sifre = '$hash' ")->fetch();
					
					if( $ok )
					{
						$cookieArray = array( 'id' => $login['id'], 'name' => $name, 'nick' => $nick, 'email' => $email, 'password' => $hash );
						$cookieArray = json_encode( $cookieArray );
						
						$this->setCookie( 'login', $cookieArray, time()+60*60*24*7 );
						
						return $goes = $this->go( SITE_URL );
					}
					
					else
					{
						return $dataError = '<div class="alert-danger">Maalesef beklenmedik bir hata oluştu!!!</div><br />';
						exit();
					}
				}
			}
		}
		
		public function signIn( $loginNick, $loginPassword )
		{
			$nick = $this->formControl( $loginNick );
			$password = $this->formControl( $loginPassword );
			$hash = $this->encrypt( $password );
			
			if( empty( $nick ) || empty( $password ) )
			{
				return $dataError = '<div class="alert-danger">Giriş yapmak için lütfen bütün alanları doldurunuz!!!</div><br />';
				exit();
			}

			else
			{
				$login = $this->db->query("SELECT * FROM uyeler WHERE k_adi = '$nick' AND sifre = '$hash' ")->fetch();
				
				if( $login )
				{
					$cookieArray = array( 'id' => $login['id'], 'name' => $login['isim'], 'nick' => $nick, 'email' => $login['eposta'], 'password' => $hash );
					$cookieArray = json_encode( $cookieArray );

					$this->setCookie( 'login', $cookieArray, time()+60*60*24*7 );

					return $goes = $this->go( SITE_URL );
				}
				
				else
				{
					return $dataError = '<div class="alert-danger">Maalesef beklenmedik bir hata oluştu!!!</div><br />';
					exit();
				}
			}
		}
		
		public function signOut( $userId, $userId2 )
		{
			$userLogoutControl = $this->db->prepare("SELECT * FROM uyeler WHERE id=:id");
			$userLogoutControl->execute(
				array(
					'id' => $userId
				)
			);
			$count = $userLogoutControl->rowCount();
			
			if( $count != 0 )
			{
				if( $userId == $userId2 )
				{
					$this->deleteCookie( 'login', '', time()-60*60*24*7 );
					return $dataSuccess = '<div class="alert-success">Çıkış işleminiz başarılı bir şekilde gerçekleştirilmiştir, ana sayfaya yönlendiriliyorsunuz...</div>';
					$this->go( SITE_URL, 3 );
					exit();
				}
				
				else
				{
					return $dataError = '<div class="alert-danger">Maalesef bu kullanıcı siz değilsiniz!!!</div>';
					exit();
				}
			}
			
			else
			{
				return $dataError = '<div class="alert-danger">Maalesef böyle bir kullanıcı yok</div>';
				exit();
			}
		}
		
		public function adminControl( $userId )
		{
			$control = $this->db->prepare("SELECT * FROM admin WHERE admin_k_adi=:nick");
			$control->execute(
				array(
					'nick' => $userId
				)
			);
			$count = $control->rowCount();
			
			if( $count != 0 )
			{
				return true;
			}
			
			else
			{
				return $dataError = '<div class="alert-danger">Maalesef böyle bir admin yok!!!</div>';
			}
		}
    }
?>
<script type="text/javascript" src="http://localhost/yc/jquery-3.6.1.js"></script>