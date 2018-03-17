<?php

/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/



define( 'THEME_VERSION' , '4.0' );




// require widgets
require_once get_stylesheet_directory() . '/widgets/widget-index.php';





// require functions for admin
if( is_admin() ){
    require_once get_stylesheet_directory() . '/functions-admin.php';
}


// add link manager
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// delete wp_head code
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');



// WordPress Emoji Delete
remove_action( 'admin_print_scripts' ,	'print_emoji_detection_script');
remove_action( 'admin_print_styles'  ,	'print_emoji_styles');
remove_action( 'wp_head'             ,	'print_emoji_detection_script',	7);
remove_action( 'wp_print_styles'     ,	'print_emoji_styles');
remove_filter( 'the_content_feed'    ,	'wp_staticize_emoji');
remove_filter( 'comment_text_rss'    ,	'wp_staticize_emoji');
remove_filter( 'wp_mail'             ,	'wp_staticize_emoji_for_email');




add_theme_support( 'post-formats', array( 'aside' ) ); 



// post thumbnail
if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(220, 150, true );
}

// hide admin bar
add_filter('show_admin_bar', 'hide_admin_bar');
function hide_admin_bar($flag) {
	return false;
}

// no self Pingback
add_action('pre_ping', '_noself_ping');
function _noself_ping(&$links) {
	$home = get_option('home');
	foreach ($links as $l => $link) {
		if (0 === strpos($link, $home)) {
			unset($links[$l]);
		}
	}
}

// reg nav
if (function_exists('register_nav_menus')){
    register_nav_menus( array(
        'nav' => __('网站导航', 'haoui'),
        'topmenu' => __('顶部菜单', 'haoui'),
        'pagenav' => __('页面左侧导航', 'haoui')
    ));
}

// reg sidebar
if (function_exists('register_sidebar')) {
	$sidebars = array(
		'gheader' => '公共头部',
		'gfooter' => '公共底部',
		'home'    => '首页',
		'cat'     => '分类页',
		'tag'     => '标签页',
		'search'  => '搜索页',
		'single'  => '文章页'
	);

	foreach ($sidebars as $key => $value) {
		register_sidebar(array(
			'name'          => $value,
			'id'            => $key,
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
		));
	};
}

function _hui($name, $default = false) {
	$option_name = 'dux';

	/*// Gets option name as defined in the theme
	if ( function_exists( 'optionsframework_option_name' ) ) {
		$option_name = optionsframework_option_name();
	}

	// Fallback option name
	if ( '' == $option_name ) {
		$option_name = get_option( 'stylesheet' );
		$option_name = preg_replace( "/\W/", "_", strtolower( $option_name ) );
	}*/

	// Get option settings from database
	$options = get_option( $option_name );

	// Return specific option
	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}

if( !_hui('gravatar_url') || _hui('gravatar_url') == 'ssl' ){
    add_filter('get_avatar', '_get_ssl2_avatar');
}else if( _hui('gravatar_url') == 'duoshuo' ){
    add_filter('get_avatar', '_duoshuo_get_avatar', 10, 3);
}

//官方Gravatar头像调用ssl头像链接
function _get_ssl2_avatar($avatar) {
    $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2&d=mm" class="avatar avatar-$2" height="50" width="50">',$avatar);
    return $avatar;
}

//多说官方Gravatar头像调用
function _duoshuo_get_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "gravatar.duoshuo.com", $avatar);
    return $avatar;
}









// require no-category
if( _hui('no_categoty') && !function_exists('no_category_base_refresh_rules') ){

	register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
	add_action('created_category', 'no_category_base_refresh_rules');
	add_action('edited_category', 'no_category_base_refresh_rules');
	add_action('delete_category', 'no_category_base_refresh_rules');
	function no_category_base_refresh_rules() {
	    global $wp_rewrite;
	    $wp_rewrite -> flush_rules();
	}

	register_deactivation_hook(__FILE__, 'no_category_base_deactivate');
	function no_category_base_deactivate() {
	    remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
	    // We don't want to insert our custom rules again
	    no_category_base_refresh_rules();
	}

	// Remove category base
	add_action('init', 'no_category_base_permastruct');
	function no_category_base_permastruct() {
	    global $wp_rewrite, $wp_version;
	    if (version_compare($wp_version, '3.4', '<')) {
	        // For pre-3.4 support
	        $wp_rewrite -> extra_permastructs['category'][0] = '%category%';
	    } else {
	        $wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
	    }
	}

	// Add our custom category rewrite rules
	add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
	function no_category_base_rewrite_rules($category_rewrite) {
	    //var_dump($category_rewrite); // For Debugging

	    $category_rewrite = array();
	    $categories = get_categories(array('hide_empty' => false));
	    foreach ($categories as $category) {
	        $category_nicename = $category -> slug;
	        if ($category -> parent == $category -> cat_ID)// recursive recursion
	            $category -> parent = 0;
	        elseif ($category -> parent != 0)
	            $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
	        $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
	        $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
	        $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
	    }
	    // Redirect support from Old Category Base
	    global $wp_rewrite;
	    $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
	    $old_category_base = trim($old_category_base, '/');
	    $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

	    //var_dump($category_rewrite); // For Debugging
	    return $category_rewrite;
	}

	// For Debugging
	//add_filter('rewrite_rules_array', 'no_category_base_rewrite_rules_array');
	//function no_category_base_rewrite_rules_array($category_rewrite) {
	//  var_dump($category_rewrite); // For Debugging
	//}

	// Add 'category_redirect' query variable
	add_filter('query_vars', 'no_category_base_query_vars');
	function no_category_base_query_vars($public_query_vars) {
	    $public_query_vars[] = 'category_redirect';
	    return $public_query_vars;
	}

	// Redirect if 'category_redirect' is set
	add_filter('request', 'no_category_base_request');
	function no_category_base_request($query_vars) {
	    //print_r($query_vars); // For Debugging
	    if (isset($query_vars['category_redirect'])) {
	        $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
	        status_header(301);
	        header("Location: $catlink");
	        exit();
	    }
	    return $query_vars;
	}

}






