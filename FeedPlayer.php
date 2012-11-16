<?php
/*
 Plugin Name: Feed Player
Plugin URI: http://musicpodcasting.org/feedplayer/
Description: Feed Player for AMP
Version: 0.1.alpha
Author: AMP web team
Author URI: http://www.musicpodcasting.org/
Contributors:
Yannick Mauray (Euterpia Radio)
Dave Lee (The Bugcast)
Justin Wayne (The Justin Wayne Show)

Credits:

Copyright 2012 AMP

License: GPL (http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt)
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

	// 	$parser = YParser::parse($url, $items);

	// 	$html = '<div ';
	// 	if ($id != 'undefined') $html .= 'id="' . $id . '" ';
	// 	$html .= 'class="feedplayer">';

	// 	$html .= '<div class="feedplayer-controls">';
	// 	$html .= '<div class="feedplayer-main-button"><div class="feedplayer-button feedplayer-play-button"></div><div class="feedplayer-button feedplayer-pause-button"></div></div>';
	// 	$html .= '<div class="feedplayer-misc-buttons"><div><div class="feedplayer-previous-button"></div><div class="feedplayer-next-button"></div></div><div><a href="#" class="feedplayer-playlist-button">Playlist</a><a href="#" class="feedplayer-info-button">Info</a></div></div>';
	// 	$html .= '</div>';

	// 	$html .= '<div class="feedplayer-playlist">';
	// 	$html .= '<div class="feedplayer-inner-playlist">';
	// 	foreach ($parser->items as $item) {
	// 		if ($item->enclosure != null) {
	// 			$html .= '<div class="feedplayer-item" enclosure="' . $item->enclosure . '">' . $item->title . '<div class="feedplayer-item-info">' . $item->description . '</div></div>';
	// 		}
	// 	}
	// 	$html .= '</div>'; // feedplayer-inner-playlist
	// 	$html .= '<div class="feedplayer-info">Lorem ipsum dolor sit amet et fluctuat nec mergitur alea jacta est et caetera.</div>';
	// 	$html .= '</div>'; // feedplayer-playlist

	// 	$html .= '</div>'; // feedplayer

	$html = '<div ';
	if ($id != 'undefined') $html .= 'id="' . $id . '" ';
	$html .= 'class="feedplayer">';
	$html .= '<div class="feedplayer-controls">';
	$html .= '<div class="feedplayer-main-button"><div class="feedplayer-button feedplayer-play-button"></div><div class="feedplayer-button feedplayer-pause-button"></div></div>';
	$html .= '<div class="feedplayer-misc-buttons"><div><div class="feedplayer-previous-button"></div><div class="feedplayer-next-button"></div></div><div><a href="#" class="feedplayer-playlist-button">Playlist</a><a href="#" class="feedplayer-info-button">Info</a></div></div>';
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
	$translation_array = array('swf' => plugins_url('swf', __FILE__));
	$translation_array = array('ajaxurl' => admin_url('admin-ajax.php'));
	wp_localize_script('feedplayer-scritps', 'params', $translation_array);
	wp_enqueue_script('feedplayer-scritps');

	wp_register_script('feedplayer-soundmanager', plugins_url('js/soundmanager2.js', __FILE__));
	wp_enqueue_script('feedplayer-soundmanager');

	wp_register_style('feedplayer-styles', plugins_url('FeedPlayer.css', __FILE__));
	wp_enqueue_style('feedplayer-styles');
}

add_shortcode('feedplayer', 'feedplayer_shortcode');
add_action('wp_enqueue_scripts', 'feedplayer_enqueue_scripts');

add_action('wp_ajax_get_feed', 'get_feed_callback');
add_action('wp_ajax_nopriv_get_feed', 'get_feed_callback');

function get_feed_callback() {
	//echo 'hello from get_feed_callback, with url = ' . $_REQUEST['url'] . ' !';
	$url = $_REQUEST['url'];
	$items = $_REQUEST['items'];
	$parser = YParser::parse($url, $items);
	$html = '';
	foreach ($parser->items as $item) {
		if ($item->enclosure != null) {
			//$html .= '<div class="feedplayer-item" enclosure="' . $item->enclosure . '">' . $item->title . '<div class="feedplayer-item-info">' . $item->description . '</div></div>';
			$html .= '<div class="feedplayer-item" enclosure="' . $item->enclosure . '">' . $item->title . '</div>';
		}
	}
	echo $html;
	die();
}

//wp_register_style('jquery-ui', plugins_url('ui/jquery-ui.css', __FILE__));
//wp_enqueue_style('jquery-ui');


?>
