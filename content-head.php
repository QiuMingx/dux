<html lang="zh">
<head>
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/css/default.css">
	<link href="<?php echo get_stylesheet_directory_uri();?>/css/style.css" rel="stylesheet" />
    <link href="<?php echo get_stylesheet_directory_uri();?>/css/perspectiveRules.css" rel="stylesheet" />
</head>
<body>
	<!-- <header class="htmleaf-header">
		<h1>jQuery超炫图片3D背景视觉差特效插件</span></h1>
		<div class="htmleaf-demo center">
		  <a href="index.html" class="current">DEMO1</a>
		  <a href="index2.html">DEMO2</a>
		  <a href="index3.html">DEMO3</a>
		</div>
	</header> -->

	<div id="demo1">
	    <img alt="background" src="<?php echo get_stylesheet_directory_uri();?>/img/background.jpg" />
	    <div id="particle-target" ></div>
	    <img alt="logo" src="<?php echo get_stylesheet_directory_uri();?>/img/1logo.png" />
	</div>
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.logosDistort.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.particleground.min.js"></script>
	<script>
	    var particles = true,
	        particleDensity,
	        options = {
	            effectWeight: 1,
	            outerBuffer: 1.08,
	            elementDepth: 220
	        };

	    $(document).ready(function() {

	        $("#demo1").logosDistort(options);

	        if (particles) {
	            particleDensity = window.outerWidth * 7.5;
	            if (particleDensity < 13000) {
	                particleDensity = 1200;
	            } else if (particleDensity > 20000) {
	                particleDensity = 1200;
	            }
	            return $('#particle-target').particleground({
	                dotColor: '#1ec5ee',
	                lineColor: '#0a4e90',
	                density: particleDensity.toFixed(0),
	                parallax: false
	            });
	        }
	    });
	</script>
</body>
</html>