// head code
add_action('wp_head', '_the_head');
function _the_head() {
	_the_keywords();
	_the_description();
	_post_views_record();
	_the_head_css();
	_the_head_code();
}
function _the_head_code() {
	if (_hui('headcode')) {
		echo "\n<!--HEADER_CODE_START-->\n" . _hui('headcode') . "\n<!--HEADER_CODE_END-->\n";
	}

}
function _the_head_css() {
	$styles = '';

	if (_hui('site_gray')) {
		$styles .= "html{overflow-y:scroll;filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(100%);}";
	}

	if (_hui('site_width') && _hui('site_width')!=='1200') {
		$styles .= ".container{max-width:"._hui('site_width')."px}";
	}

	$color = '';
	if (_hui('theme_skin') && _hui('theme_skin') !== '45B6F7') {
		$color = _hui('theme_skin');
	}

	if (_hui('theme_skin_custom') && _hui('theme_skin_custom') !== '#45B6F7') {
		$color = substr(_hui('theme_skin_custom'), 1);
	}

	if ($color) {
		$styles .= 'a:hover, .site-navbar li:hover > a, .site-navbar li.active a:hover, .site-navbar a:hover, .search-on .site-navbar li.navto-search a, .topbar a:hover, .site-nav li.current-menu-item > a, .site-nav li.current-menu-parent > a, .site-search-form a:hover, .branding-primary .btn:hover, .title .more a:hover, .excerpt h2 a:hover, .excerpt .meta a:hover, .excerpt-minic h2 a:hover, .excerpt-minic .meta a:hover, .article-content .wp-caption:hover .wp-caption-text, .article-content a, .article-nav a:hover, .relates a:hover, .widget_links li a:hover, .widget_categories li a:hover, .widget_ui_comments strong, .widget_ui_posts li a:hover .text, .widget_ui_posts .nopic .text:hover , .widget_meta ul a:hover, .tagcloud a:hover, .textwidget a, .textwidget a:hover, .sign h3, #navs .item li a, .url, .url:hover, .excerpt h2 a:hover span, .widget_ui_posts a:hover .text span, .widget-navcontent .item-01 li a:hover span, .excerpt-minic h2 a:hover span, .relates a:hover span{color: #'.$color.';}.btn-primary, .label-primary, .branding-primary, .post-copyright:hover, .article-tags a, .pagination ul > .active > a, .pagination ul > .active > span, .pagenav .current, .widget_ui_tags .items a:hover, .sign .close-link, .pagemenu li.active a, .pageheader, .resetpasssteps li.active, #navs h2, #navs nav, .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open > .dropdown-toggle.btn-primary, .tag-clouds a:hover{background-color: #'.$color.';}.btn-primary, .search-input:focus, #bdcs .bdcs-search-form-input:focus, #submit, .plinks ul li a:hover,.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open > .dropdown-toggle.btn-primary{border-color: #'.$color.';}.search-btn, .label-primary, #bdcs .bdcs-search-form-submit, #submit, .excerpt .cat{background-color: #'.$color.';}.excerpt .cat i{border-left-color:#'.$color.';}@media (max-width: 720px) {.site-navbar li.active a, .site-navbar li.active a:hover, .m-nav-show .m-icon-nav{color: #'.$color.';}}@media (max-width: 480px) {.pagination ul > li.next-page a{background-color:#'.$color.';}}';
	}

	if (_hui('csscode')) {
		$styles .= _hui('csscode');
	}

	if ($styles) {
		echo '<style>' . $styles . '</style>';
	}
}

// foot code
add_action('wp_footer', '_the_footer');
function _the_footer() {
	if (_hui('footcode')) {
		echo "<!--FOOTER_CODE_START-->\n" . _hui('footcode') . "\n<!--FOOTER_CODE_END-->\n";
	}
}

// excerpt length
add_filter('excerpt_length', '_excerpt_length');
function _excerpt_length($length) {
	return 120;
}

// smilies src
add_filter('smilies_src', '_smilies_src', 1, 10);
function _smilies_src($img_src, $img, $siteurl) {
	return get_stylesheet_directory_uri() . '/img/smilies/' . $img;
}

// load script and style
add_action('wp_enqueue_scripts', '_load_scripts');
function _load_scripts() {
	if (!is_admin()) {
		wp_deregister_script('jquery');

		// delete l10n.js
		wp_deregister_script('l10n');

		$purl = get_stylesheet_directory_uri();

		// common css
		_cssloader(array('bootstrap' => $purl.'/css/bootstrap.min.css', 'fontawesome' => $purl.'/css/font-awesome.min.css', 'main' => 'main','zoom' => 'zoom','base' => 'base','swiper' => 'swiper'));

		// page css
		if (is_page_template('pages/user.php')) {
			_cssloader(array('user' => 'user'));
		}
	


		
		
		$jss = array(
            'no' => array(
                'jquery' => $purl.'/js/libs/jquery.min.js',
				'bootstrap' => $purl . '/js/libs/bootstrap.min.js',
				'html2canvas' => $purl . '/js/libs/html2canvas.js',
				'canvas2image' => $purl . '/js/libs/canvas2image.js',
				'qrcode' => $purl . '/js/libs/qrcode.min.js',
				'zoom' => $purl . '/js/libs/zoom.js',
				'swiper' => $purl . '/js/libs/swiper.min.js',

				
            ),
            'baidu' => array(
                'jquery' => 'http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js',
                'bootstrap' => 'http://apps.bdimg.com/libs/bootstrap/3.2.0/js/bootstrap.min.js'
            ),
            '360' => array(
                'jquery' => $purl.'/js/libs/jquery.min.js',
                'bootstrap' => $purl . '/js/libs/bootstrap.min.js'
            ),
            'he' => array(
                'jquery' => '//code.jquery.com/jquery-1.9.1.min.js',
                'bootstrap' => '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'
            )
        );
		wp_register_script( 'jquery', _hui('js_outlink') ? $jss[_hui('js_outlink')]['jquery'] : $purl.'/js/libs/jquery.min.js', false, THEME_VERSION, (_hui('jquery_bom')?true:false) );
        wp_enqueue_script( 'bootstrap', _hui('js_outlink') ? $jss[_hui('js_outlink')]['bootstrap'] : $purl . '/js/libs/bootstrap.min.js', array('jquery'), THEME_VERSION, true );
		wp_enqueue_script( 'html2canvas', _hui('js_outlink') ? $jss[_hui('js_outlink')]['html2canvas'] : $purl . '/js/libs/html2canvas.js', array('jquery'), THEME_VERSION, true );
		wp_enqueue_script( 'canvas2image', _hui('js_outlink') ? $jss[_hui('js_outlink')]['canvas2image'] : $purl . '/js/libs/canvas2image.js', array('jquery'), THEME_VERSION, true );
		wp_enqueue_script( 'qrcode', _hui('js_outlink') ? $jss[_hui('js_outlink')]['qrcode'] : $purl . '/js/libs/qrcode.min.js', array('jquery'), THEME_VERSION, true );
		wp_enqueue_script( 'zoom', _hui('js_outlink') ? $jss[_hui('js_outlink')]['zoom'] : $purl . '/js/libs/zoom.js', array('jquery'), THEME_VERSION, true );
		wp_enqueue_script( 'swiper', _hui('js_outlink') ? $jss[_hui('js_outlink')]['swiper'] : $purl . '/js/libs/swiper.min.js', array('jquery'), THEME_VERSION, true );
		_jsloader(array('loader'));
		
        // wp_enqueue_script( '_main', $purl . '/js/main.js', array(), THEME_VERSION, true );


	}
}
function _cssloader($arr) {
	foreach ($arr as $key => $item) {
		$href = $item;
		if (strstr($href, '//') === false) {
			$href = get_stylesheet_directory_uri() . '/css/' . $item . '.css';
		}
		wp_enqueue_style('_' . $key, $href, array(), THEME_VERSION, 'all');
	}
}
function _jsloader($arr) {
	foreach ($arr as $item) {
		wp_enqueue_script('_' . $item, get_stylesheet_directory_uri() . '/js/' . $item . '.js', array(), THEME_VERSION, true);
	}
}

function _get_default_avatar(){
	return get_stylesheet_directory_uri() . '/img/avatar-default.png';
}

function _get_delimiter(){
	return _hui('connector') ? _hui('connector') : '-';
}




