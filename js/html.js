/**
 * Created by stefan on 20.06.15.
 */

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

$(function () {
	$('.tabbed').each(function () {
		var $this = $(this),
			active = null,
			active_category = readCookie('active'),
			$headlines = $this.find('.headlines .headline'),
			render = function () {
				$headlines.each(function () {
					var $headline = $(this),
						$content = $headline.data('content');
					if ($headline.data('active'))
					{
						$headline.addClass('active');
						$content.show();
					}
					else
					{
						$headline.removeClass('active');
						$content.hide();
					}
				});
			};

		if (active_category !== null) {
			$headlines.each(function () {
				var $headline = $(this),
					category = $headline.data('category');
				if (category == active_category)
				{
					active = $headline;
					$headlines.data('active', false);
					$headline.data('active', true);
				}
			});
		}
		$headlines.each(function () {
			var $headline = $(this),
				category = $headline.data('category'),
				$content = $this.find('.content .category[data-category="'+category+'"]');
			if (active === null)
			{
				active = $headline;
				$headlines.data('active', false);
				createCookie('active', category);
				$headline.data('active', true);

			}

			$headline.data('content', $content);
			$headline.on('click', function (event) {
				var $headline = $(event.target);
				active = $headline;
				$headlines.data('active', false);
				$headline.data('active', true);
				createCookie('active', category);
				render();
			});

		});

		render();
	});
});
