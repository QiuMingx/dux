<?php get_header(); ?>
<?php if( _hui('breadcrumbs_single_s') && !wp_is_mobile() ){ ?>
	<div class="breadcrumbs">
		<div class="container"><?php echo hui_breadcrumbs() ?></div>
	</div>
<?php } ?>

<section class="container">

	<div class="content-wrap">
	<div class="content">
		<?php while (have_posts()) : the_post(); ?>
		<header class="article-header">
			<h1 class="article-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?><?php echo get_the_subtitle() ?></a></h1>
			<div class="article-meta">
				<span class="item"><?php echo get_the_time('Y-m-d G:i:s'); ?></span>
				<?php _moloader('mo_get_post_from', false); ?>
				<?php if( mo_get_post_from() ){ ?><span class="item" title="来源"><?php echo mo_get_post_from(); ?></span><?php } ?>
				<!-- <span class="item"><?php echo '分类：';the_category(' / '); ?></span> -->
				<?php if( _hui('post_plugin_view') ){ ?><svg width="8px" height="11px" viewBox="0 0 8 11" version="1.1"><path fill="#FA322B" d="M4.41578767,10.5203705 C5.18558945,9.65382726 4.79667918,9.27570397 4.35020132,8.84189025 C3.79385437,8.30115088 3.17307801,7.69788052 3.60112465,6.48850105 C1.89412431,7.87413828 2.54308605,9.50540486 2.93201627,10.2144575 C3.00450434,10.34636 3.06089502,10.4367658 3.09885452,10.4976015 C3.11152098,10.518064 3.1212951,10.5345641 3.14144177,10.5692401 C3.21851769,10.7017143 3.17249954,10.8711512 3.03789594,10.9473634 C2.97171118,10.9848977 2.89577224,10.9928422 2.82731349,10.9752184 L2.82731349,10.9757704 C2.53444891,10.8984544 2.26867263,10.8017994 2.02759094,10.688664 C1.06162853,10.2343681 0.49145819,9.51679924 0.211878432,8.72871547 C-0.0643103053,7.94975902 -0.0522023611,7.10371777 0.142841218,6.3793478 C0.206133646,6.14450171 0.288954354,5.92162167 0.386775359,5.71808052 C0.412108287,5.66461771 0.482282482,5.53612557 0.553613618,5.4133109 C0.609984358,5.31494089 0.668688922,5.21771426 0.710119226,5.15857396 C0.753823512,5.09602324 0.813106547,5.02040254 0.883859212,4.92944477 C1.25090736,4.46034401 1.96316153,3.54946528 1.81591139,2.71024488 C1.78944148,2.55900345 1.89240884,2.41572624 2.04544365,2.38954681 C2.14727403,2.37249469 2.2456735,2.41172441 2.30780897,2.4845064 C2.42057041,2.59764177 2.94470266,3.16394945 3.08217866,4.04015226 C3.27492831,3.66715445 3.41645359,3.25719399 3.51654857,2.84725321 C3.76911983,1.81525519 3.76108113,0.798081647 3.65691692,0.336945118 C3.62356523,0.187418746 3.71905241,0.0395483279 3.87037176,0.00658747322 C3.9537909,-0.0116080294 4.03665149,0.0088545135 4.09994392,0.0560681895 L4.09994392,0.0554964977 C5.92545048,1.41554571 7.29470509,3.48746656 7.77627004,5.46561062 C7.95579471,6.20364198 8.01272398,6.93142239 7.92529547,7.60690305 C7.83555307,8.29716878 7.59622676,8.93225662 7.18485594,9.47128091 C6.65326345,10.1689589 5.83800203,10.6999992 4.69195644,10.97236 C4.54119562,11.0076076 4.38989621,10.9155065 4.35423065,10.7671038 C4.33236855,10.676146 4.35824004,10.5857205 4.41578767,10.5203705 L4.41578767,10.5203705 L4.41578767,10.5203705 Z"></path></svg>&nbsp;<span class="item post-views"><?php echo _get_post_views() ?></span><?php } ?>
				<!-- <span class="item"><?php echo _get_post_comments() ?></span> -->
				<span class="item"><?php edit_post_link('[编辑]'); ?></span>
							
				<div class="articl_share_btn  pull-right"><span class="share_btn">分享</span></div>
				<!-- <a href="###" class="share_btn ui-link" title="分享比特快讯给好友">分享</a> -->
			</div>
		</header>
		<article class="article-content">
			<?php _the_ads($name='ads_post_01', $class='asb-post asb-post-01') ?>
			<?php the_content(); ?>
			<!-- <div class="list"></div> -->

