<?php
/**
 * @package FusionCMS
 * @version 6.0
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @link http://raxezdev.com/fusioncms
 */
class Template
{
	protected $CI;
	public $theme_path;
	public $page_path;
	public $full_theme_path;
	public $image_path;
	public $theme;
	public $page_url;
	public $theme_data;
	public $style_path;
	public $view_path;
	public $module_name;
	private $title;
	private $showSlider;
    
    private $showSidebar = TRUE;
    private $topHeader = "";
    
    /**
     * Shows/Hides the breadcrumbs
     * @type {Boolean}
     */
    private $showBreadcrumbs = FALSE;
    
    /**
     * Contains the content trail of breadcrumbs
     * @type {Array}
     */
    private $breadcrumbs = array();
    
	private $custom_description;
	private $custom_keywords;

	/**
	 * Get the CI instance and construct the paths
	 */
	public function __construct()
	{
		$this->CI = &get_instance();

		// Get the theme name
		$this->theme = $this->CI->config->item('theme');

		// Construct the paths
		$this->module_name = $this->CI->router->fetch_module();
		$this->theme_path = "themes/".$this->theme."/";
		$this->view_path = "views/";
		$this->style_path = base_url().APPPATH."themes/".$this->theme."/css/";
		$this->image_path = base_url().APPPATH."themes/".$this->theme."/images/";
        $this->js_path = base_url().APPPATH."themes/".$this->theme."/js/";
		$this->page_url = ($this->CI->config->item('rewrite')) ? base_url() : base_url().'index.php/';
		$this->loadManifest();
		$this->title = "";

		// Check if module manifest exists or not
		if(file_exists("application/modules/".$this->module_name."/manifest.json"))
		{
			// Load the manifest data
			$moduleManifestData = file_get_contents("application/modules/".$this->module_name."/manifest.json");
			$moduleManifest = json_decode($moduleManifestData, true);

			// Check for the enabled flag
			if(!array_key_exists("enabled", $moduleManifest) || !$moduleManifest['enabled'])
			{
			    if($this->CI->input->is_ajax_request() && isset($_REQUEST['is_json_ajax']) && $_REQUEST['is_json_ajax'] == 1)
				{
					$array = array(
						"title" => "The module has been disabled", 
						"content" => "<script>window.location.reload(true)</script>",
						"js" => "",
						"css" => "",
						"slider" => false
					);

					die(json_encode($array));
				}
				else
				{
					show_error("The module (".$this->module_name.") is not enabled.");
				}
			}
		}
		else
		{
			show_error("Invalid module (<b>".$this->module_name."</b> is missing manifest.json)");
		}

		if(!defined("pageURL"))
		{
			define("pageURL", $this->page_url);
		}

		$this->preSlider();
        
        // Breadcrumb to the homepage
        $this->addBreadcrumb($this->CI->config->item("server_name"), base_url());
        $this->hideBreadcrumbs();
	}

	/**
	 * Determinate if we should show the slider or not
	 */
	private function preSlider()
	{
		// Should we display the slider on this page?
		if($this->CI->config->item('slider'))
		{
			// Is it meant to be displayed only on the news page
			if($this->CI->config->item('slider_home')
			&& $this->CI->router->class == "news")
			{
				$this->showSlider = true;
			}

			// If it's not on the news page
			elseif($this->CI->config->item('slider_home')
			&& $this->CI->router->class != "news")
			{
				$this->showSlider = false;
			}

			// Simply enabled
			else
			{
				$this->showSlider = true;
			}
		}
		else
		{
			$this->showSlider = false;
		}
	}

	/**
	 * Display the global announcement message
	 */
	private function announcement()
	{
		$data = array(
				'module' => 'default',
				'title' => $this->CI->config->item("title"),
				'headline' => $this->CI->config->item("message_headline"),
				'message' => $this->CI->config->item("message_text"),
				'size' => $this->CI->config->item('message_headline_size')
			);

		$output = $this->loadPage("message.tpl", $data);
		
		die($output);
	}

	/**
	 * Loads the current theme values
	 */
	private function loadManifest()
	{
		if(!file_exists(APPPATH.$this->theme_path))
		{
			show_error("Invalid theme. The folder <b>".APPPATH.$this->theme_path."</b> doesn't exist!");
		}
		elseif(!file_exists(APPPATH.$this->theme_path."/manifest.json"))
		{
			show_error("Invalid theme. The file <b>manifest.json</b> is missing!");
		}

		// Load the manifest
		$data = file_get_contents(APPPATH.$this->theme_path."manifest.json");

		// Convert to array
		$array = json_decode($data, true);

		// Fix the favicon link
		$array['favicon'] = $this->image_path.$array['favicon'];
		
		if(!isset($array['blank_header'])) {$array['blank_header'] = '';}

		// Save the data
		$this->theme_data = $array;
	}

