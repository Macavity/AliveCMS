<?php

/*
|--------------------------------------------------------------------------
| News articles per page
|--------------------------------------------------------------------------
*/
$config['news_limit'] = 5;

/*
|--------------------------------------------------------------------------
| News Facebook Configuration
|--------------------------------------------------------------------------
*/
//Enable getting the news from your facebook wall 0 - Disable, 1 - Enable 
$config['news_by_facebook'] = false;

//The username of your group
$config['facebook_username'] = "UnforgivenWoW";

//IMPORTANT! you have to be admin on the page read facebook.txt in _README
$config['facebook_app_id'] = 439709492724999;
$config['facebook_app_secret'] = "0c6b57016ac2e7626225bd5c571c8cbc";

//The headline is created by the facebook post, set the length here.
$config['facebook_headline_length'] = 40;

//The id of the user on the website that posts everything regarding facebook.
$config['facebook_user_poster'] = 40160;


/*
|--------------------------------------------------------------------------
| External News Configuration
|--------------------------------------------------------------------------
*/

//Enable getting the news from an external source. false - Disable, true - Enable 
$config['news_by_external'] = true;
$config['news_external_source'] = "http://forum.wow-alive.de/external_news.php";

// Set if the generated html contains images with relative paths. No trailing slash.
$config['news_external_domain'] = "http://forum.wow-alive.de";

// Set if the generated html contains images with relative paths.
$config['news_external_more'] = "http://forum.wow-alive.de/forumdisplay.php?f=16";

