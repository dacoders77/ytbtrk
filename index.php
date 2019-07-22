<?php 

include 'functions.php';

// Получаем ID видео из url-строки
$video_id = getVideoId(''); // Rn3VKA6Ms04

// Получаем данные превью
$data = getPreviewData($video_id);

// Выводим страницу
?><!DOCTYPE html>
<html prefix="og: //ogp.me/ns#">
	<head>
		<!-- Default metategs -->
        <meta charset="UTF-8">
        <title><?= $data['title'] ?></title>
		<meta name="description" content="<?= mb_substr($data['description'], 0, 160) ?>">
		
		<!-- Special metategs -->
		<meta property="og:site_name" content="Youtube">
		<meta property="og:url" content="<?= $data['snippets']['url'] ?>">
		<meta property="og:type" content="video">
		<meta property="og:title" content="<?= $data['title'] ?>">
		<meta property="og:description" content="<?= mb_substr($data['description'], 0, 160) ?>">
		<meta property="og:image" content="<?= $data['snippets']['image'] ?>">
		<meta property="og:image:secure_url" content="<?= $data['snippets']['image'] ?>">
		<meta property="og:image:type" content="image/jpg">
		<meta property="og:image:width" content="<?= $data['snippets']['width'] ?>">
		<meta property="og:image:height" content="<?= $data['snippets']['height'] ?>">
		<meta property="og:video:url" content="<?= $data['snippets']['video'] ?>">
		<meta property="og:video:secure_url" content="<?= $data['snippets']['video'] ?>">
		<meta property="og:video:type" content="text/html">
		<meta property="og:video:width" content="<?= $data['snippets']['width'] ?>">
		<meta property="og:video:height" content="<?= $data['snippets']['height'] ?>">
		
		<!-- Redirect metategs -->
		<noscript>
			<meta http-equiv="refresh" content="10;URL=<?= $data['snippets']['url'] ?>" />
		</noscript>
		
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-MWBDGG9');</script>
		<!-- End Google Tag Manager -->
		
    </head>
    <body>
		
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MWBDGG9"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143000217-2"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-143000217-2');
		</script>
		
		<!-- Redirect script -->
		<script type="text/javascript">
			setTimeout(function(){
				location="<?= $data['snippets']['url'] ?>";
			}, 2000);
		</script>
		
		<p><?= $data['snippets']['url'] ?></p>
		
	</body>
</html>