function _post_target_blank(){
    return _hui('target_blank') ? ' target="_blank"' : '';
}

function _title() {
	global $new_title;
	if( $new_title ) return $new_title;

	global $paged;

	$html = '';
	$t = trim(wp_title('', false));

	if( (is_single() || is_page()) && get_the_subtitle(false) ){
		$t .= get_the_subtitle(false);
	}

	if ($t) {
		$html .= $t . _get_delimiter();
	}

	$html .= get_bloginfo('name');

	if (is_home()) {
		if(_hui('hometitle')){
            $html = _hui('hometitle');
            if ($paged > 1) {
                $html .= _get_delimiter() . '最新发布';
            }
        }else{
			if ($paged > 1) {
				$html .= _get_delimiter() . '最新发布';
			}else if( get_option('blogdescription') ){
				$html .= _get_delimiter() . get_option('blogdescription');
			}
		}
	}

	if( is_category() ){
		global $wp_query; 
		$cat_ID = get_query_var('cat');
		$cat_tit = _get_tax_meta($cat_ID, 'title');
		if( $cat_tit ){
			$html = $cat_tit;
		}
	}

	if ($paged > 1) {
		$html .= _get_delimiter() . '第' . $paged . '页';
	}

	return $html;
}

function get_the_subtitle($span=true){
    global $post;
    $post_ID = $post->ID;
    $subtitle = get_post_meta($post_ID, 'subtitle', true);

    if( !empty($subtitle) ){
    	if( $span ){
        	return ' <span>'.$subtitle.'</span>';
        }else{
        	return ' '.$subtitle;
        }
    }else{
        return false;
    }
}



function _bodyclass() {
	$class = '';

	if( _hui('nav_fixed') && !is_page_template('pages/resetpassword.php') ){
		$class .= ' nav_fixed';
	}
	
	if( _hui('list_comments_r') ){
		$class .= ' list-comments-r';
	}

	if ((is_single() || is_page()) && _hui('post_p_indent_s')) {
		$class .= ' p_indent';
	}

	if ((is_single() || is_page()) && comments_open()) {
		$class .= ' comment-open';
	}
	if (is_super_admin()) {
		$class .= ' logged-admin';
	}
	
	$class .= ' site-layout-'.(_hui('layout') ? _hui('layout') : '2');

	if( _hui('list_type')=='text' ){
		$class .= ' list-text';
	}

	if( is_category() ){
		_moloader('mo_is_minicat', false);
		if( mo_is_minicat() ){
			$class .= ' site-minicat';
		}
	}

	return trim($class);
}

function _moloader($name = '', $apply = true) {
	if (!function_exists($name)) {
		include get_stylesheet_directory() . '/modules/' . $name . '.php';
	}

	if ($apply && function_exists($name)) {
		$name();
	}
}


function _the_menu($location = 'nav') {
	echo str_replace("</ul></div>", "", preg_replace("/<div[^>]*><ul[^>]*>/", "", wp_nav_menu(array('theme_location' => $location, 'echo' => false))));
}

