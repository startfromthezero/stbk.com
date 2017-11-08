<?php echo $page_header; ?>
<link href="/public/css/home.css" rel="stylesheet" />
<body>
    <!-- 导航 -->
    <nav class="blog-nav layui-header">
        <div class="blog-container">
            <!-- QQ互联登陆 -->
            <a href="javascript:;" id="qqLoginBtn" class="blog-user">
                <i class="fa fa-qq"></i>
            </a>
            <a href="javascript:;" class="blog-user layui-hide">
                <img src="/public/images/Absolutely.jpg" alt="Absolutely" title="Absolutely" />
            </a>
            <!-- 风之迷者 -->
            <a class="blog-logo" href="/">风之迷者</a>
            <!-- 导航菜单 -->
            <ul class="layui-nav" lay-filter="nav">
                <li class="layui-nav-item layui-this">
                    <a href="/"><i class="fa fa-home fa-fw"></i>&nbsp;网站首页</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/web/main/article"><i class="fa fa-file-text fa-fw"></i>&nbsp;文章专栏</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/web/main/resource"><i class="fa fa-tags fa-fw"></i>&nbsp;资源分享</a>
                </li>
                <li class="layui-nav-item">
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
    <div id="loginDiv" style="height: 305px; width: 450px; border: 0px; padding: 0px; margin: 0px; position: absolute; z-index: 100002; top: 334px; left: 35%; display: block; visibility: visible;">
        <iframe id="loginIframe" name="loginIframe" scrolling="no" width="100%" height="100%" frameborder="0"></iframe>
    </div>
    <!-- 主体（一般只改变这里的内容） -->
    <div class="blog-body">
        <!-- canvas -->
        <canvas id="canvas-banner" style="background: #393D49;"></canvas>
        <!--为了及时效果需要立即设置canvas宽高，否则就在home.js中设置-->
        <script type="text/javascript">
            var canvas = document.getElementById('canvas-banner');
            canvas.width = window.document.body.clientWidth - 10;//减去滚动条的宽度
            if (screen.width >= 992) {
                canvas.height = window.innerHeight * 1 / 3;
            } else {
                canvas.height = window.innerHeight * 2 / 7;
            }
        </script>
        <!-- 这个一般才是真正的主体内容 -->
        <div class="blog-container">
            <div class="blog-main">
                <!-- 网站公告提示 -->
                <div class="home-tips shadow">
                    <i style="float:left;line-height:17px;" class="fa fa-volume-up"></i>
                    <div class="home-tips-container">
                        <span style="color: #009688">偷偷告诉大家，本博客的后台管理也正在制作，为大家准备了游客专用账号！</span>
                        <span style="color: red">网站新增留言回复啦！使用QQ登陆即可回复，人人都可以回复！</span>
                        <span style="color: red">如果你觉得网站做得还不错，来Fly社区点个赞吧！<a href="http://fly.layui.com/case/2017/" target="_blank" style="color:#01AAED">点我前往</a></span>
                        <span style="color: #009688">风之迷者 &nbsp;—— &nbsp;一个.NET程序员的个人博客，新版网站采用Layui为前端框架，目前正在建设中！</span>
                    </div>
                </div>
                <!--左边文章列表-->
                <div class="blog-main-left">
                    <?php foreach ($news as $key=>$val){ ?>
                    <div class="article shadow">
                        <div class="article-left">
                            <img src="<?php echo $val['img_url']; ?>" alt="<?php echo $val['news_name']; ?>" />
                        </div>
                        <div class="article-right">
                            <div class="article-title">
                                <a href="/web/main/detail"><?php echo $val['news_name']; ?></a>
                            </div>
                            <div class="article-abstract">
                                <?php echo $val['news_synopsis']; ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="article-footer">
                            <span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo !empty($val['time_modify']) ? $val['time_modify'] : $val['time_added'];?></span>
                            <span class="article-author"><i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo $val['news_author']; ?></span>
                            <span><i class="fa fa-tag"></i>&nbsp;&nbsp;<a href="#">
                                    <?php echo $news_types[$val['type_id']]; ?></a></span>
                            <span class="article-viewinfo"><i class="fa fa-eye"></i>&nbsp;0</span>
                            <span class="article-viewinfo"><i class="fa fa-commenting"></i>&nbsp;4</span>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!--右边小栏目-->
                <div class="blog-main-right">
                    <div class="blogerinfo shadow">
                        <div class="blogerinfo-figure">
                            <img src="/public/images/Absolutely.jpg" alt="Absolutely" />
                        </div>
                        <p class="blogerinfo-nickname">TongShao</p>
                        <p class="blogerinfo-introduce">一个差点就90的PHP程序员</p>
                        <p class="blogerinfo-location"><i class="fa fa-location-arrow"></i>&nbsp;广东 - 深圳</p>
                        <hr />
                        <div class="blogerinfo-contact">
                            <a target="_blank" title="QQ交流" href="javascript:layer.msg('启动QQ会话窗口')"><i class="fa fa-qq fa-2x"></i></a>
                            <a target="_blank" title="给我写信" href="javascript:layer.msg('启动邮我窗口')"><i class="fa fa-envelope fa-2x"></i></a>
                            <a target="_blank" title="新浪微博" href="javascript:layer.msg('转到你的微博主页')"><i class="fa fa-weibo fa-2x"></i></a>
                            <a target="_blank" title="码云" href="javascript:layer.msg('转到你的github主页')"><i class="fa fa-git fa-2x"></i></a>
                        </div>
                    </div>
                    <div></div><!--占位-->
                    <div class="blog-module shadow">
                        <div class="blog-module-title">热文排行</div>
                        <ul class="fa-ul blog-module-ul">
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">Web安全之跨站请求伪造CSRF</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">ASP.NET MVC 防范跨站请求伪造（CSRF）</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">常用正则表达式</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">EF CodeFirst数据迁移常用指令</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">浅谈.NET Framework基元类型</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">C#基础知识回顾-扩展方法</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">一步步制作时光轴（一）（HTML篇）</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/web/main/detail">一步步制作时光轴（二）（CSS篇）</a></li>
                        </ul>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">最近分享</div>
                        <ul class="fa-ul blog-module-ul">
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="http://pan.baidu.com/s/1c1BJ6Qc" target="_blank">Canvas</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="http://pan.baidu.com/s/1kVK8UhT" target="_blank">pagesize.js</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="https://pan.baidu.com/s/1mit2aiW" target="_blank">时光轴</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="https://pan.baidu.com/s/1nuAKF81" target="_blank">图片轮播</a></li>
                        </ul>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">一路走来</div>
                        <dl class="footprint">
                            <dt>2017年03月12日</dt>
                            <dd>新增留言回复功能！人人都可参与回复！</dd>
                            <dt>2017年03月10日</dt>
                            <dd>风之迷者2.0基本功能完成，正式上线！</dd>
                            <dt>2017年03月09日</dt>
                            <dd>新增文章搜索功能！</dd>
                            <dt>2017年02月25日</dt>
                            <dd>QQ互联接入网站，可QQ登陆发表评论与留言！</dd>
                        </dl>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">后台记录</div>
                        <dl class="footprint">
                            <dt>2017年03月16日</dt>
                            <dd>分页新增页容量控制</dd>
                            <dt>2017年03月12日</dt>
                            <dd>新增管家提醒功能</dd>
                            <dt>2017年03月10日</dt>
                            <dd>新增Win10快捷菜单</dd>
                        </dl>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">友情链接</div>
                        <ul class="blogroll">
                            <li><a target="_blank" href="http://www.layui.com/" title="Layui">Layui</a></li>
                            <li><a target="_blank" href="http://www.pagemark.cn/" title="页签">页签</a></li>
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!-- 本页脚本 -->
    <script src="/public/js/home.js"></script>
    <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101439780" data-redirecturi="http://www.stbk.com/callback.php" charset="utf-8"></script>
    <script type="text/javascript">
        //qq登录
        QC.Login({
            btnId: "qqLoginBtn",    //插入按钮的节点id
            //用户需要确认的scope授权项，可选，默认all
            scope: "all",
            //按钮尺寸，可用值[A_XL| A_L| A_M| A_S|  B_M| B_S| C_S]，可选，默认B_S
            size : "A_M"
        });

        //从页面收集OpenAPI必要的参数。get_user_info不需要输入参数，因此paras中没有参数
        var paras = {};

        QC.api("get_user_info", {}) //get_user_info是API参数
                //指定接口访问成功的接收函数，s为成功返回Response对象
                .success(function (s)
                {
                    //成功回调，通过s.data获取OpenAPI的返回数据
                    nick = s.data.nickname; //获得昵称
                    headurl = s.data.figureurl_qq_1; //获得头像
                    if (QC.Login.check())
                    {//判断是否登录
                        QC.Login.getMe(function (openId, accessToken)
                        { //这里可以得到openId和accessToken
                            //下面可以调用自己的保存方法

                        });
                    }
                })
                //指定接口访问失败的接收函数，f为失败返回Response对象
                .error(function (f)
                {
                    //失败回调
                    alert("获取用户信息失败！");
                });
        ////指定接口完成请求后的接收函数，c为完成请求返回Response对象
        //.complete(function (c) {
        //    //完成请求回调
        //    alert("获取用户信息完成！");
        //});
    </script>
    <!-- 底部 -->
    <?php echo $page_footer; ?>