function getURLVar(key)
{
	var value = [];
	var query = String(document.location).split('?');
	if (query[1])
	{
		var part = query[1].split('&');
		for (i = 0; i < part.length; i++)
		{
			var data = part[i].split('=');
			if (data[0] && data[1])
			{
				value[data[0]] = data[1];
			}
		}
	}
	return (typeof value[key] != 'undefined') ? value[key] : '';
}

//打印对象
function print_r(theObj)
{
	var retStr = '';
	if (typeof theObj == 'object')
	{
		retStr += '<div style="font-size:15px;">';
		for (var p in theObj)
		{
			if (typeof theObj[p] == 'object')
			{
				retStr += '<div><b>[' + p + '] => ' + typeof(theObj) + '</b></div>';
				retStr += '<div style="padding-left:25px;">' + print_r(theObj[p]) + '</div>';
			}
			else
			{
				retStr += '<div>[' + p + '] => <b>' + theObj[p] + '</b></div>';
			}
		}
		retStr += '</div>';
	}
	document.write(retStr);
}

//Tab
function useTabs(arr, cls)
{
	$(arr).each(function ()
	{
		var obj = this;
		$(obj[0]).mouseover(function ()
		{
			$(arr).each(function ()
			{
				$(this[0]).removeClass(cls);
				$(this[1]).hide();
			});

			$(obj[0]).addClass(cls);
			$(obj[1]).show();
		});
	});
}

/*
 对日期时间做计算处理
 type	: 要计算的类型: 年、季、月、天、时、分、秒
 interval: 间隔数(正数为加时间,负数为减时间)
 vdate	: 从哪天开始算起,如果不填写则为此时开始
 */
function calcDate(type, interval, vdate)
{
	interval = parseInt(interval);
	var date = (vdate) ? new Date(vdate) : new Date();
	switch (type)
	{
	case 'y'://年
		date.setYear(date.getYear() + interval);
		break;
	case 'q' ://季
		date.setMonth(date.getMonth() + (interval * 3));
		break;
	case 'm' ://月
		date.setMonth(date.getMonth() + interval);
		break;
	case 'd' ://天
		date.setDate(date.getDate() + interval);
		break
	case 'h' ://时
		date.setHours(date.getHours() + interval);
		break
	case 'm' ://分
		date.setMinutes(date.getMinutes() + interval);
		break
	case 's' ://秒
		date.setSeconds(date.getSeconds() + interval);
		break;
	default:
	}
	return date;
}

//获取事件句标
function getEvent(evt)
{
	if (document.all || window.opera)
	{
		return window.event;
	}
	var func = getEvent.caller;
	while (func != null)
	{
		var arg0 = func.arguments[0];
		if (arg0)
		{
			if ((arg0.constructor == Event || arg0.constructor == MouseEvent) ||
				(typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation))
			{
				return arg0;
			}
		}
		func = func.caller;
	}
	return window.event;
}

//获取表单GET传值中的数据
function getVar(name)
{
	var getStr = document.location.search;
	var getStr = getStr.substr(1, getStr.length) + '&';
	var pos = getStr.lastIndexOf(name + '=');
	if (!name || pos == -1)
	{
		return "";
	}
	pos += String(name).length + 1
	return getStr.substring(pos, getStr.indexOf('&', pos));
}

//分析表单GET传值中的数据返回数组
function getArrayVar()
{
	var getStr = document.location.search;
	var getStr = getStr.substr(1, getStr.length) + '&';
	getStr = getStr.replace(/&/g, "',");
	getStr = getStr.replace(/=/g, ":'");
	getStr = getStr.slice(0, -1);
	eval("var v = {" + getStr + "};");
	return v;
}

//数字全角转半角
function SBCcaseToDBCcase(str)
{
	var vStr = '１２３４５６７８９０－（）＋；，　';
	var vStr1 = '1234567890-()+;, ';
	var len = vStr.length;
	str = String(str);
	for (var i = 0; i < len; ++i)
	{
		str = str.replace(new RegExp(vStr.substr(i, 1), 'g'), vStr1.substr(i, 1));
	}
	return str;
}

//格式化显示价格@param int price 价格@param int precision 保留几位小数
function formatPrice(price, precision)
{
	price = Number(price);
	precision = precision || 2;//default 2
	if (precision == 0 || price == price.toFixed())
	{
		return price.toFixed();
	}
	return price.toFixed(precision);
}

//获取数据的类型
function getDataType(obj)
{
	if (obj == undefined)
	{
		return false;
	}
	if (obj.htmlElement)
	{
		return 'element';
	}
	var type = typeof obj;

	if (type == 'object' && obj.nodeName)
	{
		switch (obj.nodeType)
		{
		case 1:
			return 'element';
		case 3:
			return (/\S/).test(obj.nodeValue) ? 'textnode' : 'whitespace';
		}
	}

	if (type == 'object' || type == 'function')
	{
		switch (obj.constructor)
		{
		case Array:
			return 'array';
		case RegExp:
			return 'regexp';
		}
		if (typeof obj.length == 'number')
		{
			if (obj.item) return 'collection';
			if (obj.callee) return 'arguments';
		}
	}
	return type;
}

