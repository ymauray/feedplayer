<?php
/*
Plugin Name: Feed Player
Plugin URI: http://yma.dk/wordpress/feedplayer/
Description: HTML5 audio player capable of reading a podcast feed. 
Version: 1.1
Author: Yannick Mauray
Author URI: http://yma.dk/wordpress
Contributors:
Yannick Mauray (Euterpia Radio)
Justin Wayne (The Justin Wayne Show)
Peter Clitheroe (Suffolk'n'Cool)

Credits:

Copyright 2012 Yannick Mauray

License: GPL v3 (http://www.gnu.org/licenses/gpl-3.0.txt)
*/

/*
 * Encoding: UTF-8
*/

require_once('parser/parser.php');

function feedplayer_shortcode($atts, $content = null) {

	extract(shortcode_atts(array(
			'id' => 'undefined',
			'url' => 'undefined',
			'items' => 0
	), $atts));

	if ($url == 'undefined') return;

	$html = '<div ';
	if ($id != 'undefined') $html .= 'id="' . $id . '" ';
	$html .= 'class="feedplayer">';
	$html .= '<div class="feedplayer-controls">';
	$html .= '<div class="feedplayer-main-button"><div class="feedplayer-button feedplayer-play-button"></div><div class="feedplayer-button feedplayer-pause-button"></div></div>';
	$html .= '<div class="feedplayer-misc-buttons"><div><div class="feedplayer-button feedplayer-previous-button"></div><div class="feedplayer-button feedplayer-next-button"></div><div class="feedplayer-progress-bar"><div class="feedplayer-progress-indicator"></div></div></div><div><a href="#" class="feedplayer-playlist-button">Playlist</a><a href="#" class="feedplayer-info-button">Info</a><span class="feedplayer-volume-label">Vol. :</span><div class="feedplayer-volume-bar"><div class="feedplayer-volume-indicator"></div></div></div></div>';
	$html .= '</div>'; // feedplayer-controls

	$html .= '<div class="feedplayer-playlist">';
	$html .= '<div class="feedplayer-inner-playlist feedplayer-loading">';
	$html .= 'Loading, please wait...';
	$html .= '<script>jQuery(document).ready(function($) {feedplayer_fetch_feed("' . $id . '", "' . $url . '", ' . $items . ');})</script>';
	$html .= '</div>'; // feedplayer-inner-playlist
	$html .= '<div class="feedplayer-info"></div>';
	$html .= '</div>'; // feedplayer-playlist

	$html .= '</div>'; // feedplayer

	return $html;
}

function feedplayer_enqueue_scripts() {
	wp_register_script('feedplayer-scritps', plugins_url('FeedPlayer.js', __FILE__), array('jquery', 'jquery-ui-datepicker'));
	$translation_array = array(
			'swf' => plugins_url('swf', __FILE__),
			'popouturl' => plugins_url('PopOut.php', __FILE__),
			'ajaxurl' => admin_url('admin-ajax.php')
	);
	wp_localize_script('feedplayer-scritps', 'params', $translation_array);
	wp_enqueue_script('feedplayer-scritps');

	wp_register_script('feedplayer-soundmanager', plugins_url('js/soundmanager2.js', __FILE__));
	wp_enqueue_script('feedplayer-soundmanager');

	wp_register_style('feedplayer-styles', plugins_url('FeedPlayer.css', __FILE__));
	wp_enqueue_style('feedplayer-styles');
}

add_shortcode('feedplayer', 'feedplayer_shortcode');

add_action('init', 'feedplayer_activate_autoupdate');  
add_action('wp_enqueue_scripts', 'feedplayer_enqueue_scripts');
add_action('wp_ajax_get_feed', 'get_feed_callback');
add_action('wp_ajax_nopriv_get_feed', 'get_feed_callback');

function feedplayer_activate_autoupdate()
{
	require_once ('autoupdate/wp_autoupdate.php');
	$current_version = feedplayer_get_version();
	$slug = plugin_basename(__FILE__);
	$remote_path = 'http://yma.dk/wordpress/update/FeedPlayer.php';
	new wp_auto_update($current_version, $remote_path, $slug);
}

function get_feed_callback() {
	$url = $_REQUEST['url'];
	$items = $_REQUEST['items'];
	$parser = YParser::parse($url, $items);
	$html = '<div class="feedplayer-inner-playlist">';
	foreach ($parser->items as $item) {
		if ($item->enclosure != null) {
			//$html .= '<div class="feedplayer-item" enclosure="' . $item->enclosure . '">' . $item->title . '<div class="feedplayer-item-info">' . $item->description . '</div></div>';
			$html .= '<div class="feedplayer-item" enclosure="' . $item->enclosure . '">' . $item->title . '</div>';
		}
	}
	$html .= '</div>'; // feedplayer-inner-playlist
	$html .= '<div class="feedplayer-info">' . $parser->description . '</div>';
	echo $html;
	die();
}

function feedplayer_get_version() {
	if (!function_exists('get_plugins'))
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	$plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
	$plugin_file = basename((__FILE__));
	return $plugin_folder[$plugin_file]['Version'];
}

?>