function _the_logo() {
	$svg = '<svg version="1.1" id="图层_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	viewBox="0 0 6803.1 1984.3" enable-background="new 0 0 6803.1 1984.3" xml:space="preserve">
<g>
   <g>
	   <g>
		   <g>
			   <path fill="#EAAB21" d="M815.7,1135.1c-59.2,0-120-2.5-182.4-7.4c-62.4-5-119.1-12.8-170-23.7l184.9-991.6
				   c55.7-10.9,114.2-18.3,175.7-22.2c61.4-3.9,117.8-5.9,169.2-5.9c59.2,0,110.6,6.2,154.2,18.5c43.6,12.3,79.4,28.9,107.7,49.6
				   c28.2,20.7,49.7,45.1,64.5,73.3c14.8,28.1,23,58,24.7,89.5c1.1,21.7,0.1,44.4-3.1,68.1c-3.2,23.7-10.9,47.1-23,70.3
				   c-12.1,23.2-29.2,45.1-51.3,65.9c-22.1,20.7-51.5,39.5-88.1,56.2c45.4,19.7,79.4,46.6,101.9,80.7c22.5,34,34.8,71.8,37,113.2
				   c2.5,47.4-4.5,93-21,136.9c-16.5,43.9-44.5,82.9-84.2,116.9c-39.7,34-92,61.2-157.1,81.4
				   C990.3,1124.9,910.4,1135.1,815.7,1135.1z M770.8,673.3l-49.7,266.4c15.9,3,36,5.2,60.3,6.7c24.3,1.5,46.7,2.2,67.5,2.2
				   c29.6,0,58.8-2.2,87.7-6.7c28.8-4.4,54.6-12.6,77.2-24.4c22.6-11.8,40.7-28.1,54.4-48.8c13.7-20.7,19.7-47.4,18-79.9
				   c-0.7-13.8-4.2-27.6-10.3-41.4c-6.2-13.8-15.4-26.1-27.8-37c-12.4-10.8-28.4-19.7-48-26.6c-19.6-6.9-42.7-10.4-69.4-10.4H770.8z
					M804.6,500.1h145c59.2,0,102.7-12.8,130.4-38.5c27.8-25.6,40.7-56.2,38.9-91.8c-1-18.7-6-34.8-15.1-48.1
				   c-9.1-13.3-20.7-23.9-35-31.8c-14.2-7.9-30.3-13.6-48.2-17c-17.9-3.4-36.3-5.2-55-5.2c-20.7,0-42.9,0.7-66.5,2.2
				   c-23.6,1.5-41.3,3.2-53,5.2L804.6,500.1z"/>
		   </g>
		   <polygon fill="#EAAB21" points="470.7,1064.8 687.5,1046.4 659.9,1197.1 443.3,1197.1 			"/>
		   <polygon fill="#EAAB21" points="1162.8,152.1 946,170.5 973.6,19.8 1190.1,19.8 			"/>
		   <polygon fill="#EAAB21" points="779.1,1064.8 995.9,1046.4 968.3,1197.1 751.7,1197.1 			"/>
		   <polygon fill="#EAAB21" points="542.9,112.4 747.7,97.1 846.1,275.2 506.7,309.4 			"/>
		   <polygon fill="#EAAB21" points="392.6,917.1 713.3,939.7 743.3,1134.1 358,1104 			"/>
		   <polygon fill="#EAAB21" points="854.4,152.1 637.6,170.5 665.2,19.8 881.7,19.8 			"/>
	   </g>
	   <path fill="#EAAB21" d="M1584,1120.7h-199.9l168.8-710.5h201.3L1584,1120.7z M1681.3,323.7c-27.9,0-53.1-8.3-75.6-25
		   c-22.5-16.7-33.8-42.1-33.8-76.3c0-18.9,3.8-36.7,11.5-53.4c7.6-16.7,17.8-31.1,30.4-43.2c12.6-12.2,27.2-21.8,43.9-29
		   c16.7-7.2,34.5-10.8,53.4-10.8c27.9,0,53.1,8.3,75.6,25c22.5,16.7,33.8,42.1,33.8,76.3c0,18.9-3.8,36.7-11.5,53.4
		   c-7.7,16.7-17.8,31.1-30.4,43.2c-12.6,12.2-27.3,21.8-43.9,29C1718,320.1,1700.2,323.7,1681.3,323.7z"/>
	   <path fill="#EAAB21" d="M1959.5,233.2l209.4-32.4l-51.3,209.4h224.2L2301.2,575h-222.9l-59.4,248.5c-5.4,20.7-8.8,40.1-10.1,58.1
		   c-1.4,18,0.9,33.6,6.7,46.6c5.8,13.1,16,23.2,30.4,30.4c14.4,7.2,34.2,10.8,59.4,10.8c21.6,0,42.5-2,62.8-6.1
		   c20.3-4.1,40.7-9.7,61.5-16.9l14.9,154c-27,9.9-56.3,18.5-87.8,25.7c-31.5,7.2-68.9,10.8-112.1,10.8c-62.1,0-110.3-9.2-144.5-27.7
		   c-34.2-18.4-58.5-43.7-72.9-75.6c-14.4-32-20.7-68.7-18.9-110.1c1.8-41.4,8.1-85.1,18.9-131L1959.5,233.2z"/>
	   <path fill="#EAAB21" d="M1584.8,1117.6h-199l168.1-707.2h200.3L1584.8,1117.6z M1681.6,324.4c-27.8,0-52.9-8.3-75.3-24.9
		   c-22.4-16.6-33.6-41.9-33.6-76c0-18.8,3.8-36.5,11.4-53.1c7.6-16.6,17.7-30.9,30.3-43c12.5-12.1,27.1-21.7,43.7-28.9
		   c16.6-7.2,34.3-10.8,53.1-10.8c27.8,0,52.9,8.3,75.3,24.9c22.4,16.6,33.6,41.9,33.6,76c0,18.8-3.8,36.5-11.4,53.1
		   c-7.6,16.6-17.7,30.9-30.3,43c-12.6,12.1-27.1,21.7-43.7,28.9C1718.1,320.8,1700.4,324.4,1681.6,324.4z"/>
	   <path fill="#EAAB21" d="M1958.5,234.3l208.4-32.3l-51.1,208.4H2339l-40.3,164h-221.8l-59.2,247.4c-5.4,20.6-8.7,39.9-10.1,57.8
		   c-1.3,17.9,0.9,33.4,6.7,46.4c5.8,13,15.9,23.1,30.3,30.3c14.3,7.2,34.1,10.8,59.2,10.8c21.5,0,42.4-2,62.5-6
		   c20.2-4,40.5-9.6,61.2-16.8l14.8,153.3c-26.9,9.9-56,18.4-87.4,25.5c-31.4,7.2-68.6,10.8-111.6,10.8c-61.8,0-109.8-9.2-143.9-27.6
		   c-34.1-18.4-58.3-43.5-72.6-75.3c-14.3-31.8-20.6-68.3-18.8-109.6c1.8-41.2,8.1-84.7,18.8-130.4L1958.5,234.3z"/>
	   <path fill="#4C4B4C" d="M2879.7,59.6c67.7,0,126.9,8.1,177.4,24.3c50.5,16.2,92.5,37.9,125.9,65.2c33.4,27.3,58.1,59.4,74.3,96.3
		   c16.2,36.9,24.3,75.6,24.3,116c0,33.4-3.8,66.2-11.4,98.6c-7.6,32.4-21.7,63.2-42.5,92.5c-20.7,29.3-48.5,56.4-83.4,81.1
		   c-34.9,24.8-79.6,45.7-134.2,62.9c28.3,52.6,57.4,116.3,87.2,191.1c29.8,74.8,57.9,153.2,84.2,235h-253.2
		   c-23.3-69.8-46.8-138.7-70.5-207c-23.8-68.2-50.3-127.1-79.6-176.7h-118.3l-92.5,383.6h-235L2579.5,86.9
		   c54.6-10.1,107.7-17.2,159.2-21.2C2790.2,61.6,2837.2,59.6,2879.7,59.6z M2872.1,259.7c-17.2,0-34.9,0.5-53.1,1.5
		   c-18.2,1-33.4,2-45.5,3l-68.2,288.1h80.4c48.5,0,89.5-4.5,122.8-13.6c33.4-9.1,60.1-21.2,80.4-36.4c20.2-15.2,34.6-32.6,43.2-52.3
		   c8.6-19.7,12.9-39.7,12.9-59.9c0-17.2-2.5-33.6-7.6-49.3c-5.1-15.7-14.2-29.6-27.3-41.7c-13.1-12.1-30.8-21.7-53.1-28.8
		   C2934.8,263.3,2906.5,259.7,2872.1,259.7z"/>
	   <path fill="#4C4B4C" d="M3901.1,817.3c-12.6,51.1-16.1,102.4-10.8,153.9c5.4,51.6,15.7,97.5,30.9,137.8l-178.8,25.5
		   c-5.4-10.8-10.3-21-14.8-30.9c-4.5-9.9-9-21.1-13.4-33.6c-25.1,20.6-52.9,37.6-83.4,51.1c-30.5,13.4-64.5,20.2-102.2,20.2
		   c-44.8,0-83.1-7.9-115-23.5c-31.8-15.7-57.8-36.7-78-63.2c-20.2-26.4-34.8-57.4-43.7-92.8c-9-35.4-13.4-73.3-13.4-113.6
		   c0-61.8,11-120.1,32.9-174.8c21.9-54.7,52.4-102.4,91.4-143.2c39-40.8,84.9-72.8,137.8-96.1c52.9-23.3,110.2-35,172.1-35
		   c8.1,0,22.2,0.5,42.4,1.3c20.2,0.9,43.2,2.9,69.2,6c26,3.2,53.3,8.3,82,15.5c28.7,7.2,56,17,82,29.6L3901.1,817.3z M3761.3,569.9
		   c-11.7-1.8-22.2-3.1-31.6-4c-9.4-0.9-20.8-1.3-34.3-1.3c-30.5,0-58.9,7.6-85.4,22.9c-26.4,15.2-49.5,35.2-69.2,59.8
		   c-19.7,24.7-35.2,53.1-46.4,85.4c-11.2,32.3-16.8,65.9-16.8,100.8c0,43,7.2,77.1,21.5,102.2c14.3,25.1,40.8,37.6,79.3,37.6
		   c20.6,0,39.2-4,55.8-12.1c16.6-8.1,34.3-21.5,53.1-40.3c1.8-22.4,4.7-45.9,8.7-70.6c4-24.6,8.3-47.3,12.8-67.9L3761.3,569.9z"/>
	   <path fill="#4C4B4C" d="M4190.4,239.2l208.4-32.3l-51.1,208.4h223.2l-40.3,164h-221.8l-59.2,247.4c-5.4,20.6-8.7,39.9-10.1,57.8
		   c-1.3,17.9,0.9,33.4,6.7,46.4c5.8,13,15.9,23.1,30.3,30.3c14.3,7.2,34.1,10.8,59.2,10.8c21.5,0,42.4-2,62.5-6
		   c20.2-4,40.5-9.6,61.2-16.8l14.8,153.3c-26.9,9.9-56,18.4-87.4,25.5c-31.4,7.2-68.6,10.8-111.6,10.8c-61.8,0-109.8-9.2-143.9-27.6
		   c-34.1-18.4-58.3-43.5-72.6-75.3c-14.4-31.8-20.6-68.3-18.8-109.6c1.8-41.2,8.1-84.7,18.8-130.4L4190.4,239.2z"/>
	   <path fill="#4C4B4C" d="M4756.4,1122.5h-199l168.1-707.2h200.3L4756.4,1122.5z M4853.2,329.3c-27.8,0-52.9-8.3-75.3-24.9
		   c-22.4-16.6-33.6-41.9-33.6-76c0-18.8,3.8-36.5,11.4-53.1c7.6-16.6,17.7-30.9,30.3-43c12.5-12.1,27.1-21.7,43.7-28.9
		   c16.6-7.2,34.3-10.8,53.1-10.8c27.8,0,52.9,8.3,75.3,24.9c22.4,16.6,33.6,41.9,33.6,76c0,18.8-3.8,36.5-11.4,53.1
		   c-7.6,16.6-17.7,30.9-30.3,43c-12.6,12.1-27.1,21.7-43.7,28.9C4889.7,325.7,4872,329.3,4853.2,329.3z"/>
	   <path fill="#4C4B4C" d="M5100.5,447.6c15.2-4.5,32-9.6,50.4-15.5c18.4-5.8,39-11.2,61.8-16.1c22.9-4.9,48.4-8.9,76.6-12.1
		   c28.2-3.1,60.3-4.7,96.1-4.7c105.8,0,178.4,30.5,217.8,91.4c39.4,61,46.2,144.3,20.2,250.1l-91.4,381.8h-200.3l88.7-373.8
		   c5.4-23.3,9.6-45.9,12.8-67.9c3.1-21.9,2.9-41.2-0.7-57.8c-3.6-16.6-11.9-30-24.9-40.3c-13-10.3-32.9-15.5-59.8-15.5
		   c-26,0-52.4,2.7-79.3,8.1l-130.4,547.2h-200.3L5100.5,447.6z"/>
	   <path fill="#4C4B4C" d="M6310.6,1033.8c-13.4,58.3-31.2,108.7-53.1,151.3c-22,42.6-49.3,78-82,106.2
		   c-32.7,28.2-71.9,49.3-117.6,63.2c-45.7,13.9-99.1,20.8-160,20.8c-56.5,0-104-5.2-142.5-15.5c-38.5-10.3-73.5-23.1-104.9-38.3
		   l67.2-158.7c27.8,12.5,56.7,23.3,86.7,32.3c30,8.9,67,13.4,110.9,13.4c57.3,0,100.6-12.8,129.7-38.3
		   c29.1-25.5,48.2-58.5,57.1-98.8l5.4-25.5c-17.9,9-37,16.1-57.1,21.5c-20.2,5.4-41.5,8.1-63.9,8.1c-42.1,0-78.4-6.5-108.9-19.5
		   c-30.5-13-55.4-31.1-74.6-54.4c-19.3-23.3-33.6-50.9-43-82.7c-9.4-31.8-14.1-66.6-14.1-104.2c0-68.1,12.5-128.4,37.6-180.8
		   c25.1-52.4,58.5-96.1,100.2-131.1c41.7-35,89.6-61.4,143.9-79.3c54.2-17.9,110.9-26.9,170.1-26.9c43,0,85.6,4.5,127.7,13.4
		   c42.1,9,83.4,22.9,123.7,41.7L6310.6,1033.8z M6221.8,569.9c-15.2-3.6-34.1-5.4-56.5-5.4c-34.1,0-64.5,6-91.4,18.1
		   c-26.9,12.1-50,28.5-69.2,49.1c-19.3,20.6-34.1,45.5-44.4,74.6c-10.3,29.1-15.5,60.3-15.5,93.4c0,16.1,1.3,31.2,4,45
		   c2.7,13.9,7.6,26,14.8,36.3c7.2,10.3,17,18.6,29.6,24.9c12.5,6.3,28.2,9.4,47.1,9.4c12.5,0,29.3-2.2,50.4-6.7
		   c21-4.5,40.1-14.8,57.1-30.9L6221.8,569.9z"/>
   </g>
   <g>
	   <path fill="#1AB1FC" d="M3023.6,1245v192.5h154.1v92.9h-154.1v238.9l156.1-53.2c-0.9,35.5-0.2,69.3,2,101.6
		   c-99.6,30.5-177.2,60.8-232.9,90.9l-48.5-79.4c15.7-12.1,23.6-31.9,23.6-59.2v-525H3023.6z M3461,1331.2l87.5,68.7
		   c-76.1,72.7-154.1,141.2-234.2,205.6v145c0,28.3,11.4,42.4,34.3,42.4h47.1c17.5,0,29.1-6.2,34.7-18.5c5.6-12.3,11.6-58,17.8-137
		   c35.4,13.5,68.9,24.9,100.3,34.3c-4.5,42.4-10.3,79.4-17.5,110.9c-7.2,31.5-15.5,53.3-24.9,65.3c-9.4,12-23.4,21.3-41.9,27.9
		   c-18.5,6.6-41.1,9.9-67.8,9.9h-84.8c-64.6,0-96.9-34.3-96.9-103V1237h99.6v248.4C3363,1438.9,3411.9,1387.5,3461,1331.2z"/>
	   <path fill="#1AB1FC" d="M3582.2,1710.1l-12.1-95.6l116.4-19.2v-131.6h-44.4c-4.7,33.2-10.3,65.3-16.8,96.2
		   c-17.1-10.8-35.7-20.9-55.9-30.3c13.9-72.2,23.3-150.5,28.3-234.9l61.2,6.1c-1.6,26-3.6,51.4-6.1,76.1h33.7v-137.3h82.1V1377h39
		   v86.8h-39v116.4l43.7-8.8c-1.8,25.1-2.2,55.2-1.3,90.2l-42.4,8.1v229.5h-82.1v-213C3649.7,1694.1,3614.9,1702,3582.2,1710.1z
			M3814.4,1453h161.5v-55.9h-134.6v-80.8h134.6v-76.7h92.9v76.7h138.6v80.8h-138.6v55.9h170.3v80.8h-424.7V1453z M3835.3,1705.4
		   l46.1-33.7h-55.5V1591h244.3v-43.1h92.9v43.1h67.3v80.8H4163V1772c0,34.3-6.7,61.2-20,80.8c-13.4,19.5-31.1,32-53.3,37.5
		   c-22.2,5.5-66.6,8.2-133.3,8.2c-7.2-39-13.5-68.7-18.8-88.8c32.3,2.7,60.1,4,83.5,4c32.8,0,49.1-19.1,49.1-57.2v-84.8H3913
		   c25.1,26.2,50.4,54.3,75.7,84.1l-68.7,53.2C3892.7,1771.8,3864.4,1737.3,3835.3,1705.4z"/>
	   <path fill="#1AB1FC" d="M4496.2,1678.5c3.1,39.5,7.6,72.7,13.5,99.6c-68.7,44.4-117.3,83-146.1,115.8l-49.8-66
		   c11.2-14.4,16.8-33.2,16.8-56.5v-222.8H4262v-87.5h158.2V1737C4456.5,1710.1,4481.8,1690.6,4496.2,1678.5z M4377.7,1234.9
		   c31,41.7,60.3,83.5,88.2,125.2l-85.5,52.5c-21.5-39-48.7-83.2-81.4-132.6L4377.7,1234.9z M4460.5,1585.6h189.8v-230.2h-156.8
		   v-86.2h412.6v86.2h-157.5v230.2h178.4v86.2h-178.4v227.5h-98.3v-227.5h-189.8V1585.6z M4564.8,1372.9l56.5,158.2l-84.8,27.6
		   c-18.4-54.3-37.7-107.7-57.9-160.2L4564.8,1372.9z M4837.4,1367.5l83.5,31l-74,164.9l-76.1-33.7
		   C4791,1485.3,4813.2,1431.2,4837.4,1367.5z"/>
	   <path fill="#1AB1FC" d="M5044,1442.9c16.6-0.4,35.4-1.1,56.5-2c14.8-25.6,30.7-53.6,47.8-84.1l74.7,41.1
		   c-57,95.6-104.3,166.9-142,214c38.1-3.6,72.7-7.9,103.6-12.8c-3.6,23.8-5.8,50.9-6.7,81.4c-85.7,8.5-151.2,18.2-196.5,28.9
		   l-22.2-76.1c26-22.4,56.5-60.3,91.5-113.7c-32.8,2.7-59.2,5.4-79.4,8.1l-24.9-76.7c34.5-43.5,72.5-115.8,113.7-216.7l84.1,33.7
		   C5103.9,1342.4,5070.5,1400.7,5044,1442.9z M4948.5,1767.3c74.9-9.9,146.7-21.1,215.4-33.7c-1.8,18.8-3.1,37-4,54.5
		   c44-83.5,69.3-191.4,76.1-323.7c0.9-22,1.6-58.1,2-108.4h-47.8v-82.8h360.8v77.4L5499,1457h93.6v80.1
		   c-16.2,75.8-45.5,142.9-88.2,201.2c35.4,31.9,73.6,56.1,114.4,72.7c-25.6,34.1-45.3,62.6-59.2,85.5
		   c-42.2-24.7-80.3-55.2-114.4-91.5c-39.5,37.2-86.1,70.2-140,98.9c-18.4-30.5-37-57.9-55.9-82.1c54.3-25.1,100.1-55.4,137.3-90.9
		   c-30.5-45.8-56.5-98.3-78.1-157.5c-13.9,142.2-54.1,251.9-120.5,329.1c-19.3-28.7-38.6-53.4-57.9-74l-165.6,31L4948.5,1767.3z
			M5325.4,1403.9c34.5,114,73.4,201.9,116.4,263.8c26.5-38.6,45.3-81.7,56.5-129.2h-98.3V1457l47.1-101H5326
		   C5326,1371.8,5325.8,1387.7,5325.4,1403.9z"/>
   </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
';
	$tag = is_home() ? 'h1' : 'div';
	echo '<' . $tag . ' class="logo"><a href="' . get_bloginfo('url') . '" title="' . (_hui('hometitle')?_hui('hometitle'):get_bloginfo('name') .(get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '')). '"><img src="'._hui('logo_src').'">' . get_bloginfo('name') . '</a></' . $tag . '>';
	// echo '<' . $tag . ' class="logo"><a href="' . get_bloginfo('url') . '" title="' . (_hui('hometitle')?_hui('hometitle'):get_bloginfo('name') .(get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '')). '">'.$svg. '</a></' . $tag . '>';
}

