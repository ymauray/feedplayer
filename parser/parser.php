<?php
class YFeedItem {
	public $title;
	public $date;
	public $timestamp;
	public $enclosure;
	public $enclosure_type;
	public $description;
};

class YFeed {
	public $title;
	public $link;
	public $copyright;
	public $description;

	public $items;
}

class YRSSFeed extends YFeed {
	
	public function parse($x, $items = 0) {
		if (count($x->channel) != 1) {
			throw new Exception($x->channel . ' channel(s) detected in the feed.');
		}
		$c = $x->channel;
		$this->title = $c->title;
		$this->link = $c->link;
		$this->copyright = $c->copyright;
		$this->description = $c->description;
		foreach ($c->item as $i) {
			$item = new YFeedItem();
			$item->title = (string) $i->title;
			$item->date = (string) $i->pubDate;
			$item->timestamp = strtotime($i->pubDate);
			$e = $i->enclosure;
			if (sizeof($e) > 0) {
				$a = $e->attributes();
				$item->enclosure = $a['url'];
				$item->enclosure_type = $a['type'];
			}
			if (sizeof($i->description) > 0) {
				$item->description = $i->description;
			}
			$this->items[] = $item;
			if ($items > 0) {
				$items--;
				if ($items == 0) break;
			}
		}
		
		return $this;
	}
};

class YParser {
	public static function parse($feed_uri, $items = 0) {
		$xml_source = file_get_contents($feed_uri);
		$x = simplexml_load_string($xml_source);
		$file_type = $x->getName();
		if ($file_type == 'rss') {
			$feed = new YRSSFeed();
			return $feed->parse($x, $items);
		} else {
			throw new Exception('Unknown file type : ' . $file_type);
		}
	}
}
?>
