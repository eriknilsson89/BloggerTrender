<?php session_start();
//inkluderar kontrollern till skraparn
	require_once("webscraper/masterController.php");
	require_once("facebook/src/facebook.php");
	$mc = new masterController();
	//content innehåller resultatet från skrapningen
	$content = $mc->DoControll();
	$facebook = new Facebook(array(
  		'appId'  => '192220717526062',
  		'secret' => '29e979825a6026adfb5d44389abce5db',
	));
	// Get User ID
	$user = $facebook->getUser();
	if ($user) {
	  try {
	    // Proceed knowing you have a logged in user who's authenticated.
	    $user_profile = $facebook->api('/me');
	  } catch (FacebookApiException $e) {
	    error_log($e);
	    $user = null;
	  }
	}
	// Login or logout url will be needed depending on current user state.
	if ($user) {
	  $logoutUrl = $facebook->getLogoutUrl();
	} else {
	  $loginUrl = $facebook->getLoginUrl();
	}
	
?>
<!doctype html />
<html>
<head>
    <title>BloggTrender</title>
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
    <meta property="fb:app_id" content="{192220717526062}">
    <link rel="Stylesheet" type="text/css" href="basic.css" />
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1&appId=192220717526062";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<section id="content">
	<header>
		<h1>BloggTrender</h1>
	</header>
	
	<?php if ($user) { ?>
		<a href="<?php echo $logoutUrl; ?>" class='logOutLink'>Logout</a>
      <div class="fb-like" data-href="<?php echo $_SESSION['curUrl'] ?>" data-layout="button_count" data-send="true" data-width="450" data-show-faces="true"></div>
		<form method="get" action="">
			<label>Ange en bloggs url</label>
			<p>http://<input type="text" id="searchField" name="searchField" />.blogg.se
			<input type="submit" value="Sök!" id="searchButton" name="searchButton" /></p>
		</form>
		<article id="searchResult">
			<?php echo $content; ?>
		</article>
		<div class="fb-comments" data-href="http://127.0.0.1/BloggerTrender/" data-num-posts="2" data-width="500"></div>		
      
    <?php } else { ?>
    <article id="login-article">
    	<p>Du måste logga in via Facebook för att söka på bloggar!</p>
        <a href="<?php echo $loginUrl; ?>">Logga in med Facebook</a>
    </article>
    <?php } ?>
   
    
	</section>
	
</body>
</html>