<input type="button" id = "btn" value="加载批注" onclick="loader()" />
		</article>
		<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		<?php if (_hui('ads_post_footer_s')) {
			echo '<div class="asb-post-footer"><b>AD：</b><strong>【' . _hui('ads_post_footer_pretitle') . '】</strong><a'.(_hui('ads_post_footer_link_blank')?' target="_blank"':'').' href="' . _hui('ads_post_footer_link') . '">' . _hui('ads_post_footer_title') . '</a></div>';
		} ?>
		<?php 
		$link = get_post_meta(get_the_ID(), 'link', true);
		if( _hui('post_rewards_s') || (_hui('post_link_single_s')&&$link) ){ ?>
            <div class="post-actions">
            	<?php if( _hui('post_rewards_s') ){ ?><a href="javascript:;" class="action action-rewards" data-event="rewards"><i class="fa fa-jpy"></i> <?php echo _hui('post_rewards_text', '打赏') ?></a><?php } ?>
            	<?php if( _hui('post_link_single_s') && $link ){ 
            		echo '<a class="action action-link" href="'. $link .'"'. (_hui('post_link_blank_s')?' target="_blank"':'') . (_hui('post_link_nofollow_s')?' rel="external nofollow"':'') .'><i class="fa fa-external-link"></i> '._hui('post_link_h1') .'</a>';
            	} ?>
            </div>
        <?php } ?>
		<?php if( _hui('post_copyright_s') ){
			echo '<div class="post-copyright">' . _hui('post_copyright') . '</div>';
		} ?>
		<?php endwhile; ?>

		<?php if( !wp_is_mobile() || (!_hui('m_post_share_s') && wp_is_mobile()) ){ ?>
			<div class="action-share"><?php _moloader('mo_share'); ?></div>
		<?php } ?>

		<div class="article-tags"><?php the_tags('标签：','',''); ?></div>
		
		<?php if( _hui('post_authordesc_s') ){ ?>
		<div class="article-author">
			<?php echo _get_the_avatar(get_the_author_meta('ID'), get_the_author_meta('email')); ?>
			<h4><i class="fa fa-user" aria-hidden="true"></i><a title="查看更多文章" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('nickname'); ?></a></h4>
			<?php echo get_the_author_meta('description'); ?>
		</div>
		<?php } ?>

		<?php if( _hui('post_prevnext_s') ){ ?>
            <nav class="article-nav">
                <span class="article-nav-prev"><?php previous_post_link('上一篇<br>%link'); ?></span>
                <span class="article-nav-next"><?php next_post_link('下一篇<br>%link'); ?></span>
            </nav>
        <?php } ?>

		<?php _the_ads($name='ads_post_02', $class='asb-post asb-post-02') ?>
		<?php 
			if( _hui('post_related_s') ){
				_moloader('mo_posts_related', false); 
				mo_posts_related(_hui('related_title'), _hui('post_related_n'));
			}
		?>
		<?php _the_ads($name='ads_post_03', $class='asb-post asb-post-03') ?>
		<?php comments_template('', true); ?>
	</div>

	</div>
	
	<?php 
		if( has_post_format( 'aside' )){

		}else{
			get_sidebar();
			
		} 
	?>
</section>
<script>
		window.onload = function(){
			var art = document.getElementsByTagName('article')[0];
			var imgs = art.getElementsByTagName('img');
			for(var i = 0; i<imgs.length;i++){
				imgs[i].setAttribute('data-action','zoom')
			}
		}
</script>

<?php get_footer(); ?>

<!-- 分享页面 -->
<?php
		$cars=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
	?>
<div class="art_share_img">
	<div id="express">
		<img src="<?php echo get_stylesheet_directory_uri();?>/img/ZheadSmall.png" class="share_header">
		<div class="share_main">
			<div class="share_main_time">
				<img src="<?php echo get_stylesheet_directory_uri();?>/img/time.png">
				<span style='font-size:16px;'><?php  echo $cars[date('w')].'&nbsp;'.date('Y-m-d H:i:s');  ?></span>
			</div>
			<div class="share_content" style='text-indent: -0.5em;' ><?php echo the_title('<b>【','】</b>').'<br/>'. _get_excerpt().'[扫码阅读全文]' ?></div>
			</br>	
			</br>
			<div class="star_level">
			<span>推荐星级&nbsp;&nbsp;</span><img style="margin-top: -2px" src="<?php echo get_stylesheet_directory_uri();?>/img/level5.png" height="12">
            </div>
			<div class="share_foot">
				<img src="<?php echo get_stylesheet_directory_uri();?>/img/Zfoot.png" class="share_information">
				<div class="share_zx_code" id ="qrcode" data-href="<?php echo get_permalink();?>">
				</div>
			</div>
			<div id ="qrcode"></div>
		</div>
	</div>
	<!---->
	<div id="imgoverlay" ></div>
	<div class="share_imgBox" id="share_imgBox">
		
		<div class="shareImg" id="imges">
		</div>
	
		<div class="explain">请长按图片，将本条快讯推荐给好友</div>
	</div>
</div>