function _the_ads($name='', $class=''){
    if( !_hui($name.'_s') ) return;

    if( wp_is_mobile() ){
    	echo '<div class="asb asb-m '.$class.'">'._hui($name.'_m').'</div>';
    }else{
        echo '<div class="asb '.$class.'">'._hui($name).'</div>';
    }
}


function _post_views_record() {
	if (is_singular()) {
		global $post;
		$post_ID = $post->ID;
		if ($post_ID) {
			$post_views = (int) get_post_meta($post_ID, 'views', true);
			if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
				add_post_meta($post_ID, 'views', 1, true);
			}
		}
	}
}
// 2018/01/04删除“阅读（）”
function _get_post_views($before = '', $after = '') {
	global $post;
	$post_ID = $post->ID;
	$views = (int) get_post_meta($post_ID, 'views', true);
	return $before . $views . $after;
}
//  获取星级
function _get_post_stars() {
	global $post;
	$post_ID = $post->ID;
	$views = (int) get_post_meta($post_ID, 'starLivel', true);
	return  $views;
}

function _str_cut($str, $start, $width, $trimmarker) {
	$output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $start . '}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $width . '}).*/s', '\1', $str);
	return $output . $trimmarker;
}

function _get_excerpt($limit = 120, $after = '...') {
	$excerpt = get_the_excerpt();
	if (_new_strlen($excerpt) > $limit) {
		return _str_cut(strip_tags($excerpt), 0, $limit, $after);
	} else {
		return $excerpt;
	}
}