	/**
	 * Add an extra page title
	 * @param String $title
	 */
	public function setTitle($title)
	{
		$this->title = $title . " - ";
	}

	/**
	 * Add an extra description
	 * @param String $description
	 */
	public function setDescription($description)
	{
		$this->custom_description = $description;
	}

	/**
	 * Add extra keywords
	 * @param String $keywords
	 */
	public function setKeywords($keywords)
	{
		$this->custom_keywords = $keywords;
	}
	
	/**
	 * Loads the template
	 * @param String $content The page content
	 * @param Array $css Full path to your css file
	 * @param String $js Full path to your js file
	 */
	public function view($content, $css = false, $js = false)
	{
		if($this->CI->config->item("message_enabled"))
		{
			$this->announcement();
		}

		if($this->CI->input->is_ajax_request() && isset($_REQUEST['is_json_ajax']) && $_REQUEST['is_json_ajax'] == 1)
		{
		    $array = array(
				"title" => $this->title.$this->CI->config->item('title'), 
				"content" => $content,
				"js" => $js,
				"css" => $css,
				"slider" => $this->showSlider
			);

			$this->outputJSON($array);
		}
		
        // Extra CSS Files
        if(is_string($css)){
            $css = array($css);
        }
        
		//Load the sideboxes 
		$sideboxes = $this->loadSideboxes();
		        
                
		// Gather the header data
		$header_data = array(
		    "controller" => $this->CI->router->class,
            "method" => $this->CI->router->method,
			"style_path" => $this->style_path,
			"theme_path" => $this->theme_path,
			"image_path" => $this->image_path,
			"js_path" => $this->js_path,
			"url" => $this->page_url,
            "server_name" => $this->CI->config->item('server_name'),
			"title" => $this->title . $this->CI->config->item('title'),
			"slider_interval" => $this->CI->config->item('slider_interval'),
			"slider_style" => $this->CI->config->item('slider_style'),
			"vote_reminder" => $this->voteReminder(),
			"keywords" => ($this->custom_keywords) ? $this->custom_keywords : $this->CI->config->item("keywords"),
			"description" => ($this->custom_description) ? $this->custom_description : $this->CI->config->item("description"),
			"menu_top" => $this->getMenu("top"),
			"menu_side" => $this->getMenu("side"),
			"path" => base_url().APPPATH,
			"favicon" => $this->theme_data['favicon'],
			"cdn" => $this->CI->config->item('cdn'),
			"extra_css" => $css,
			"extra_js" => $js,
			"analytics" => $this->CI->config->item('analytics'),
			"use_fcms_tooltip" => $this->CI->config->item('use_fcms_tooltip'),
			"slider" => $this->theme_data['slider_text'],
			"slider_id" => $this->theme_data['slider_id'],
			"cookie_law" => $this->CI->config->item('cookie_law'),
			"csrf_cookie" => $this->CI->input->cookie('csrf_token_name')
		);

		// Load the theme
		
		// Is there a specified header.tpl for the current theme?
        if(file_exists(APPPATH.$this->theme_path."views/header.tpl")){
            //debug("themed header", APPPATH.$this->theme_path);
            $header = $this->CI->smarty->view($this->theme_path."views/header.tpl", $header_data, true);
            
        }
        else{
            $header = $this->CI->smarty->view($this->view_path."header.tpl", $header_data, true);
        }
		
		$modal_data = array(
			'url' => $this->page_url,
			'vote_reminder' => $this->CI->config->item('vote_reminder'),
			'vote_reminder_image' => $this->CI->config->item('vote_reminder_image')
		);

		// Load the modals
		$modals = $this->CI->smarty->view($this->theme_path."views/modals.tpl", $modal_data, true);

		$url = $this->CI->router->fetch_class();

		if($this->CI->router->fetch_method() != "index")
		{
			$url .= "/".$this->CI->router->fetch_method();
		}
        
        /*
         * Breadcrumbs
         */
        
        /**
         * Contains the breadcrumb html if activated
         * @type String
         */
        $breadCrumbs = "";
        
        if($this->showBreadcrumbs == TRUE && !empty($this->breadcrumbs)){
            $data = array(
                "show_breadcrumbs" => $this->showBreadcrumbs,
                "breadcrumbs" => $this->breadcrumbs,
            );
            $breadCrumbs = $this->CI->smarty->view($this->theme_path."views/breadcrumbs.tpl", $data, true);
        }
        
        /*
         * Slider
         */
        $slider = "";
        
        /**
         * Standard path to the slider template file
         */
        $sliderTplPath = $this->view_path."slider.tpl";
        
        if($this->showSlider){
            
            $data = array(
                "slider" => $this->getSlider(),
                "show_slider" => $this->showSlider,
            );
            
            // Template specific slider?
            if(file_exists(APPPATH.$this->theme_path."views/slider.tpl")){
                $sliderTplPath = $this->theme_path."views/slider.tpl";
            }
            $slider = $this->CI->smarty->view($sliderTplPath, $data, true);
            
        }
        
        /*
         * Userplate
         */
        $userPlate = $this->getUserplate();
        
        
        // Gather the theme data
		$theme_data = array(
			"currentPage" => $url,
			"url" => $this->page_url,
			"theme_path" => $this->theme_path,
			"full_theme_path" => $this->page_url."application/".$this->theme_path,
			"serverName" => $this->CI->config->item('server_name'),
			"page" => '<div id="content_ajax">'.$content.'</div>',
			"slider" => $slider,
			"show_slider" => $this->showSlider,
			"show_sidebar" => $this->showSidebar,
            "topheader" => $this->topHeader,
			"head" => $header,
			"modals" => $modals,
			"breadcrumbs" => $breadCrumbs,
			"userplate" => $userPlate,
			"CI" => $this->CI,
			"image_path" => $this->image_path,
            "isOnline" => $this->CI->user->isOnline(),
            "is_gm" => $this->CI->user->isGm(),
            "is_admin" => $this->CI->user->isAdmin(),
            "is_dev" => $this->CI->user->isDev(),
            "is_owner" => $this->CI->user->isOwner(),
            "user_name" => $this->CI->user->getNickname(),
			"header_url" => ($this->CI->config->item('header_url')) ? "style='background-image:url(".$this->CI->config->item('header_url').")'" : "",
			"sideboxes" => $sideboxes,
		);

		// Load the main template
		$output = $this->CI->smarty->view($this->theme_path."template.tpl", $theme_data, true);

		die($output);
	}

