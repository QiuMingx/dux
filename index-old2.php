<?php 
	get_header(); 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<!-- <iframe id="iframe" style="z-index:1;height:900px;width:100%;position:relative; border-width: 0;margin-top:-15px;" src="http://192.168.3.88/web_effect/3webdemo/" ></iframe> -->
<section class="container" sytle="position:relative;z-index:3;top:0px;">
	<div class="content-wrap">
	<div class="content">
		<?php 
			if( $paged==1 && _hui('focusslide_s') ){ 
				_moloader('mo_slider', false);
				mo_slider('focusslide');
			} 
		?>
		<?php 
			$pagedtext = ''; 
			if( $paged > 1 ){
				$pagedtext = ' <small>第'.$paged.'页</small>';
			}
		?>
		<?php  
			if( _hui('minicat_home_s') ){
				_moloader('mo_minicat');
			}
		?>
		<div class="box box-line mb-20">
    <div class="box-title clearfix ">
        <h3 class="pull-left">今日快讯</h3>
        <a href="<?php echo home_url() . '/lives'?>" class="more pull-right">更多</a>
    </div>
    
    <div class="box-con today-swiper" style="height: 120px">
        <div class="swiper-container ">
            <div class="swiper-wrapper" >
            	<?php $livepost = get_posts( "category=live&numberposts=5" ); ?>
        			<?php if( $livepost ) : ?>
   
          			<?php foreach( $livepost as $livepost) : setup_postdata( $livepost ); ?>
   
          		<div class="swiper-slide " >
            		<div class="media">
            		<article class="excerpt-minic media-body" style="position:relative; border: none; margin: 0 ;padding: 0;">
						<time title="星期三 2018-02-28 15:46:14" data-href="http://192.168.3.66/bit_wp/1730/" style=" display:none;font-size:18px;color:#000;">15:46</time>
						<img style="margin-top: -8px;border-radius: 0;display: none;" src="http://192.168.3.66/bit_wp/wp-content/themes/dux/img/level5.png" height="12">
						<p></p>
						<div class="lives article-content kx_lives">
							<div class="has-content-area" data-url="http://192.168.3.66/bit_wp/?p=1730" data-title="据新华社消息222">
								<div class="clamp line-3"> <?php echo the_content(); ?></div>
            				</div>
						</div>
						<div class="articl_share_btn news-widget  clearfix" style="padding: 0;">
							<span style="display: inline-block;height: 16px; line-height: 16px; padding-top: 6px;"><i class="fa fa-clock-o"></i> <span>2 小时前</span></span> &nbsp; &nbsp;<a class="item share_btn ui-link"> <span>分享</span> </a>						
						</div>
				</article>
				</div>
				</div>
          <?php endforeach; ?>
       
        <?php endif; ?>
          </div>
        </div>
    </div>
</div>
		<?php _the_ads($name='ads_index_01', $class='asb-index asb-index-01') ?>
		<div class="title">
			<h3>
				<?php echo _hui('index_list_title') ? _hui('index_list_title') : '最新发布' ?>
				<?php echo $pagedtext ?>
			</h3>
			<?php 
				if( _hui('index_list_title_r') ){
					echo '<div class="more">'._hui('index_list_title_r').'</div>';
				} 
			?>
		</div>
		<?php 
			$pagenums = get_option( 'posts_per_page', 10 );
			$offsetnums = 0;
			$stickyout = 0;
			if( _hui('home_sticky_s') && in_array(_hui('home_sticky_n'), array('1','2','3','4','5')) && $pagenums>_hui('home_sticky_n') ){
				if( $paged <= 1 ){
					$pagenums -= _hui('home_sticky_n');
					$sticky_ids = get_option('sticky_posts'); rsort( $sticky_ids );
					$args = array(
						'post__in'            => $sticky_ids,
						'showposts'           => _hui('home_sticky_n'),
						'ignore_sticky_posts' => 1
					);
					query_posts($args);
					get_template_part( 'excerpt' );
				}else{
					$offsetnums = _hui('home_sticky_n');
				}
				$stickyout = get_option('sticky_posts');
			}


			$args = array(
				'post__not_in'        => array(),
				'ignore_sticky_posts' => 1,
				'showposts'           => $pagenums,
				'paged'               => $paged
			);
			if( $offsetnums ){
				$args['offset'] = $pagenums*($paged-1) - $offsetnums;
			}
			if( _hui('notinhome') ){
				$pool = array();
				foreach (_hui('notinhome') as $key => $value) {
					if( $value ) $pool[] = $key;
				}
				if( $pool ) $args['cat'] = '-'.implode($pool, ',-');
			}
			if( _hui('notinhome_post') ){
				$pool = _hui('notinhome_post');
				$args['post__not_in'] = explode("\n", $pool);
			}
			if( $stickyout ){
				$args['post__not_in'] = array_merge($stickyout, $args['post__not_in']);
			}
			query_posts($args);
			get_template_part( 'excerpt' ); 
			_moloader('mo_paging');
		?>
		<?php _the_ads($name='ads_index_02', $class='asb-index asb-index-02') ?>
	</div>
	</div>
	<?php get_sidebar(); ?>
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
