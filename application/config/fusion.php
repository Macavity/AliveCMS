<?php

/**
 * @package FusionCMS
 * @version 6.0
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @link http://raxezdev.com/fusioncms
 */

/*
|--------------------------------------------------------------------------
| License key
|--------------------------------------------------------------------------
*/
$config['licenseKey'] = "rlWxV-wbvq2-93YJS-fnKMy-YzEyV-vjvnF-V6VwZ-0AlVf-VzfvB-vWxMT-R4BTS-yZmZj-MGHmL-GVvsD-==";

/*
|--------------------------------------------------------------------------
| ACP security code
|--------------------------------------------------------------------------
|
| An additional password to add another layer of security
|
*/
$config['security_code'] = "cocktail";

/*
|--------------------------------------------------------------------------
| Theme name
|--------------------------------------------------------------------------
|
| Must be located in application/views/NAME and must contain a valid theme manifest
|
*/
$config['theme'] = "shattered";

/*
|--------------------------------------------------------------------------
| Website title
|--------------------------------------------------------------------------
*/
$config['title'] = "WoW Alive Portal";

/*
|--------------------------------------------------------------------------
| Server name
|--------------------------------------------------------------------------
*/
$config['server_name'] = "WoW Alive";

/*
|--------------------------------------------------------------------------
| Realmlist
|--------------------------------------------------------------------------
*/
$config['realmlist'] = "178.63.89.20:3704";

/*
|--------------------------------------------------------------------------
| Use rewrite engine
|--------------------------------------------------------------------------
|
| Whether to hide index.php from the URL or not
| Requires Apache module "rewrite_module", to enable it remove the # in
| front of "LoadModule rewrite_module modules/mod_rewrite.so" in the Apache
| config file "httpd.conf"
|
*/
$config['rewrite'] = true;

/*
|--------------------------------------------------------------------------
| SMTP server
|--------------------------------------------------------------------------
|
| If you don't have a SMTP server put it to false. The password recovery
| will be disabled if you disable this.
|
*/
$config['has_smtp'] = 1;

/*
|--------------------------------------------------------------------------
| Disabled expansions
|--------------------------------------------------------------------------
|
| You may disable expansions by their expansion ids, in case you're
| running a WoTLK server and don't want accounts to have Cataclysm
| expansion enabled.
|
| array(3) would disable Cataclysm while array(2,3) would disable both
| WoTLK and Cataclysm.
|
*/
$config['disabled_expansions'] = array(3);

/*
|--------------------------------------------------------------------------
| Custom header image
|--------------------------------------------------------------------------
*/
$config['header_url'] = false;

/*
|--------------------------------------------------------------------------
| Image slider module
|
| ['slider'] Enable or disable slider
| ['slider_home'] Hide slider from any page except of 'Home'
| ['slider_intveral'] How long the delay should be between the images (in milliseconds, default is 3000 (3 seconds))
| ['slider_style'] Animation style (leave blank for "random"):
|
|	2D:
|		bars
|		blinds
|		blocks
|		blocks2
|		concentric
|		dissolve requires mask support
|		slide
|		warp
|		zip
|	3D:
|		bars3d
|		blinds3d
|		cube
|		tiles3d
|		turn
|
|--------------------------------------------------------------------------
*/
$config['slider'] = true;
$config['slider_home'] = true;
$config['slider_interval'] = 5000;
$config['slider_style'] = false;

/*
|--------------------------------------------------------------------------
| Enable vote reminder popup
|--------------------------------------------------------------------------
|
| ['vote_reminder'] Whether or not to display a popup message that reminds the users to vote
| every (by default) 12 hours.
|
| ['reminder_interval'] How often the reminder should be displayed, in seconds
| Default is 60 * 60 * 12, which represents 12 hours
|
*/
$config['vote_reminder'] = 0;
$config['vote_reminder_image'] = "/application/images/misc/banner.jpg";
$config['reminder_interval'] = 108000;

/*
|--------------------------------------------------------------------------
| Search engine related
|--------------------------------------------------------------------------
|
| ['keywords'] Content keywords, separated by comma
| ['description'] Brief description of your site
|
*/
$config['keywords'] = "alive, wow alive, 4life, wow4life, wow, world of warcraft, private server, privatserver";
$config['description'] = "WoW Alive";

/*
|--------------------------------------------------------------------------
| Use Content Delivery Network for Javascript libraries
|--------------------------------------------------------------------------
|
| Reduce load and improve loading times by enabling this.
| If you're on a LAN environment put it to false, otherwise
| the website won't load since it relies on those libraries.
|
*/
$config['cdn'] = 1;

/*
|--------------------------------------------------------------------------
| Use FusionCMS tooltip system instead of WoWHead tooltips
|--------------------------------------------------------------------------
|
| Put to false if you mainly have "blizzlike" items.
|
*/
$config['use_fcms_tooltip'] = true;

/*
|--------------------------------------------------------------------------
| Use Smileys
|--------------------------------------------------------------------------
|
| This setting goes to where your smiley folder is, if you 
| change the emoticons in it then don't forget to change 
| the smileys.php
|
*/
$config['smiley_path'] = "application/images/emoticons/";


/*
|--------------------------------------------------------------------------
| Enable Google Analytics
|--------------------------------------------------------------------------
|
| In case you want to track your visitor statistics more deeply,
| Google Analytics can be enabled by entering your Tracking ID here
| (found on the "Admin" page)
|
| Set it to false if you don't use it
|
| Example: UA-XXXXXXXX-1, format may differ
|
*/
// For live use: UA-15760448-1
$config['analytics'] = false;

/*
|--------------------------------------------------------------------------
| Cache system
|--------------------------------------------------------------------------
|
| Wether or not the cache should be enabled. Disable only for development.
| Turning it on will improve performance drastically.
|
*/
$config['cache'] = false;

/*
|--------------------------------------------------------------------------
| Ask for permission to use cookies
|--------------------------------------------------------------------------
|
| Due to this retarded EU cookie law: http://www.youtube.com/watch?v=arWJA0jVPAc
| people may want to ask their users for permission to store cookies
|
*/
$config['cookie_law'] = true;

/*
|--------------------------------------------------------------------------
| DEVELOPER CONFIGS (don't touch if you don't know what you're doing)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Guest Rank
|--------------------------------------------------------------------------
|
| ['default_guest_rank'] This defines the gmlevel of the guest rank.
| ['default_guest_id'] This defines the rank ID (see ranks table) of the guest rank
|
*/
$config['default_guest_rank'] = -1;
$config['default_guest_id'] = 1;

/*
|--------------------------------------------------------------------------
| Player Rank
|--------------------------------------------------------------------------
|
| ['default_player_rank'] This defines the gmlevel of the player rank.
| ['default_player_id'] This defines the rank ID (see ranks table) of the player rank
|
*/
$config['default_player_rank'] = 0;
$config['default_player_id'] = 2;