	/**
	 * Determinate whether or not we should show the vote reminder popup
	 * @return String
	 */
	private function voteReminder()
	{
		if($this->CI->config->item('vote_reminder')
		&& !$this->CI->input->cookie("vote_reminder"))
		{
			$this->CI->input->set_cookie("vote_reminder", "1", $this->CI->config->item('reminder_interval'));
			
			return true;
		}
		else
		{
			return false;
		}
	}

	public function loadSideboxes()
	{
		require_once("application/interfaces/sidebox.php");
		
		$out = array();
        
        $controller = $this->CI->router->class;
        $method = $this->CI->router->method;
        
		$sideboxes_db = $this->CI->cms_model->getSideboxes($controller, $method);
		
		foreach($sideboxes_db as $sidebox)
		{
			$fileLocation =  'application/modules/sidebox_'.$sidebox['type'].'/controllers/'.$sidebox['type'].'.php';

			if(file_exists($fileLocation))
			{
				require_once($fileLocation);

				if($sidebox['type'] == 'custom')
				{
					$object = new $sidebox['type']($sidebox['id']);
				}
				else 
				{
					$object = new $sidebox['type']();
				}
                
				if($this->CI->user->requireRank($sidebox['rank_needed'], false))
				{
					
                    $sideboxName = $sidebox["displayName"];
                    $sideboxData = $object->view();
                    
                    if(!empty($object->overwriteDisplayName)){
                        $sideboxName = $object->overwriteDisplayName;
                    }
                    
                    array_push($out, array(
                       'name' => $sideboxName, 
                       'css_id' => (empty($sidebox['css_id'])) ? "sidebox-".$sidebox['id'] : $sidebox['css_id'], 
                       'data' => $sideboxData
                    ));
                    
				}
			}
			else
			{
				array_push($out, array(
				    'name' => "Oops, something went wrong", 
				    "css_id" => "",
				    'data' => 'The following sidebox module is missing: <b>'.$sidebox['type'].'</b>'));
			}
		}
		
		return $out;
	}

