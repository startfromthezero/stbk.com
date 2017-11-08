<?php echo $page_header; ?>
<link href="/public/css/timeline.css" rel="stylesheet" />
<body>
    <!-- 导航 -->
    <nav class="blog-nav layui-header">
        <div class="blog-container">
            <!-- QQ互联登陆 -->
            <a href="javascript:;" class="blog-user">
                <i class="fa fa-qq"></i>
            </a>
            <a href="javascript:;" class="blog-user layui-hide">
                <img src="/public/images/Absolutely.jpg" alt="Absolutely" title="Absolutely" />
            </a>
            <!-- 风之迷者 -->
            <a class="blog-logo" href="/">风之迷者</a>
            <!-- 导航菜单 -->
            <ul class="layui-nav" lay-filter="nav">
                <li class="layui-nav-item">
                    <a href="/"><i class="fa fa-home fa-fw"></i>&nbsp;网站首页</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/web/main/article"><i class="fa fa-file-text fa-fw"></i>&nbsp;文章专栏</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/web/main/resource"><i class="fa fa-tags fa-fw"></i>&nbsp;资源分享</a>
                </li>
                <li class="layui-nav-item layui-this">
                    <a href="/web/main/timeline"><i class="fa fa-hourglass-half fa-fw"></i>&nbsp;点点滴滴</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/web/main/about"><i class="fa fa-info fa-fw"></i>&nbsp;关于本站</a>
                </li>
            </ul>
            <!-- 手机和平板的导航开关 -->
            <a class="blog-navicon" href="javascript:;">
                <i class="fa fa-navicon"></i>
            </a>
        </div>
    </nav>
    <!-- 主体（一般只改变这里的内容） -->
    <div class="blog-body">
        <div class="blog-container">
            <blockquote class="layui-elem-quote sitemap layui-breadcrumb shadow">
                <a href="/" title="网站首页">网站首页</a>
                <a href="/web/main/timeline" title="点点滴滴">点点滴滴</a>
                <a><cite>时光轴</cite></a>
            </blockquote>
            <div class="blog-main">
                <div class="child-nav shadow">
                    <span class="child-nav-btn child-nav-btn-this">时光轴</span>
                    <span class="child-nav-btn">笔记墙</span>
                </div>
                <div class="timeline-box shadow">
                    <div class="timeline-main">
                        <h1><i class="fa fa-clock-o"></i>时光轴<span> —— 记录生活点点滴滴</span></h1>
                        <div class="timeline-line"></div>
                        <?php foreach($times as $key=>$val){ ?>
                        <div class="timeline-year">
                            <h2><a class="yearToggle" href="javascript:;"><?php echo $key; ?></a><i class="fa fa-caret-down fa-fw"></i></h2>
                            <?php foreach($val as $k=>$v){ ?>
                            <div class="timeline-month">
                                <h3 class=" animated fadeInLeft"><a class="monthToggle" href="javascript:;"><?php echo $k; ?></a><i class="fa fa-caret-down fa-fw"></i></h3>
                                <ul>
                                    <?php foreach($v as $a){ ?>
                                    <li class=" ">
                                        <div class="h4  animated fadeInLeft">
                                            <p class="date"><?php echo $a['time']; ?></p>
                                        </div>
                                        <p class="dot-circle animated "><i class="fa fa-dot-circle-o"></i></p>
                                        <div class="content animated fadeInRight"><?php echo $a['content']; ?></div>
                                        <div class="clear"></div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <h1 style="padding-top:4px;padding-bottom:2px;margin-top:40px;"><i class="fa fa-hourglass-end"></i>THE END</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		layui.use('jquery', function () {
			var $ = layui.jquery;

			$(function () {
				$('.monthToggle').click(function () {
					$(this).parent('h3').siblings('ul').slideToggle('slow');
					$(this).siblings('i').toggleClass('fa-caret-down fa-caret-right');
				});
				$('.yearToggle').click(function () {
					$(this).parent('h2').siblings('.timeline-month').slideToggle('slow');
					$(this).siblings('i').toggleClass('fa-caret-down fa-caret-right');
				});
			});
		});
    </script>
<?php echo $page_footer; ?>