//检验URL链接是否有效
function getUrlState(URL)
{
	var xmlhttp = new ActiveXObject("microsoft.xmlhttp");
	xmlhttp.Open("GET", URL, false);
	try
	{
		xmlhttp.Send();
	}
	catch (e)
	{
	}
	finally
	{
		var result = xmlhttp.responseText;
		if (result)
		{
			return (xmlhttp.Status == 200) ? true : false;
		}
		else
		{
			return (false);
		}
	}
}

//格式化CSS样式代码
function formatCss(s)
{
	s = s.replace(/\s*([\{\}\:\;\,])\s*/g, "$1");
	s = s.replace(/;\s*;/g, ";"); //清除连续分号
	s = s.replace(/\,[\s\.\#\d]*{/g, "{");
	s = s.replace(/([^\s])\{([^\s])/g, "$1 {\n\t$2");
	s = s.replace(/([^\s])\}([^\n]*)/g, "$1\n}\n$2");
	s = s.replace(/([^\s]);([^\s\}])/g, "$1;\n\t$2");
	return s;
}

//压缩CSS样式代码
function compressCss(s)
{
	s = s.replace(/\/\*(.|\n)*?\*\//g, ""); //删除注释
	s = s.replace(/\s*([\{\}\:\;\,])\s*/g, "$1");
	s = s.replace(/\,[\s\.\#\d]*\{/g, "{"); //容错处理
	s = s.replace(/;\s*;/g, ";"); //清除连续分号
	s = s.match(/^\s*(\S+(\s+\S+)*)\s*$/); //去掉首尾空白
	return (s == null) ? "" : s[1];
}

//随机数时间戳
function uniqueId()
{
	var a = Math.random, b = parseInt;
	return Number(new Date()).toString() + b(10 * a()) + b(10 * a()) + b(10 * a());
}

//全角半角转换 iCase: 0全到半，1半到全，其他不转化
function chgCase(sStr, iCase)
{
	if (typeof sStr != "string" || sStr.length <= 0 || !(iCase === 0 || iCase == 1))
	{
		return sStr;
	}
	var i, oRs = [], iCode;
	if (iCase)/*半->全*/
	{
		for (i = 0; i < sStr.length; i += 1)
		{
			iCode = sStr.charCodeAt(i);
			if (iCode == 32)
			{
				iCode = 12288;
			}
			else if (iCode < 127)
			{
				iCode += 65248;
			}
			oRs.push(String.fromCharCode(iCode));
		}
	}
	else/*全->半*/
	{
		for (i = 0; i < sStr.length; i += 1)
		{
			iCode = sStr.charCodeAt(i);
			if (iCode == 12288)
			{
				iCode = 32;
			}
			else if (iCode > 65280 && iCode < 65375)
			{
				iCode -= 65248;
			}
			oRs.push(String.fromCharCode(iCode));
		}
	}
	return oRs.join("");
}

//替换全部
String.prototype.replaceAll = function (s1, s2)
{
	return this.replace(new RegExp(s1, "gm"), s2)
}

//清除空格
String.prototype.trim = function ()
{
	var reExtraSpace = /^\s*(.*?)\s+$/;
	return this.replace(reExtraSpace, "$1")
}

//清除左空格
String.prototype.ltrim = function ()
{
	return this.replace(/^(\s*|　*)/, '');
}

//清除右空格
String.prototype.rtrim = function ()
{
	return this.replace(/(\s*|　*)$/, '');
}

//判断是否以某个字符串开头
String.prototype.startWith = function (s)
{
	return this.indexOf(s) == 0
}

//判断是否以某个字符串结束
String.prototype.endWith = function (s)
{
	var d = this.length - s.length;
	return (d >= 0 && this.lastIndexOf(s) == d)
}

//转义html标签
String.prototype.htmlEncode = function ()
{
	return this.replace(/&/g, '&').replace(/\"/g, '"').replace(/</g, '<').replace(/>/g, '>')
}

//本地存储数据
Storage.prototype.set = function (key, value)
{
	this.setItem(key, JSON.stringify(value));
}

//本地获取数据
Storage.prototype.get = function (key)
{
	var value = this.getItem(key);
	return value && JSON.parse(value);
}

/*
 对Date的扩展，将 Date 转化为指定格式的String
 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
 例子：
 (new Date()).format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
 (new Date()).format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
 */
Date.prototype.format = function (fmt)
{
	var o = {
		"M+": this.getMonth() + 1, //月份
		"d+": this.getDate(), //日
		"h+": this.getHours(), //小时
		"m+": this.getMinutes(), //分
		"s+": this.getSeconds(), //秒
		"q+": Math.floor((this.getMonth() + 3) / 3), //季度
		"S" : this.getMilliseconds() //毫秒
	};

	if (/(y+)/.test(fmt))
	{
		fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	}

	for (var k in o)
	{
		if (new RegExp("(" + k + ")").test(fmt))
		{
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		}
	}

	return fmt;
}

//数组去重复值
Array.prototype.unique = function ()
{
	var a = {};
	for (var i = 0; i < this.length; ++i)
	{
		if (typeof a[this[i]] == 'undefined')
		{
			a[this[i]] = '';
		}
	}
	this.length = 0;
	for (var i in a)
	{
		if (i.length > 0)
		{
			this[this.length] = i;
		}
	}
	return this;
}