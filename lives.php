<?php
/**
 * Used for index/archive/search/author/catgory/tag.
 *
 */
// 定义或图标
$fire ='<svg width="8px" height="11px" viewBox="0 0 8 11" version="1.1"><path fill="#FA322B" d="M4.41578767,10.5203705 C5.18558945,9.65382726 4.79667918,9.27570397 4.35020132,8.84189025 C3.79385437,8.30115088 3.17307801,7.69788052 3.60112465,6.48850105 C1.89412431,7.87413828 2.54308605,9.50540486 2.93201627,10.2144575 C3.00450434,10.34636 3.06089502,10.4367658 3.09885452,10.4976015 C3.11152098,10.518064 3.1212951,10.5345641 3.14144177,10.5692401 C3.21851769,10.7017143 3.17249954,10.8711512 3.03789594,10.9473634 C2.97171118,10.9848977 2.89577224,10.9928422 2.82731349,10.9752184 L2.82731349,10.9757704 C2.53444891,10.8984544 2.26867263,10.8017994 2.02759094,10.688664 C1.06162853,10.2343681 0.49145819,9.51679924 0.211878432,8.72871547 C-0.0643103053,7.94975902 -0.0522023611,7.10371777 0.142841218,6.3793478 C0.206133646,6.14450171 0.288954354,5.92162167 0.386775359,5.71808052 C0.412108287,5.66461771 0.482282482,5.53612557 0.553613618,5.4133109 C0.609984358,5.31494089 0.668688922,5.21771426 0.710119226,5.15857396 C0.753823512,5.09602324 0.813106547,5.02040254 0.883859212,4.92944477 C1.25090736,4.46034401 1.96316153,3.54946528 1.81591139,2.71024488 C1.78944148,2.55900345 1.89240884,2.41572624 2.04544365,2.38954681 C2.14727403,2.37249469 2.2456735,2.41172441 2.30780897,2.4845064 C2.42057041,2.59764177 2.94470266,3.16394945 3.08217866,4.04015226 C3.27492831,3.66715445 3.41645359,3.25719399 3.51654857,2.84725321 C3.76911983,1.81525519 3.76108113,0.798081647 3.65691692,0.336945118 C3.62356523,0.187418746 3.71905241,0.0395483279 3.87037176,0.00658747322 C3.9537909,-0.0116080294 4.03665149,0.0088545135 4.09994392,0.0560681895 L4.09994392,0.0554964977 C5.92545048,1.41554571 7.29470509,3.48746656 7.77627004,5.46561062 C7.95579471,6.20364198 8.01272398,6.93142239 7.92529547,7.60690305 C7.83555307,8.29716878 7.59622676,8.93225662 7.18485594,9.47128091 C6.65326345,10.1689589 5.83800203,10.6999992 4.69195644,10.97236 C4.54119562,11.0076076 4.38989621,10.9155065 4.35423065,10.7671038 C4.33236855,10.676146 4.35824004,10.5857205 4.41578767,10.5203705 L4.41578767,10.5203705 L4.41578767,10.5203705 Z"></path></svg>';
$ii = 0;
while ( have_posts() ) : the_post(); 

    $_thumb = _get_post_thumbnail();

    $_excerpt_text = '';
    if( _hui('list_type')=='text' || (_hui('list_type') == 'thumb_if_has' && strstr($_thumb, 'data-thumb="default"')) ){
        $_excerpt_text .= ' excerpt-text';
    }
    if( _hui('home_sticky_s') && is_sticky() ){
        $_excerpt_text .= ' excerpt-sticky';
    }

    $ii++;
   
    echo '<article class="excerpt-minic excerpt-'.$ii. $_excerpt_text .'" style="position:relative;">';

        // if( _hui('list_type') == 'thumb' ){
        //     echo '<a'._post_target_blank().' class="focus" href="'.get_permalink().'">'.$_thumb.'</a>';
        // }else if( _hui('list_type') == 'thumb_if_has' && !strstr($_thumb, 'data-thumb="default"') ){
        //     echo '<a'._post_target_blank().' class="focus" href="'.get_permalink().'">'.$_thumb.'</a>';
        // }
        echo '<div style ="position:relative;">';
        if( _hui('post_plugin_cat') && !is_category() ) {
            $category = get_the_category();
            if($category[0]){
                echo '<a class="cat" href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'<i></i></a> ';
            }
        };
        echo '</div>';
        echo '<header>';
        // 调整栏目图标
            // if( _hui('post_plugin_cat') && !is_category() ) {
            //     $category = get_the_category();
            //     if($category[0]){
            //         echo '<a class="cat" href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'<i></i></a> ';
            //     }
            // };
            // echo '<h2><a'._post_target_blank().' href="'.get_permalink().'" title="'.get_the_title().get_the_subtitle(false)._get_delimiter().get_bloginfo('name').'">'.get_the_title().get_the_subtitle().'</a></h2>';
            // if( _hui('home_sticky_s') && is_sticky() ){
            //     echo '<span class="sticky-icon">置顶</span>';
            // }
        echo '</header>';
       
						
					        if( _hui('post_plugin_date') ){
					            echo '<time title="'.get_the_time('l Y-m-d G:i:s').'" data-href="'.get_permalink().'" style="font-size:18px;color:#000;">'.get_the_time('G:i').'</time>';
							}
							echo 
                				'<img style="margin-top: -8px;border-radius: 0;" src="'.get_stylesheet_directory_uri().'/img/'.get_field('starLevel').'.png" height="12">';
            			

					        echo '</p>';
							
							echo '<div class="lives article-content">'; the_content(); echo '</div>';

							echo '<div class="articl_share_btn clearfix">
							
							<a href="###" class="share_btn ui-link" title="分享比特评级快讯给好朋友">全文分享</a></div>';
				
        if( _hui('post_link_excerpt_s') ) _moloader('mo_post_link');

    echo '</article>';

endwhile; 

wp_reset_query();