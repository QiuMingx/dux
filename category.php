<?php 
get_header(); 

// paging
$pagedtext = '';
if( $paged && $paged > 1 ){
	$pagedtext = ' <small>第'.$paged.'页</small>';
}

_moloader('mo_is_minicat', false);

$description = trim(strip_tags(category_description()));
?>

<?php if( mo_is_minicat() ){ ?>
<!-- <div class="pageheader">
<div class = "container" >
	<div class = "share" >
	<?php _moloader('mo_share', false); mo_share('renren'); ?> </div> <h1 > <?php single_cat_title(); echo $pagedtext; ?> 
	</h1> <div class = "note" > <?php echo $description ? $description : '去分类设置中添加分类描述吧' ?> < /div> </div> </div> -->
<?php } ?>

<section class="container">
	<div class="content-wrap">
		<div class="content">

			<?php _the_ads($name='ads_cat_01', $class='asb-cat asb-cat-01') ?>
			<?php
		$cars=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
	?>
			<?php 
		if(in_category('lives')){
					echo '<div class="catleader"><h1>', single_cat_title(), $pagedtext.'</h1>'.'<div class="catleader-desc">'.$description.'</div></div>';
					echo'<div class="time" style="font-family:WenQuanYi Micro Hei;background-color:#fff;color:#555;padding:20px 0;font-size:18px;">
							<div class="time-box" style="margin:0 10px;" >
								<i class="fa fa-clock-o"></i><span>&nbsp;'. date('Y-m-d').'&nbsp;'.'今天'.$cars[date('w')].'</span></div></div>';
					while ( have_posts() ) : the_post(); 
					    echo '<article class="excerpt-minic" style="position:relative;">';
						
					        if( _hui('post_plugin_date') ){
					            echo '<time title="'.get_the_time('l Y-m-d G:i:s').'" data-href="'.get_permalink().'" style="font-size:18px;color:#000;">'.get_the_time('G:i').'</time>';
							}
							if(get_field('starLevel')){
								echo 
                					'<img style="margin-top: -8px;border-radius: 0;" src="'.get_stylesheet_directory_uri().'/img/'.get_field('starLevel').'.png" height="12">';
							}else{
								echo 
									'<img style="margin-top: -8px;border-radius: 0;" src="'.get_stylesheet_directory_uri().'/img/'.'level3.png" height="12">';
							}

					        echo '</p>';
							
							echo '<div class="lives article-content">'; the_content(); echo '</div>';

							echo '<div class="articl_share_btn clearfix">
							
							<span class="share_btn ui-link" title="分享比特评级快讯给好朋友">全文分享</span></div>';
						echo '</article>';
						

					endwhile; 
					// get_template_part( 'lives' ); 
					_moloader('mo_paging');

					wp_reset_query();

				 }else{
					echo '<div class="catleader"><h1>', single_cat_title(), $pagedtext.'</h1>'.'<div class="catleader-desc">'.$description.'</div></div>';
					get_template_part( 'excerpt' ); 
					_moloader('mo_paging');
				}
				?>
		</div>


	</div>
	<?php get_sidebar() ?>
</section>

<?php get_footer(); ?>

<!-- 分享页面 -->

<div class="art_share_img">
	<div id="express">
		<img src="<?php echo get_stylesheet_directory_uri();?>/img/headSmall.png" class="share_header">
		<div class="share_main">
			<div class="share_main_time">
				<img src="<?php echo get_stylesheet_directory_uri();?>/img/time.png">
				<span style='font-size:16px;'><?php echo date('Y-m-d H:i:s')?></span>
			</div>
			<div class="share_content">【日本虚拟货币协会要求成员报告虚拟货币保管状态和管理方法】日本虚拟货币协会（CBA）会长奥山泰全1月26日就日本coincheck交易所被盗事件发出公告，要求所有会员检查报告虚拟货币保管状态及管理方法，以防被盗事件再次发生。</div>
			</br>
			</br>
			<div class="star_level">
                <span>重要程度&nbsp;&nbsp;</span><img style="margin-top: -2px" src="<?php echo get_stylesheet_directory_uri();?>/img/xing.jpg" height="12">
            </div>
			<div class="share_foot">
				<img src="<?php echo get_stylesheet_directory_uri();?>/img/footLeft.png" class="share_information">
				<div class="share_qr_code" id ="qrcode">
					<!-- <img src="<?php echo get_stylesheet_directory_uri();?>/img/footRight.png"> -->
				</div>
			</div>
			<div id ="qrcode"></div>
		</div>
	</div>
	<!---->
	<div id="imgoverlay" ></div>
	<div class="share_imgBox" id="share_imgBox">
		
		<div class="shareImg" id="imges">
			<!-- <div class="pic_share1" style="width: 100%;height: 100%;">
			</div> -->
			<!-- <img src="" class="canvasImg">  -->
		</div>
		<!-- <div class="removeShareImg">
			<img src="<?php echo get_stylesheet_directory_uri();?>/img/close-share.png">
		</div> -->
		<div class="explain">请长按图片，将本条快讯推荐给好友</div>
	</div>
</div>
<script>
	  window.onload = function () {
		  var arts = document.getElementsByClassName('excerpt-minic');
		  for(var i=0;i<arts.length;i++){
			  console.log(arts[i].getElementsByTagName('time')[0].title);
			  var time = arts[i].getElementsByTagName('time')[0].title.split(" ");
			  
			  if(i < arts.length-1 && arts[i+1].getElementsByTagName('time')[0].title.split(" ")[1] !=time[1] ){
				  var p = document.createElement('div');
				  p.innerHTML = '<div class="time" style="font-family:WenQuanYi Micro Hei;background-color:#fff;color:#555;padding:20px 0;font-size:18px;">'
							+'<div class="time-box" style="margin:0 10px;" >'+
								'<i class="fa fa-clock-o"></i><span>&nbsp;'+arts[i+1].getElementsByTagName('time')[0].title.split(" ")[1].replace(/-/g,".")+' '+arts[i+1].getElementsByTagName('time')[0].title.split(" ")[0]+'</span></div>';
				  arts[i+1].insertBefore(p,arts[i+1].getElementsByTagName('time')[0]);

			  }

		  }

	  }

</script>