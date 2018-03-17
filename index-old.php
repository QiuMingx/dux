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
			<!--<div class="swiper-container">
  <div class="swiper-wrapper">
    <div class="swiper-slide">slider1</div>
    <div class="swiper-slide">slider2</div>
    <div class="swiper-slide">slider3</div>
  </div>
</div>-->
			<div class="box box-line mb-20">
				<div class="box-title clearfix">
					<h4 class="pull-left">
						<a href="/m/lives.html">今日快讯</a>
					</h4>
					<a href="/m/lives.html" class="more pull-right">更多</a>
				</div>
				<?php $posts = get_posts( "category=live&numberposts=10" ); ?>
				<?php if( $posts ) : ?>
				<ul>
					<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<li>
						<a href=”<?php the_permalink() ?>” rel=”bookmark” title=”
							<?php the_title(); ?>”>
							<?php the_title(); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
				<div class="box-con today-swiper" style="height: 100px">
					<div class="swiper-container ">
						<div class="swiper-wrapper">
							<div class="swiper-slide ">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【美国会众议员布拉德·谢尔曼：数字货币目前是骗局】3月14日消息，国会众议员布拉德·谢尔曼(Brad Sherman)在“审查数字和ICO市场”听证会上表示，数字货币正在成为洗钱、黑钱、非法转移资产、融资诈骗的重要工具，甚至能帮助逃税，类似的各种违法用途。它的本质是一个骗局。据悉听证会结束后，数字货币市场现暴跌。（责任编辑：李文静）
											</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>2 小时前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【美国户外运动品牌取消区块链项目】华尔街日报消息，美国知名户外运动服装品牌L.L.Bean已经取消了其区块链计划，即将传感器缝制到大衣和靴子上，利用区块链账簿技术跟踪并了解人们如何使用户外装备。取消原因是媒体发文称该计划“令人毛骨悚然”，公司的真实用途是获得客户的位置数据，并在顾客购买后不断跟踪服装。该计划负责人Loomia则表示，开拓性的技术公司往往被误解。（责任编辑：杜烨）
											</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>38 分钟前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide ">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【小米区块链加密兔今日内测 11点正式开领】小米宣布其区块链游戏“加密兔”将于今天开始内测，并对部分玩家开放，今天上午11点正式开领，该产品由米链团队开发。加密兔是基于区块链技术的数字宠物，胡萝卜代表加密兔游戏中的积分。通过完成任务可获得胡萝卜或加密兔，所得的胡萝卜可以兑换加密兔。据悉，目前在社交网络上，已经传出加密兔炒到上百元价格的消息，小米方面尚未对此进行回应。（责任编辑：筱雨）
											</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>40 分钟前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide ">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【香港证监会：针对数字货币投资者保障问题会采取行动】3月15日消息称，香港证监会梁凤仪表示部分加密货币及ICO存在投资者保障问题，有留意部分的ICO活动，证监会亦有采取行动，例如近期证监会向一离岸ICO活动发警告信，成功令网页拒绝使用香港IP的用户登录。（责任编辑：李文静）
											</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>2 小时前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide ">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【美国国会议员候选人接受比特币献金】来自外媒消息称，美国加利福尼亚州第52区国会候选人迈克尔·奥尔曼开始接受比特币政治献金。据了解，参议员共和党候选人奥斯汀彼得森在竞选活动中收到了价值约4500美金的比特币献金。成为美国政治史上目前数额最大的单笔比特币捐赠。（责任编辑：李文静）
										</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>2 小时前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【美国会众议员布拉德·谢尔曼：数字货币目前是骗局】3月14日消息，国会众议员布拉德·谢尔曼(Brad Sherman)在“审查数字和ICO市场”听证会上表示，数字货币正在成为洗钱、黑钱、非法转移资产、融资诈骗的重要工具，甚至能帮助逃税，类似的各种违法用途。它的本质是一个骗局。据悉听证会结束后，数字货币市场现暴跌。（责任编辑：李文静）
											</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>2 小时前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide ">
								<div class="media">
									<div class="media-body">
										<p class="clamp line-3">
											【美国户外运动品牌取消区块链项目】华尔街日报消息，美国知名户外运动服装品牌L.L.Bean已经取消了其区块链计划，即将传感器缝制到大衣和靴子上，利用区块链账簿技术跟踪并了解人们如何使用户外装备。取消原因是媒体发文称该计划“令人毛骨悚然”，公司的真实用途是获得客户的位置数据，并在顾客购买后不断跟踪服装。该计划负责人Loomia则表示，开拓性的技术公司往往被误解。（责任编辑：杜烨）
											</p>
										<div class="news-widget" style="margin:0">
											<div class="item">
												<i class="icon-time"></i>
												<span>38 分钟前</span>
											</div>
										</div>
									</div>
								</div>
							</div>
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
<?php get_footer();