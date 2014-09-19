<?php

/*
|--------------------------------------------------------------------------
| Enable/Disable internal and external news systems (useful if news is retrieved via a plugin).
| When both are enabled, news will be merged.
|--------------------------------------------------------------------------
*/
$config['news_internal'] = true;
$config['news_external'] = false;

/*
|--------------------------------------------------------------------------
| News articles per page
|--------------------------------------------------------------------------
*/
$config['news_limit'] = 5;

/*
|--------------------------------------------------------------------------
| RSS Feed configuration
|--------------------------------------------------------------------------
*/
$config['rss_feed_name'] = "Your Server";
$config['rss_description'] = "The best world of warcraft server in the world!";
$config['rss_lang'] = "en-us";

/*
|--------------------------------------------------------------------------
| External News Configuration
|--------------------------------------------------------------------------
| @alive
*/

//Enable getting the news from an external source. false - Disable, true - Enable
$config['news_external_source'] = "";

// Set if the generated html contains images with relative paths. No trailing slash.
$config['news_external_domain'] = "";

// Set if the generated html contains images with relative paths.
$config['news_external_more'] = "";
