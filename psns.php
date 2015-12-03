<?php
require_once('settings.php');

//FacebookOGPやTwitterCardsを生成するクラス
class SNS {
	private $title, $description, $url, $imageurl;
	private $site_name, $site_domain, $twitter_account, $locale;

	public function __construct($title, $description, $imageurl, $url = NULL) {
		global $psqlAlbum;

		$this->title		= $title;
		$this->imageurl         = $imageurl;

		//引数descriptionがNULLなら、全角スペースを代入
		//※descriptionが空だとTwitter Cardsが表示されないため
		if(is_null($description)) {
			$this->description = '　';
		} else {
			$this->description = $description;
		}
		//引数urlが未指定ならリクエストされたURLを代入
		if(is_null($url)) {
			$this->url	= $psqlAlbum['DomainName'] . $_SERVER['REQUEST_URI'];
		} else {
			$this->url	= $url;
		}

		$this->site_name	= $psqlAlbum['AlbumName'];
		$this->twitter_account	= $psqlAlbum['AuthorTwitterAccount'];
		$this->locale		= $psqlAlbum['SiteLangFull'];
		$this->site_domain	= $psqlAlbum['Domain'];
	}

	public function toFacebookOGP($type) {
		switch ($type) {
			case 'article':
			case 'website':
				return
<<<HEREDOC
<!-- Facebook OGP -->
<meta property="og:title" content="$this->title" />
<meta property="og:type" content="$type" />
<meta property="og:description" content="$this->description" />
<meta property="og:url" content="$this->url" />
<meta property="og:image" content="$this->imageurl" />
<meta property="og:site_name" content="$this->site_name" />
<meta property="og:locale" content="$this->locale" />

HEREDOC;
			default:
				return NULL;
		}
	}

	public function toTwitterCards($type, $img0 = NULL, $img1 = NULL, $img2 = NULL, $img3 = NULL) {
		switch ($type) {
			case 'photo':
				return
<<<HEREDOC
<!-- Twitter Card -->
<meta name="twitter:card" content="photo" />
<meta name="twitter:site" content="$this->twitter_account" />
<meta name="twitter:creator" content="$this->twitter_account">
<meta name="twitter:domain" content="$this->site_domain" />
<meta name="twitter:title" content="$this->title" />
<meta name="twitter:description" content="$this->description" />
<meta name="twitter:image" content="$this->imageurl" />
<meta name="twitter:url" content="$this->url" />

HEREDOC;
				break;
			case 'gallery':
				return
<<<HEREDOC
<!-- Twitter Card -->
<meta name="twitter:card" content="gallery" />
<meta name="twitter:site" content="$this->twitter_account" />
<meta name="twitter:creator" content="$this->twitter_account" />
<meta name="twitter:domain" content="$this->site_domain" />
<meta name="twitter:title" content="$this->title" />
<meta name="twitter:description" content="$this->description" />
<meta name="twitter:image" content="$this->imageurl" />
<meta name="twitter:url" content="$this->url" />
<META name="twitter:image0" content="$img0" />
<META name="twitter:image1" content="$img1" />
<META name="twitter:image2" content="$img2" />
<META name="twitter:image3" content="$img3" />

HEREDOC;
				break;
			default:
				return NULL;
				break;
		}
	} 
}
?>
