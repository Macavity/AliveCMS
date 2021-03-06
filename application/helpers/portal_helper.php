<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('debug'))
{
    function debug($label, $data = ""){

        $CI = &get_instance();
        if($CI->input->is_ajax_request()){
            return;
        }

        $string = "";
        
        if(is_array($label) || is_object($label))
            $string .= " ".print_r($label,true)." ";
        else
            $string .= " $label ";
        
        if($data === ""){
        }
        elseif(is_array($data) || is_object($data))
            $string .= " ".print_r($data,true)." ";
        else
            $string .= " $data ";
        
        echo "\n<!-- ".$string." -->";
    }  
}

if ( ! function_exists('makeWowheadLinks')){
    function makeWowheadLinks($string){
        if(preg_match("@https?://(www|de|old)\.wowhead.com/\??([^=]+=-?(\d+))@i", $string, $matches)){
            $temp = preg_replace("@https?://(www|de|old)\.wowhead.com/\??([^=]+=-?(\d+))@i", "<a href=\"$0\" target=\"_blank\">$0</a>", $string);
            if(!empty($temp))
                $string = $temp;
        }
        if(preg_match("@https?://(www|www)\.senzaii.net/server/bugtracker/bug/(\d+)/?@i", $string, $matches)){
            $temp = preg_replace("@https?://(www|www)\.senzaii.net/server/bugtracker/bug/(\d+)/?@i", "<a href=\"$0\" target=\"_blank\">$0</a>", $string);
            if(!empty($temp))
                $string = $temp;
        }
        return $string;
    }
}

if( ! function_exists("sec_to_dhms") ){

    function sec_to_dhms($sec, $show_days = false, $label = "")
    {
        $days = intval($sec / 86400);
        $hours = intval(($sec / 3600) % 24);
        $minutes = intval(($sec / 60) % 60);
        $seconds = intval($sec % 60);
        $string = array();
        if($days > 365){
            $years = floor($days / 365);
            $days = $days % 365;
            $string[] = ($years == 1) ? $years.' Jahr' : $years.' Jahre';
        }
        if($days > 0)
            $string[] = ($days == 1) ? $days.' Tag' : $days.' Tage';
        if($hours > 0)
            $string[] = $hours." Std.";
        if($minutes > 0)
            $string[] = $minutes." Min.";
        if($seconds > 0)
            $string[] = $seconds." Sek.";

        $string = implode(" ", $string);

        if(!empty($label))
            $string = $label.$string;
        return $string;
    }
}

if( ! function_exists("icon_class") ){

    function icon_class($class, $tooltip = true){
        if($tooltip){

            static $CI;

            if(!$CI){
                $CI = &get_instance();
            }

            $classLabel = $CI->realms->getClass($class);

            $output = '
	<span class="icon-frame frame-18" data-tooltip="'.$classLabel.'">
		<img src="/application/images/icons/18/class_'.$class.'.jpg" height="18" width="18">
	</span>';

        }
        else{
            $output = '
	<span class="icon-frame frame-18">
		<img src="/application/images/icons/18/class_'.$class.'.jpg" height="18" width="18">
	</span>';
        }

        return $output;
    }
}

if( ! function_exists("icon_faction") ){

    function icon_faction($faction, $tooltip = true){

        $label = ($faction == FACTION_ALLIANCE) ? lang('Alliance', 'pvp') : lang('Horde', 'pvp');

        $output = '
	<span class="icon-frame frame-18" data-tooltip="'.$label.'">
		<img src="/application/images/icons/18/faction_'.$faction.'.jpg" alt="" width="18" height="18" />
	</span>';

        return $output;
    }
}

if( ! function_exists("icon_race") ){

    function icon_race($raceId, $gender){

        static $CI;

        if(!$CI){
            $CI = &get_instance();
        }

        $label = $CI->realms->getRace($raceId, $gender);

        $output = '
        <span class="icon-frame frame-18" data-tooltip="'.$label.'">
		    <img src="/application/images/icons/18/race_'.$raceId.'_'.$gender.'.jpg" height="18" width="18">
	    </span>';

        return $output;
    }
}



if( ! function_exists("get_wow_icon") ){

    function get_wow_icon($size, $name, $greyscale = false)
    {

        $localPath = APPPATH.'/images/icons/'.$size.'/'.$name.'.jpg';
        $localPathGrey = APPPATH.'/images/icons/'.$size.'/'.$name.'.grey.jpg';

        $localImagePath = ($greyscale)
            ? $localPath
            : $localPathGrey;

        if(file_exists($localImagePath))
            return $localImagePath;

        /*
         * Image file not found, then download it first
         */
        if(!file_exists($localPath))
        {
            $url = "http://eu.media.blizzard.com/wow/icons/".$size."/".$id.".jpg";
            $contents = file_get_contents($url);

            // save the normal colored version
            if(strlen($contents) > 0)
                file_put_contents($localPath, $contents);
        }

        // if needed, convert the normal version to greyscale
        if($greyscale)
        {
            $imageFile = imagecreatefromjpeg($localPath);

            if($imageFile && imagefilter($imageFile, IMG_FILTER_GRAYSCALE))
            {
                imagepng($imageFile, $localPathGrey);
            }
            return $localPathGrey;
        }

        return $localPath;
    }
}


