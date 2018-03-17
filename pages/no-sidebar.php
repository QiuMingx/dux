<?php 
/**
 * Template name: No sidebar page
 * Description:   A no sidebar page
 */

get_header();

?>
<div class="container container-no-sidebar">
	<div class="content">
		<?php while (have_posts()) : the_post(); ?>
		<header class="article-header">
			<h1 class="article-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
		</header>
		<article class="article-content">
			<?php the_content(); ?>
		</article>
		<?php endwhile;  ?>
		<?php comments_template('', true); ?>
	</div>
</div>

<!-- TradingView Widget BEGIN -->
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/js/tv.js"></script>

<script type="text/javascript">
new TradingView.widget({
  "width": 1200,
  "height": 610,
  "symbol": "COINBASE:BTCUSD",
  "interval": "D",
  "timezone": "Etc/UTC",
  "theme": "Light",
  "style": "1",
  "locale": "zh_CN",
  "toolbar_bg": "#f1f3f6",
  "enable_publishing": false,
  "allow_symbol_change": true,
  "hideideas": false,
  "save_image": false,
"watchlist": [ "ETHUSD",
	"EOSUSD",
	"LTCUSD",
	"BTCUSD",
	"DOGEUSD" ],
});

</script>
<script>
window.onload = function(){
	var test = document.getElementsByClassName('widgetbar-pages');
	console.log(test)
	alert(test.innerHTML);
		// test.style.width = '300px';


}
	

</script>
<!-- TradingView Widget END -->
<!-- TradingView Widget BEGIN -->
<!-- <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script type="text/javascript">
new TradingView.widget({
	"container_id": 'watchlist',
	"width": 998,
	"height": 610,
	"symbol": "NASDAQ:AAPL",
	"interval": "D",
	"timezone": "exchange",
	"theme": "Light",
	"style": "1",
	"toolbar_bg": "#f1f3f6",
	"withdateranges": true,
	"allow_symbol_change": true,
	"save_image": false,
	"watchlist": [ "AAPL",
	"IBM",
	"TSLA",
	"AMD",
	"MSFT",
	"GOOG" ],
	"hideideas": true
});
</script> -->
<!-- TradingView Widget END -->
				

<?php

get_footer();