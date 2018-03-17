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

<section class = "container">
	<div class = "content-wrap">
	<div class = "content">
	
	<?php _the_ads($name='ads_cat_01', $class='asb-cat asb-cat-01') ?>
	<?php
		$cars=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
	?>
<?php 
		if(in_category('lives')){
					echo '<div class="catleader"><h1>', single_cat_title(), $pagedtext.'</h1>'.'<div class="catleader-desc">'.$description.'</div></div>';
					echo'<div class="time" style="font-family:WenQuanYi Micro Hei;background-color:#fff;color:#555;padding:20px 0;font-size:18px;">
							<div class="time-box" style="margin:0 10px;" >
								<i class="fa fa-clock-o"></i><span>&nbsp;'. date('Y.m.d').'&nbsp;'.'今天'.$cars[date('w')].'</span></div></div>';
					while ( have_posts() ) : the_post(); 
					    echo '<article class="excerpt-minic" style="position:relative;">';
					        // echo '<h2><a'._post_target_blank().' href="'.get_permalink().'" title="'.get_the_title()._get_delimiter().get_bloginfo('name').'">'.get_the_title().'</a></h2>';
					        // echo '<p class="meta" style="float:left;width:85px;border:none;" >';
					

							// echo'<li class="clearfix" data-id="6785">
							// 	<div class="live-date">
							// 		<p class="live-time">18:21</p>
							// 		<p class="star-wrap-new">
							// 			<span class="star-bright"></span>
							// 			<span class="star-bright"></span>
							// 			<span class="star-bright"></span>
							// 			<span class="star-bright"></span>
							// 			<span class="star-dark"></span>
							// 		</p>
							// 	</div>
							// 	<div class="live-info">
							// 	【英国央行放弃推出数字货币】英国央行告诉媒体，
							// 	已经放弃了推出数字货币的计划。银行方面表示，
							// 	推出数字货币有可能会对金融体系造成影响，
							// 	公众可能会停止使用商业银行账户，转而使用英国央行的数字支付系统进行交易，
							// 	这有可能造成经济“动荡”。英国央行最近重申，像比特币这样的数字货币不会对全球金融稳定构成威胁。
							// 	<span>
							// 	<a class="live-link" href="" target="_blank">[查看原文]</a>
							// 	</span>
							// 	</div>
							// 	</li>';

							// 图标
							// <i class="fa fa-clock-o"></i>
							



					        if( _hui('post_plugin_date') ){
					            echo '<time style="font-size:18px;color:#000;">'.get_the_time('G:i').'</time>';
					        }

					        // if( _hui('post_plugin_author') ){
					        //     $author = get_the_author();
					        //     if( _hui('author_link') ){
					        //         $author = '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.$author.'</a>';
					        //     }
					        //     echo '<span class="author"><i class="fa fa-user"></i>'.$author.'</span>';
					        // }

					        // if( _hui('post_plugin_view') ){
					        //     echo '<span class="pv"><i class="fa fa-eye"></i>'._get_post_views().'</span>';
					        // }

					        // if ( comments_open() && _hui('post_plugin_comn') ) {
					        //     echo '<a class="pc" href="'.get_comments_link().'"><i class="fa fa-comments-o"></i>评论('.get_comments_number('0', '1', '%').')</a>';
							// }
							// padding:0;float:left;width:650px;text-align:left;text-indent:0;

					        echo '</p>';

					        echo '<div class="article-content" style="">'; the_content(); echo '</div>';
					    echo '</article>';

					endwhile; 

					_moloader('mo_paging');

					wp_reset_query();




				/* if( mo_is_minicat() ){
					while ( have_posts() ) : the_post(); 
					    echo '<article class="excerpt-minic">';
					        echo '<h2><a'._post_target_blank().' href="'.get_permalink().'" title="'.get_the_title()._get_delimiter().get_bloginfo('name').'">'.get_the_title().'</a></h2>';
					        echo '<p class="meta">';

					        if( _hui('post_plugin_date') ){
					            echo '<time><i class="fa fa-clock-o"></i>'.get_the_time('Y-m-d').'</time>';
					        }

					        if( _hui('post_plugin_author') ){
					            $author = get_the_author();
					            if( _hui('author_link') ){
					                $author = '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.$author.'</a>';
					            }
					            echo '<span class="author"><i class="fa fa-user"></i>'.$author.'</span>';
					        }

					        if( _hui('post_plugin_view') ){
					            echo '<span class="pv"><i class="fa fa-eye"></i>'._get_post_views().'</span>';
					        }

					        if ( comments_open() && _hui('post_plugin_comn') ) {
					            echo '<a class="pc" href="'.get_comments_link().'"><i class="fa fa-comments-o"></i>评论('.get_comments_number('0', '1', '%').')</a>';
					        }

					        echo '</p>';

					        echo '<div class="article-content">'; the_content(); echo '</div>';
					    echo '</article>';

					endwhile; 

					_moloader('mo_paging');

					wp_reset_query();*/
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