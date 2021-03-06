<!DOCTYPE html>
<html lang="pl" id="<?php echo get_slot('htmlId') ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
		<meta name="title" content="Tytuł" >
		<meta name="robots" content="index, follow" >

	    <?php include_http_metas() ?>
	    <?php include_metas() ?>
	    <?php include_title() ?>
	    <link rel="shortcut icon" href="/favicon.ico" />
	    <?php include_stylesheets() ?>
	    <?php include_javascripts() ?>
	</head>
	<body id="<?php echo get_slot('bodyId') ?>">
		<?php echo $sf_content ?>
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-16993226-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>
	</body>
</html>
