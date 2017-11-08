function tipBox(tip, tit, cExec)
{
	tit = tit || 'HECart Notification';
	cExec = cExec || function () {};
	$.fn.tboxy({title: tit, value: tip, time: 6000, closeExec: cExec});
}

jQuery.cookie = function (name, value, options)
{
	if (typeof value != 'undefined') //name and value given, set cookie
	{
		options = options || {};
		if (value === null)
		{
			value = '';
			options.expires = -1;
		}

		var expires = '';
		if (options.expires && (typeof options.expires == 'number' || options.expires.toGMTString))
		{
			var date;
			if (typeof options.expires == 'number')
			{
				date = new Date();
				date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
			}
			else
			{
				date = options.expires;
			}
			expires = '; expires=' + date.toGMTString();
		}

		var path = options.path ? '; path=' + (options.path) : '';
		var domain = options.domain ? '; domain=' + (options.domain) : '';
		var secure = options.secure ? '; secure' : '';
		document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	}
	else //only name given, get cookie
	{
		var cookieValue = null;
		if (document.cookie && document.cookie != '')
		{
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++)
			{
				var cookie = jQuery.trim(cookies[i]);
				if (cookie.substring(0, name.length + 1) == (name + '='))
				{
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break;
				}
			}
		}
		return cookieValue;
	}
}

$.fn.easyTabs = function ()
{
	var selector = this;
	this.each(function ()
	{
		var obj = $(this);
		$(obj.attr('href')).hide();
		$(obj).click(function ()
		{
			$(selector).removeClass('selected');
			$(selector).each(function (i, element)
			{
				$($(element).attr('href')).hide();
			});

			$(this).addClass('selected');
			$($(this).attr('href')).fadeIn();
			return false;
		});
	});

	$(this).show();
	$(this).first().click();
};

function loadJsCss(filename)
{
	var ref, strFile = filename.toLowerCase();
	if (strFile.indexOf('.js') != -1)
	{
		ref = document.createElement('script');
		ref.setAttribute('type', 'text/javascript');
		ref.setAttribute('src', filename);
	}
	else if (strFile.indexOf('.css'))
	{
		ref = document.createElement('link');
		ref.setAttribute('rel', 'stylesheet');
		ref.setAttribute('type', 'text/css');
		ref.setAttribute('href', filename);
	}

	if (typeof ref != 'undefined')
	{
		document.getElementsByTagName('head')[0].appendChild(ref);
	}
}

function pwdSetting(obj)
{
	$(obj).find(':password').each(function ()
	{
		if ($(this).val())
		{
			$(this).val($.md5($(this).val()));
		}
	});
}

function pwdChecking(obj)
{
	$(obj).find(':password').each(function ()
	{
		if ($(this).val())
		{
			var salt = $(this).attr('salt');
			var pwd = $.md5($(this).val());
			$(this).val($.md5($.md5($.md5(pwd.substring(0, 9)) + pwd) + salt));
		}
	});
}

Storage.prototype.set = function (key, value)
{
	this.setItem(key, JSON.stringify(value));
}

Storage.prototype.get = function (key)
{
	var value = this.getItem(key);
	return value && JSON.parse(value);
}