	/**
	 * Load a page template
	 * @param String $page Filename
	 * @param Array $data Array of additional template data
	 * @return String
	 */
	public function loadPage($page, $data = array(), $json = false)
	{
		// Determinate which module to load from
		if(array_key_exists('module', $data))
		{
			$module = $data['module'];
		}
		else
		{
			$module = $this->module_name;
		}

		// Should we load from the default views or not?
		if($module == "default")
		{
			// Shorthand for loading views/page.tpl
			$page = ($page == "page.tpl") ? "views/page.tpl" : $page;
			
			return $this->CI->smarty->view($this->theme_path . $page, $data, true, true);
		}
		else
		{
			// Default data
			$data['url'] = array_key_exists("url", $data) ? $data['url'] : $this->page_url;
			$data['theme_path'] = array_key_exists("theme_path", $data) ? $data['theme_path'] : $this->theme_path;
			$data['image_path'] = array_key_exists("image_path", $data) ? $data['image_path'] : $this->image_path;

			// Consruct the path
			$themeView = "application/" . $this->theme_path . "modules/" . $module . "/" . $page;
			
			// Check if this theme wants to replace our view with it's own
			if(file_exists($themeView))
			{
				return $this->CI->smarty->view($themeView, $data, true);
			}
			else
			{
				return $this->CI->smarty->view('modules/'.$module.'/views/'.$page, $data, true);
			}
		}
	}

	/**
	 * Shorthand for loading a content box
	 * @param String $title
	 * @param String $body
	 * @param Boolean $full
	 * @return String
	 */
	public function box($title, $body, $full = false, $css = false, $js = false)
	{
		$data = array(
				"module" => "default", 
				"headline" => $title, 
				"content" => $body
			);

		$page = $this->loadPage("page.tpl", $data);

		if($full)
		{
			$this->view($page, $css, $js);
		}
		else
		{
			return $page;
		}
	}
	
    /**
     * Generates the Userplate, used to switch the active character
     */
    public function getUserplate(){
            
        
        $data = array(
            "isOnline" => $this->CI->user->isOnline(),
            "charList" => array(),
            "nickname" => $this->CI->user->getNickname(),
            "image_path" => $this->image_path,
            "url" => $this->page_url,
        );
        if($this->CI->user->isOnline()){
            
            /**
             * List of all realms with all characters on each realm
             * @type Array
             */
            $realmChars = $this->CI->user->getCharacters($this->CI->user->getId());    
                
            $charList = array();
            $activeChar = array();
            
            $activeCharFound = FALSE;
            
            //debug("realmChars", $realmChars);
            //debug("activeGuid", $this->CI->user->getActiveChar());
            
            $n = 0;
            
            foreach($realmChars as $realmRow){
                
                $realmId = $realmRow["realmId"];
                $realmName = "Norganon";
                
                foreach($realmRow["characters"] as $charRow){
                    
                    $charRow["realmId"] = $realmId;
                    $charRow["realmName"] = $realmName;
                    $charRow["url"] = "/characters/".strtolower($realmName)."/".$charRow["name"]."/";
                    $charRow["hasGuild"] = FALSE;
                    
                    $charRow["classString"] = $this->CI->realms->getClass($charRow["class"], $charRow["gender"]);
                    $charRow["raceString"] = $this->CI->realms->getRace($charRow["race"], $charRow["gender"]);
                    
                    if($charRow["guid"] == $this->CI->user->getActiveChar() && $realmId == $this->CI->user->getActiveRealm()){
                        $activeCharFound = TRUE;
                        $activeChar = $charRow;
                    }
                    else{
                        $charList[$n] = $charRow;
                        $n++;
                    }
                }
                
            }
             
            if(!$activeCharFound && count($charList) > 0){
                
                //debug("0er", $charList[0]);
                $this->CI->user->setActiveChar($charList[0]["guid"], $charList[0]["realmId"]);
                $activeCharFound = true;
                $activeChar = $charList[0];
                unset($charList[0]);
                
            }
            
            if($activeChar){
                $activeRealm = $this->CI->realms->getRealm($this->CI->user->getActiveRealm())->getCharacters();
                
                $data["factionString"] = $this->CI->realms->getFactionString($activeChar["race"]);
                
                $guildId = $activeRealm->getGuild($this->CI->user->getActiveChar());
                
                $activeChar["avatarUrl"] = $this->CI->realms->formatAvatarPath($activeChar);
                
                if($guildId){
                    $activeChar["hasGuild"] = TRUE;
                    $activeChar["guildName"] = $activeRealm->getGuildName($guildId);
                    $activeChar["guildUrl"] = "/guild/".strtolower($activeChar["realmName"])."/".$activeChar["guildName"]."/";
                }
                
            }
            else{
                $data["factionString"] = "neutral";
            }
            
            $data["activeChar"] = $activeChar;
            $data["charList"] = $charList;
            
        }
        return $this->CI->smarty->view($this->theme_path."views/userplate.tpl", $data, true);
        
        
    }
    