function _get_post_comments($before = '评论(', $after = ')') {
	return $before . get_comments_number('0', '1', '%') . $after;
}

function _new_strlen($str,$charset='utf-8') {        
    $n = 0; $p = 0; $c = '';
    $len = strlen($str);
    if($charset == 'utf-8') {
        for($i = 0; $i < $len; $i++) {
            $c = ord($str{$i});
            if($c > 252) {
                $p = 5;
            } elseif($c > 248) {
                $p = 4;
            } elseif($c > 240) {
                $p = 3;
            } elseif($c > 224) {
                $p = 2;
            } elseif($c > 192) {
                $p = 1;
            } else {
                $p = 0;
            }
            $i+=$p;$n++;
        }
    } else {
        for($i = 0; $i < $len; $i++) {
            $c = ord($str{$i});
            if($c > 127) {
                $p = 1;
            } else {
                $p = 0;
        }
            $i+=$p;$n++;
        }
    }        
    return $n;
}

function _get_post_thumbnail($size = 'thumbnail', $class = 'thumb') {
	global $post;
	$r_src = '';
	if (has_post_thumbnail()) {
        $domsxe = get_the_post_thumbnail();
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $domsxe, $strResult, PREG_PATTERN_ORDER);  
        $images = $strResult[1];
        foreach($images as $src){
        	$r_src = $src;
            break;
        }
	}else{
	    $thumblink = get_post_meta($post->ID, 'thumblink', true);
		if( _hui('thumblink_s') && !empty($thumblink) ){
			$r_src = $thumblink;
		}
		elseif( _hui('thumb_postfirstimg_s') ){
			$content = $post->post_content;  
	        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);  
	        $images = $strResult[1];

	        foreach($images as $src){
		        if( _hui('thumb_postfirstimg_lastname') ){
		            $filetype = _get_filetype($src);
		            $src = rtrim($src, '.'.$filetype)._hui('thumb_postfirstimg_lastname').'.'.$filetype;
		        }

		        $r_src = $src;
		        break;
	        }
		}
    } 

	if( $r_src ){
		if( _hui('thumbnail_src') ){
    		return sprintf('<img data-src="%s" alt="%s" src="%s" class="thumb">', $r_src, $post->post_title._get_delimiter().get_bloginfo('name'), get_stylesheet_directory_uri().'/img/thumbnail.png');
		}else{
    		return sprintf('<img src="%s" alt="%s" class="thumb">', $r_src, $post->post_title._get_delimiter().get_bloginfo('name'));
		}
    }else{
    	return sprintf('<img data-thumb="default" src="%s" class="thumb">', get_stylesheet_directory_uri().'/img/thumbnail.png');
    }
}



