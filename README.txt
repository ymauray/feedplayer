* So, what does this plugin do ? *
----------------------------------

It adds a new shortcode to WordPress, [feedplayer].

This shortcode will take two parameters and an optional third :

- id : is the HTML id for the player
- url : is the rss feed the player will take its information from
- items : an optional maximum number of items to fetch from the feed.

So to use the plugin, you would add something like that, in a page or in a template, or - even better - in a text widget that you'd then add to a sidebar :

[feedplayer id="sideplayer" url="http://www.mydomain.com/myrssfeed.xml" items=20]

* Things you'll want to customize *
-----------------------------------

You can either change the plugin's CSS (not recommanded !) or add customization to your theme's CSS.

Either way, you'll want to use those selectors. I strongly suggest that you prefix them with "#<id of the player>"

/* The height of this element is not the total height. Just the height of the playlist. */
.feedplayer-playlist {
        font-size: x-small;
        height: 120px;
}

/* This applies to both the "play" and "pause" button */
.feedplayer-button {
        width: 30px;
        height: 30px;
}

/* This applies to the container around the "play" and "pause" buttons */
.feedplayer-main-button {
        padding-right: 5px;
}

/* The "play" button */
.feedplayer-play-button {
        background-image: url("img/play.png");
}

/* The "pause" button */
.feedplayer-pause-button {
        background-image: url("img/pause.png");
}

/* The currently selected item in the playlist */
.feedplayer-selected-item {
        background-color: #4D469C;
}

/* An playlist item that has the mouse hovering over it */
.feedplayer-hovered-item {
        background-color: #3778CD;
}

/* The "previous track" mini button */
.feedplayer-previous-button {
        background-image: url('img/prev-17.png');
        width: 17px;
        height: 17px;
        display: inline-block;
        margin-right: 5px;
}

/* The "next track" mini button */
.feedplayer-next-button {
        background-image: url('img/next-17.png');
        width: 17px;
        height: 17px;
        display: inline-block;
        margin-right: 5px;
}

/* The "playlist" and "info" buttons */
.feedplayer-misc-buttons a {
        font-size: xx-small;
        padding: 0px 5px 0 5px;
        margin: 0px 5px 0 0;
        display: inline-block;
}

/* The "playlist" button */
.feedplayer-playlist-button {
}

/* The "info" button */
.feedplayer-info-button {
}

/* The container around the player */
.feedplayer {
        border: 1px solid white;
}

/* The inner playlist - the part that actually contains the items to click on */
.feedplayer-inner-playlist {
        padding-left: 2px;
}

/* The container around all the buttons and the progress bar */
.feedplayer-controls {
        border-bottom: 1px solid white;
        padding: 3px;
}

/* This container will display your podcast info, taken from the feed. */
.feedplayer-info {
        padding: 2px;
}

/* This is the progress bar */
.feedplayer-progress-bar {
        border: 1px solid white;
        display: inline-block;
        height: 5px;
        width: 120px;
        border-radius: 5px;
}

/* This indicator shows the position of "the play head" inside the progress bar */
.feedplayer-progress-indicator {
        background: white;
        height: 100%;
        width: 0%;
}

.feedplayer-volume-bar {
	border: 1px solid white;
	display: inline-block;
	height: 5px;
	width: 49px;
	border-radius: 5px;	
}

.feedplayer-volume-indicator {
	background: white;
	height: 100%;
	width: 50%;
}
