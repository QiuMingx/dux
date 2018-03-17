<?php if( _hui('layout') == '1' ) return; ?>
<div class="sidebar">
<?php 
	_moloader('mo_notice', false);
	
	if (function_exists('dynamic_sidebar')){
		dynamic_sidebar('gheader'); 

		if (is_home()){
			 
			echo '<div class="widget_text widget widget_custom_html">';
				echo '<h3>快讯</h3>';
				echo '<div class="textwidget custom-html-widget">';
					echo '<ul class="kx_index scrollbar" >';
						query_posts( 'showposts=10&cat=97'  );
			      while ( have_posts() ) : the_post(); 
							echo '<li>'	;	
								echo '<div class="clamp line-3">';
								the_content();
								echo '</div>';
								echo '<div class="kx-widget">';
									echo '<div class="item">';
										echo '<i class="fa fa-clock-o"></i>';
					 					echo '<span>'.get_the_time('G:i').'</span> ';
									echo '</div>';
								echo '</div>';
							echo '</li>';
		             endwhile; 
		         wp_reset_query();
					echo '</ul>';
				echo '</div>';
			echo '</div>';
			dynamic_sidebar('home');
		}
		elseif (is_category()){
			dynamic_sidebar('cat'); 
		}
		else if (is_tag() ){
			dynamic_sidebar('tag'); 
		}
		else if (is_search()){
			dynamic_sidebar('search'); 
		}
		else if (is_single()){
			dynamic_sidebar('single'); 
		}

		dynamic_sidebar('gfooter');
	} 
?>
	
</div>
<style>
	/*定义滚动条宽高及背景，宽高分别对应横竖滚动条的尺寸*/
.scrollbar::-webkit-scrollbar{
    width: 3px;
    height: 6px;
    background-color: #f5f5f5;
}
/*定义滚动条的轨道，内阴影及圆角*/
.scrollbar::-webkit-scrollbar-track{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    border-radius: 10px;
    background-color: #f5f5f5;
}
/*定义滑块，内阴影及圆角*/
.scrollbar::-webkit-scrollbar-thumb{
    /*width: 10px;*/
    height: 20px;
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}


</style>


            