function _get_filetype($filename) {
    $exten = explode('.', $filename);
    return end($exten);
}

function _get_attachment_id_from_src($link) {
	global $wpdb;
	$link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $link);
	return $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid='$link'");
}



//关键字
function _the_keywords() {
	global $new_keywords;
	if( $new_keywords ) {
		echo "<meta name=\"keywords\" content=\"{$new_keywords}\">\n";
		return;
	}

	global $s, $post;
	$keywords = '';
	if (is_singular()) {
		if (get_the_tags($post->ID)) {
			foreach (get_the_tags($post->ID) as $tag) {
				$keywords .= $tag->name . ', ';
			}
		}
		foreach (get_the_category($post->ID) as $category) {
			$keywords .= $category->cat_name . ', ';
		}
		$keywords = substr_replace($keywords, '', -2);
		$the = trim(get_post_meta($post->ID, 'keywords', true));
		if ($the) {
			$keywords = $the;
		}
	} elseif (is_home()) {
		$keywords = _hui('keywords');
	} elseif (is_tag()) {
		$keywords = single_tag_title('', false);
	} elseif (is_category()) {

		global $wp_query; 
		$cat_ID = get_query_var('cat');
		$keywords = _get_tax_meta($cat_ID, 'keywords');
		if( !$keywords ){
			$keywords = single_cat_title('', false);
		}
	
	} elseif (is_search()) {
		$keywords = esc_html($s, 1);
	} else {
		$keywords = trim(wp_title('', false));
	}
	if ($keywords) {
		echo "<meta name=\"keywords\" content=\"{$keywords}\">\n";
	}
}

//网站描述
function _the_description() {
	global $new_description;
	if( $new_description ){
		echo "<meta name=\"description\" content=\"$new_description\">\n";
		return;
	}

	global $s, $post;
	$description = '';
	$blog_name = get_bloginfo('name');
	if (is_singular()) {
		if (!empty($post->post_excerpt)) {
			$text = $post->post_excerpt;
		} else {
			$text = $post->post_content;
		}
		$description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($text))));
		$description = mb_substr($description, 0, 200, 'utf-8');

		if (!$description) {
			$description = $blog_name . "-" . trim(wp_title('', false));
		}

		$the = trim(get_post_meta($post->ID, 'description', true));
		if ($the) {
			$description = $the;
		}
		
	} elseif (is_home()) {
		$description = _hui('description');
	} elseif (is_tag()) {
		$description = trim(strip_tags(tag_description()));
	} elseif (is_category()) {

		global $wp_query; 
		$cat_ID = get_query_var('cat');
		$description = _get_tax_meta($cat_ID, 'description');
		if( !$description ){
			$description = trim(strip_tags(category_description()));
		}

	} elseif (is_archive()) {
		$description = $blog_name . "'" . trim(wp_title('', false)) . "'";
	} elseif (is_search()) {
		$description = $blog_name . ": '" . esc_html($s, 1) . "' 的搜索結果";
	} else {
		$description = $blog_name . "'" . trim(wp_title('', false)) . "'";
	}
	
	echo "<meta name=\"description\" content=\"$description\">\n";
}

function _set_time_ago($time) {
	
    date_default_timezone_set ('ETC/GMT-8');	
	$time = strtotime($time); 
	
	$difference = time() - $time; 

    switch ($difference) { 
    	case $difference <= '1' :
            $msg = '刚刚';
            break; 
        case $difference > '1' && $difference <= '60' :
            $msg = floor($difference) . '秒前';
            break; 
        case $difference > '60' && $difference <= '3600' :
            $msg = floor($difference / 60) . '分钟前';
            break;
         case $difference > '3600' && $difference <= '86400' :
            $msg = floor($difference / 3600) . '小时前';
            break; 
        case $difference > '86400' && $difference <= '2592000' :
            $msg = floor($difference / 86400) . '天前';
            break; 
        case $difference > '2592000':
            $msg = ''.date('Y-m-d',$time).'';
            break;
    } 
	return $msg;
	
}

function _get_time_ago($ptime) {
	date_default_timezone_set ('ETC/GMT-8');
	$ptime = strtotime($ptime);
	
	$etime = time() - $ptime;
	if ($etime < 1) {
		return '刚刚';
	}

	$interval = array(
		12 * 30 * 24 * 60 * 60 => '年前 (' . date('Y-m-d', $ptime) . ')',
		30 * 24 * 60 * 60 => '个月前 (' . date('m-d', $ptime) . ')',
		7 * 24 * 60 * 60 => '周前 (' . date('m-d', $ptime) . ')',
		24 * 60 * 60 => '天前',
		60 * 60 => '小时前',
		60 => '分钟前',
		1 => '秒前',
	);
	foreach ($interval as $secs => $str) {
		$d = $etime / $secs;
		if ($d >= 1) {
			$r = round($d);
			return $r . $str;
		}
	};
	$msg = ''.date('Y-m-d',$time).'';
	return $msg;
}

function _get_user_avatar($user_id = '') {
	if (!$user_id) {
		return false;
	}

	$avatar = get_user_meta($user_id, 'avatar');
	if ($avatar) {
		return $avatar;
	} else {
		return false;
	}
}

function _get_the_avatar($user_id = '', $user_email = '', $src = false, $size = 50) {
	$user_avtar = _get_user_avatar($user_id);
	if ($user_avtar) {
		$attr = 'data-src';
		if ($src) {
			$attr = 'src';
		}

		return '<img class="avatar avatar-' . $size . ' photo" width="' . $size . '" height="' . $size . '" ' . $attr . '="' . $user_avtar . '">';
	} else {
		$avatar = get_avatar($user_email, $size, _get_default_avatar());
		if ($src) {
			return $avatar;
		} else {
			return str_replace(' src=', ' data-src=', $avatar);
		}
	}
}