	/**
	 * Get the menu links
	 * @param Int $side ID of the specific menu
	 */
	public function getMenu($side = "top") 
	{
		//Get the database values
		$result = $this->CI->cms_model->getLinks($side);
		
		foreach($result as $key=>$item)
		{
			//Xss protect out names
			$result[$key]['name'] = $this->format($result[$key]['name'], false, false);
			
			// Hard coded PM count
			if($result[$key]['link'] == "messages")
			{
				$count = $this->CI->cms_model->getMessagesCount();

				if($count > 0)
				{
					$result[$key]['name'] .= " <b>(".$count.")</b>";
				}
			}

			if(!preg_match("/http:\/\//i", $result[$key]['link']))
			{
				$result[$key]['link'] = $this->page_url . $result[$key]['link'];
			}
			
			//Append if it's a direct link or not
			$result[$key]['link'] = 'href="'.$result[$key]['link'].'" direct="'.$result[$key]['direct_link'].'"';
		}

		return $result;
	}
    
    /**
     * Hides the sidebar if requested
     */
    public function hideSidebar(){
        $this->showSidebar = FALSE;
    }
    
    /**
     * Shows the sidebar if requested
     */
    public function showSidebar(){
        $this->showSidebar = TRUE;
    }

	/**
	 * Load the image slider
	 */
	public function getSlider()
	{
		// Load the slides from the database
		$slides_arr = $this->CI->cms_model->getSlides();

		foreach($slides_arr as $key=>$image)
		{
			if(!preg_match("/http:\/\//i", $image['link']) || !preg_match("/https:\/\//i", $image['link']))
			{
				$slides_arr[$key]['link'] = $this->page_url . $image['link'];
			}

			// Replace {path} by the theme image path
			$slides_arr[$key]['image'] = preg_replace("/\{path\}/", $this->image_path, $image['image']);
		}
		
		return $slides_arr;
	}

	/**
	 * Format text
	 * @param String $text
	 * @param Boolean $nl2br
	 * @param Boolean $smileys
	 * @param Boolean $xss
	 * @param Mixed $break
	 */
	public function format($text, $nl2br = false, $smileys = true, $xss = true, $break = false)
	{
		// Prevent Cross Site Scripting
		if($xss && is_string($text))
		{
			$text = $this->CI->security->xss_clean($text);
			$text = htmlspecialchars($text);
		}

		// Wordwrap
		if($break)
		{
			$text = wordwrap($text, $break, "<br />", true);
		}

		// Convert new lines to <br>
		if($nl2br)
		{
			$text = nl2br($text);
		}

		// Show emoticons
		if($smileys)
		{
			$text = parse_smileys($text, base_url().$this->CI->config->item('smiley_path'));
		}

		return $text;
	}

	/**
	 * Format time as "XX days/hours/minutes/seconds"
	 * @param Int $time
	 * @return String
	 */
	public function formatTime($time)
	{
		if(!is_numeric($time))
		{
			return "Not a number";
		}
		else
		{
			$a = array(
					30 * 24 * 60 * 60       => 'month',
					24 * 60 * 60            =>  'day',
					60 * 60                 =>  'hour',
					60                      =>  'minute',
					1                       =>  'second'
			);
		
			foreach($a as $secs => $str)
			{
				$d = $time / $secs;

				if ($d >= 1)
				{
					$r = round($d);
					
					return $r . ' ' . $str . ($r > 1 ? 's' : '');
				}
			}
		}
	}
	
	public function getDomainName()
	{
	    return preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", $this->CI->config->slash_item('base_url'));
	}

	public function getTitle()
	{
		return $this->title;
	}
    
    /**
     * Adds a breadcrumb to the content trail
     * @param String title
     * @param String link
     */
    public function addBreadcrumb($title, $link = ""){
        $this->breadcrumbs[] = array(
            "title" => $title,
            "link" => $link
        );
        $this->showBreadcrumbs();
    }
    
    /**
     * Returns all set breadcrumbs
     * @return Array
     */
    public function getBreadcrumbs(){
        if(is_array($this->breadcrumbs)){
            return $this->breadcrumbs;        
        }
        else{
            return array();
        }
    }
    
    public function hideBreadcrumbs(){
        $this->showBreadcrumbs = false;
    }
    
    public function showBreadcrumbs(){
        $this->showBreadcrumbs = true;
    }
    
    /**
     * Controls if the header is shown outside the page-wrapper
     */
    public function setTopHeader($header){
        $this->topHeader = $header;
    }
    
    /**
     * Generates a json formatted output
     */
    private function outputJSON($json){
        die(json_encode($json));
    }
}