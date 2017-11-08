<?php
// Heading
$_['heading_title'] = '系统设置';

// Text
$_['text_success']       = '成功提示： 您已成功更改设置！';
$_['text_items']         = '项目';
$_['text_product']       = '产品';
$_['text_voucher']       = '优惠券';
$_['text_tax']           = '税收';
$_['text_account']       = '账户';
$_['text_checkout']      = '结账';
$_['text_stock']         = '库存';
$_['text_image_manager'] = '图像管理';
$_['text_browse']        = '选择图像';
$_['text_clear']         = '清除图像';
$_['text_shipping']      = '收货地址';
$_['text_payment']       = '支付地址';

// Column
$_['column_name']   = '系统名称';
$_['column_url']    = '系统地址';
$_['column_domain'] = '系统域名值';
$_['column_action'] = '管理';

// Entry
$_['entry_domain']                 = '系统域名：<span class="help">请填写需要定位到系统的域名或域名中的关键字</span>';
$_['entry_url']                    = '系统网址：<span class="help">请输入你系统的完整网址。 注意在最后加上 \'/\' 。 例： http：//www.yourdomain.com/path/<br /><br />请用新的域名或二级域名指定到你的主机。</span>';
$_['entry_ssl']                    = '使用 SSL：<span class="help">要使用SSL， 您需要在您的服务安装SSL及在设定档有SSL凭证。</span>';
$_['entry_name']                   = '系统名称：';
$_['entry_owner']                  = '系统拥有者：';
$_['entry_address']                = '地址：';
$_['entry_email']                  = '邮件地址：';
$_['entry_telephone']              = '联系电话：';
$_['entry_fax']                    = '传真号码：';
$_['entry_title']                  = '首页标题：';
$_['entry_meta_description']       = '元标签说明：';
$_['entry_layout']                 = '默认布局：';
$_['entry_template']               = '模版设置：';
$_['entry_country']                = '国家：';
$_['entry_zone']                   = '省份：';
$_['entry_language']               = '前台语言：';
$_['entry_currency']               = '币种设置：';
$_['entry_catalog_limit']          = '默认每页显示 (目录)：<span class="help">确定目录项目中每页显示多少（产品，类别等）</span>';
$_['entry_tax']                    = '显示含税价：';
$_['entry_tax_default']            = '使用系统所在地税率：<span class="help">非注册用户使用系统所在地税率。 你可以使用系统所在地税率计算税费。</span>';
$_['entry_tax_customer']           = '使用客户所在地税率：<span class="help">注册客户使用客户所在地税率。 你可以使用系统所在地税率计算税费。</span>';
$_['entry_customer_group']         = '用户组：<span class="help">默认客户群组。</span>';
$_['entry_customer_group_display'] = '客户群组：<span class="help">显示客户群体，新客户可以选择在报名时使用，如批发业务。</span>';
$_['entry_customer_price']         = '登录后显示价格：<span class="help">会员登入后，才能显示价格。</span>';
$_['entry_account']                = '账户条款：<span class="help">注册条款。</span>';
$_['entry_cart_weight']            = '在购物车中显示重量：';
$_['entry_guest_checkout']         = '游客结账：<span class="help">允许未注册用户直接结账，此项交易不适用于可下载的产品。</span>';
$_['entry_checkout']               = '结账条款：<span class="help">结账条款。</span>';
$_['entry_order_status']           = '订单状态：<span class="help">设置默认订单状态的命令时处理。</span>';
$_['entry_stock_display']          = '显示库存：<span class="help">显示产品页面上的库存数量。</span>';
$_['entry_stock_checkout']         = '运行结账：<span class="help">如果客户订购的产品缺货，仍然允许结账。</span>';
$_['entry_logo']                   = '系统图标：';
$_['entry_icon']                   = 'Icon 图标：<span class="help">图标必须为PNG格式大小为：16px x 16px。</span>';
$_['entry_image_category']         = '分类列表的大小：';
$_['entry_image_thumb']            = '产品缩略图尺寸：';
$_['entry_image_popup']            = '产品图片弹出尺寸：';
$_['entry_image_product']          = '产品列表的大小：';
$_['entry_image_additional']       = '附加产品图像尺寸：';
$_['entry_image_related']          = '关联产品图像尺寸：';
$_['entry_image_compare']          = '对比清单图片大小：';
$_['entry_image_wishlist']         = '收藏清单图片大小：';
$_['entry_image_cart']             = '购物车图像尺寸：';
$_['entry_use_ssl']                = '使用 SSL：<span class="help">请确认你已经安排了SSL证书。</span>';

// Error
$_['error_warning']                = '系统提示： 请认真查看错误信息！';
$_['error_permission']             = '系统提示： 抱歉，您没有权限新增或修改系统设置！';
$_['error_name']                   = '系统名称必须在2至32个字符之间！';
$_['error_owner']                  = '系统拥有者必须在2至64个字符之间！';
$_['error_address']                = '系统地址必须在10 到256个字符！';
$_['error_email']                  = '无效的邮件地址！';
$_['error_telephone']              = '电话号码必须在2到32个字符！';
$_['error_domain']                 = '系统域名关键值需3到50位字符！';
$_['error_url']                    = '系统网址无效！';
$_['error_title']                  = '首页标题必须在3到32个数字之间！';
$_['error_limit']                  = '限制规定！';
$_['error_customer_group_display'] = '如果你要使用此功能，你必须包括默认的客户群！';
$_['error_image_thumb']            = '请输入产品预览图片大小！';
$_['error_image_popup']            = '请输入产品放大图片大小！';
$_['error_image_product']          = '请输入产品页面中的图片大小！';
$_['error_image_category']         = '请输入产品分类中的图片大小！';
$_['error_image_additional']       = '请输入附加产品图像尺寸！';
$_['error_image_related']          = '请输入关联产品图像尺寸！';
$_['error_image_compare']          = '请输入对比产品图像尺寸！';
$_['error_image_wishlist']         = '请输入收藏产品图像尺寸！';
$_['error_image_cart']             = '请输入购物车中的产品图像尺寸！';
$_['error_default']                = '系统提示： 您不能删除默认系统！';
$_['error_store']                  = '系统提示： 本系统不能被删除，因为目前它被绑定到%s订单！';
?>