//评论回应邮件通知
add_action('comment_post', '_comment_mail_notify');
function _comment_mail_notify($comment_id) {
	$admin_notify = '1';// admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
	$admin_email = get_bloginfo('admin_email');// $admin_email 可改为你指定的 e-mail.
	$comment = get_comment($comment_id);
	$comment_author_email = trim($comment->comment_author_email);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	global $wpdb;
	if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '') {
		$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
	}

	if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1')) {
		$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
	}

	$notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
	$spam_confirmed = $comment->comment_approved;
	if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
		$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));// e-mail 发出点, no-reply 可改为可用的 e-mail.
		$to = trim(get_comment($parent_id)->comment_author_email);
		$subject = 'Hi，您在 [' . get_option("blogname") . '] 的留言有人回复啦！';

		$letter = (object) array(
			'author' => trim(get_comment($parent_id)->comment_author),
			'post' => get_the_title($comment->comment_post_ID),
			'comment' => trim(get_comment($parent_id)->comment_content),
			'replyer' => trim($comment->comment_author),
			'reply' => trim($comment->comment_content),
			'link' => htmlspecialchars(get_comment_link($parent_id)),
			'sitename' => get_option('blogname')
		);

		$message = '
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"><tbody><tr><td><table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse"><tbody><tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td width="73" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:5px 0 0 0"></td><td valign="top" style="border-top:1px solid #d9d9d9"><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="font-size:18px;line-height:18px;color:#444;font-family:Microsoft Yahei">Hi, ' . $letter->author . '<br><br><br></div><div style="font-size:14px;line-height:22px;color:#444;font-weight:bold;font-family:Microsoft Yahei">您在' . $letter->sitename . '《' . $letter->post . '》的评论：</div><div style="font-size:14px;line-height:10px"><br></div><div style="font-size:14px;line-height:22px;color:#666;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp; ' . $letter->comment . '</div><div style="font-size:14px;line-height:10px"><br><br></div><div style="font-size:14px;line-height:22px;color:#5DB408;font-weight:bold;font-family:Microsoft Yahei">' . $letter->replyer . ' 回复您：</div><div style="font-size:14px;line-height:10px"><br></div><div style="font-size:14px;line-height:22px;color:#666;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp; ' . $letter->reply . '</div><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="text-align:center"><a href="' . $letter->link . '" target="_blank" style="text-decoration:none;color:#fff;display:inline-block;line-height:44px;font-size:18px;background-color:#61B3E6;border-radius:3px;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp;点击查看回复&nbsp; &nbsp;&nbsp; &nbsp;</a><br><br></div></td><td width="65" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-radius:0 5px 0 0"></td></tr><tr><td style="border-left:1px solid #d9d9d9">&nbsp;</td><td align="left" valign="top" style="color:#999"><div style="font-size:8px;line-height:14px"><br><br></div><div style="min-height:1px;font-size:1px;line-height:1px;background-color:#e0e0e0">&nbsp;</div><div style="font-size:12px;line-height:20px;width:425px;font-family:Microsoft Yahei"><br><a href="' . _hui('letter_link_1') . '" target="_blank" style="text-decoration:underline;color:#61B3E6;font-family:Microsoft Yahei">' . _hui('letter_text_1') . '</a><br><a href="' . _hui('letter_link_2') . '" target="_blank" style="text-decoration:underline;color:#61B3E6;font-family:Microsoft Yahei">' . _hui('letter_text_2') . '</a><br>此邮件由' . $letter->sitename . '系统自动发出，请勿回复！</div></td><td style="border-right:1px solid #d9d9d9">&nbsp;</td></tr><tr><td colspan="3" style="border-bottom:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:0 0 5px 5px"><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr><tr><td><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr></tbody></table>';

		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
		wp_mail($to, $subject, $message, $headers);
		//echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
	}
}

//自动勾选
add_action('comment_form', '_comment_add_checkbox');
function _comment_add_checkbox() {
	echo '<label for="comment_mail_notify" class="checkbox inline hide" style="padding-top:0"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>有人回复时邮件通知我</label>';
}

//文章（包括feed）末尾加版权说明
// add_filter('the_content', '_post_copyright');
function _post_copyright($content) {
	_moloader('mo_is_minicat', false);

	if ( !is_page() && !mo_is_minicat() ) {
		if (_hui('ads_post_footer_s')) {
			$content .= '<p class="asb-post-footer"><b>AD：</b><strong>【' . _hui('ads_post_footer_pretitle') . '】</strong><a'.(_hui('ads_post_footer_link_blank')?' target="_blank"':'').' href="' . _hui('ads_post_footer_link') . '">' . _hui('ads_post_footer_title') . '</a></p>';
		}

		if( _hui('post_copyright_s') ){
			$content .= '<p class="post-copyright">' . _hui('post_copyright') . '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> &raquo; <a href="' . get_permalink() . '">' . get_the_title() . '</a></p>';
		}
	}

	return $content;
}





function curPageURL() {
    $pageURL = 'http';

    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["HTTPS"] != "on") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}








// print_r( _get_tax_meta(21, 'style') );

function _get_tax_meta($id=0, $field=''){
    $ops = get_option( "_taxonomy_meta_$id" );

    if( empty($ops) ){
        return '';
    }

    if( empty($field) ){
        return $ops;
    }

    return isset($ops[$field]) ? $ops[$field] : '';
}


class __Tax_Cat{

    function __construct(){
        add_action( 'category_add_form_fields', array( $this, 'add_tax_field' ) );
        add_action( 'category_edit_form_fields', array( $this, 'edit_tax_field' ) );

        add_action( 'edited_category', array( $this, 'save_tax_meta' ), 10, 2 );
        add_action( 'create_category', array( $this, 'save_tax_meta' ), 10, 2 );
    }
 
    public function add_tax_field(){
        echo '
            <div class="form-field">
                <label for="term_meta[title]">SEO 标题</label>
                <input type="text" name="term_meta[title]" id="term_meta[title]" />
            </div>
            <div class="form-field">
                <label for="term_meta[keywords]">SEO 关键字（keywords）</label>
                <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" />
            </div>
            <div class="form-field">
                <label for="term_meta[keywords]">SEO 描述（description）</label>
                <textarea name="term_meta[description]" id="term_meta[description]" rows="4" cols="40"></textarea>
            </div>
        ';
    }
 
    public function edit_tax_field( $term ){

        $term_id = $term->term_id;
        $term_meta = get_option( "_taxonomy_meta_$term_id" );

        $meta_title = isset($term_meta['title']) ? $term_meta['title'] : '';
        $meta_keywords = isset($term_meta['keywords']) ? $term_meta['keywords'] : '';
        $meta_description = isset($term_meta['description']) ? $term_meta['description'] : '';
        
        echo '
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[title]">SEO 标题</label>
                    <td>
                        <input type="text" name="term_meta[title]" id="term_meta[title]" value="'. $meta_title .'" />
                    </td>
                </th>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[keywords]">SEO 关键字（keywords）</label>
                    <td>
                        <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" value="'. $meta_keywords .'" />
                    </td>
                </th>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <label for="term_meta[description]">SEO 描述（description）</label>
                    <td>
                        <textarea name="term_meta[description]" id="term_meta[description]" rows="4">'. $meta_description .'</textarea>
                    </td>
                </th>
            </tr>
        ';
    }
 
    public function save_tax_meta( $term_id ){
 
        if ( isset( $_POST['term_meta'] ) ) {
            
            $term_meta = array();

            $term_meta['title'] = isset ( $_POST['term_meta']['title'] ) ? esc_sql( $_POST['term_meta']['title'] ) : '';
            $term_meta['keywords'] = isset ( $_POST['term_meta']['keywords'] ) ? esc_sql( $_POST['term_meta']['keywords'] ) : '';
            $term_meta['description'] = isset ( $_POST['term_meta']['description'] ) ? esc_sql( $_POST['term_meta']['description'] ) : '';

            update_option( "_taxonomy_meta_$term_id", $term_meta );
 
        }
    }
 
}
 
$tax_cat = new __Tax_Cat();



function hui_breadcrumbs(){
    if( !is_single() ) return false;
    $categorys = get_the_category();
    $category = $categorys[0];
    
    return '当前位置：<a href="'.get_bloginfo('url').'">'.'首页'.'</a> <small>></small> '.get_category_parents($category->term_id, true, ' <small>></small> ').(!_hui('breadcrumbs_single_text')?get_the_title():'正文');
}