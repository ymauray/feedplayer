// Encoding: UTF-8

jQuery(document).ready(function($) {
	
	/* Init soundmanager2 */
	soundManager.setup({
		url: params.swf,
		onready: function() {
			soundManager.setVolume(50);
		}
	});
	
	$('.feedplayer-play-button').click(function() {
		var player = $(this).parents('.feedplayer');
		var audioId = 'audio-' + player.attr('id');
		if (player.find('.feedplayer-selected-item').size() == 0) {
			player.find('.feedplayer-item').first().click();
			return;
		}
		soundManager.resume(audioId);
		$(this).hide();
		player.find('.feedplayer-pause-button').show();
	});
	
	$('.feedplayer-pause-button').click(function() {
		var player = $(this).parents('.feedplayer');
		var audioId = 'audio-' + player.attr('id');
		soundManager.pause(audioId);
		$(this).hide();
		player.find('.feedplayer-play-button').show();
	});
	
	$('.feedplayer-next-button').click(function() {
		var player = $(this).parents('.feedplayer');
		if (player.find('.feedplayer-selected-item').size() == 0) {
			player.find('.feedplayer-inner-playlist').children()[0].click();
		}
		else {
			var next = player.find('.feedplayer-selected-item').next();
			if (next != null) {
				next.click();
			}
		}
	});
	
	$('.feedplayer-previous-button').click(function() {
		var player = $(this).parents('.feedplayer');
		if (player.find('.feedplayer-selected-item').size() != 0) {
			var previous = player.find('.feedplayer-selected-item').prev();
			if (previous != null) {
				previous.click();
			}
		}
	});
	
	$('.feedplayer-info-button').click(function() {
		var player = $(this).parents('.feedplayer');
		player.find('.feedplayer-inner-playlist').hide();
		player.find('.feedplayer-info').show();
		event.preventDefault();
	});
	
	$('.feedplayer-playlist-button').click(function() {
		var player = $(this).parents('.feedplayer');
		player.find('.feedplayer-info').hide();
		player.find('.feedplayer-inner-playlist').show();
		event.preventDefault();
	});
	
	$('.feedplayer-progress-bar').click(function(e) {
		var player = $(this).parents('.feedplayer');
		var id = player.attr('id');
		var audioId = 'audio-' + id;
		var audio = soundManager.getSoundById(audioId, true);
		if (audio == null) {
			// No audio loaded
			return;
		}
		var x = e.offsetX;
		var w = $('#' + id + ' .feedplayer-progress-bar').width();
		audio.setPosition(audio.duration * x / w);
	});
	
	$('.feedplayer-volume-bar').click(function(e) {
		var player = $(this).parents('.feedplayer');
		var id = player.attr('id');
		var audioId = 'audio-' + id;
		var audio = soundManager.getSoundById(audioId, true);
		if (audio == null) {
			// No audio loaded
			return;
		}
		var x = e.offsetX;
		var w = $('#' + id + ' .feedplayer-volume-bar').width();
		var pct = 100.0 * x / w;
		audio.setVolume(pct);
		jQuery('#' + id + ' .feedplayer-volume-indicator').css('width', pct + '%');
	});
});

function feedplayer_fetch_feed(id, url, items) {
	var data = {
		action: "get_feed",
		items: items,
		url: url
	};

	jQuery.post(params.ajaxurl, data, function(response) {
		var innerPlaylist = jQuery('#' + id + ' .feedplayer-playlist');
		innerPlaylist.removeClass('feedplayer-loading');
		innerPlaylist.html(response);
		
		jQuery('.feedplayer-item').click(function() {
			if (jQuery(this).hasClass('feedplayer-selected-item')) return;
			var player = jQuery(this).parents('.feedplayer');
			var audioId = 'audio-' + player.attr('id');
			player.find('.feedplayer-selected-item').removeClass('feedplayer-selected-item');
			jQuery(this).addClass('feedplayer-selected-item');
			var enclosure = jQuery(this).attr('enclosure');
			soundManager.destroySound(audioId);
			var sound = soundManager.createSound({
				id: audioId,
				url: enclosure,
				onconnect: function(bConnect) {
					// soundManager._writeDebug(this.id+' connected:
					// '+(bConnect?'true':'false'));
				},
				whileplaying: function() {
					var pct = 100.0 * this.position / this.duration;
					jQuery('#' + id + ' .feedplayer-progress-indicator').css('width', pct + '%');
				}
			});
			sound.stop();
			sound.play(); // will result in connection being made
			player.find('.feedplayer-play-button').hide();
			player.find('.feedplayer-pause-button').show();
		});
		
		jQuery('.feedplayer-item').hover(function() {
			jQuery(this).addClass('feedplayer-hovered-item');
		}, function() {
			jQuery(this).removeClass('feedplayer-hovered-item');
		});
	});
}
