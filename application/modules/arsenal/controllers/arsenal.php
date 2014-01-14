<?php

/**
 * @property Internal_User_model internal_user_model
 *
 * @property Arsenal_model $arsenal_model
 * @property Arsenal_Character_model $character_model
 *
 * @property Arsenal_Character_model $character
 */
class Arsenal extends MY_Controller
{
    /**
     * @var bool
     */
    private $cacheEnabled;


    private $js;
    private $css;

    private $realmId;
    private $realmName;

    private $account;
    private $accountId;
    private $accountName;

    private $charGuid;

    private $name;

    private $class;
    private $className;

    private $race;
    private $raceName;

    private $level;

    private $guildId;
    private $guildName;


    private $gender;

    private $stats;

    private $pvpStats;

    private $secondBar;
    private $secondBarValue;

    private $items;
    private $character;

    function __construct()
    {
        parent::__construct();

        requirePermission("view");

        $this->load->model('arsenal_model');

        $this->cacheEnabled = false;
        $this->items = array();
    }

    /**
     * Character Index Page
     *
     * @param bool|String $realm
     * @param bool|String $characterName
     * @param string $detail
     */
    public function index($realm = false, $characterName = false, $detail = "simple")
    {
        $this->arsenal_model->initialize($realm, $characterName);

        if($this->arsenal_model->getErrorMessage())
        {
            $this->showErrorPage($this->arsenal_model->getErrorMessage());
            return;
        }

        $this->charGuid = $this->arsenal_model->getCharacterGUID();

        $this->realmId = $this->arsenal_model->getRealm()->getId();
        $this->realmName = $this->arsenal_model->getRealm()->getName();

        $cache = $this->cache->get($this->getCacheId());

        /*
         * Load cached data if cache is enabled and cache data is not overdue
         */
        if($this->cacheEnabled && $cache !== false)
        {
            $this->template->setTitle($cache['name']);
            $this->template->setDescription($cache['description']);
            $this->template->setKeywords($cache['keywords']);

            $page = $cache['page'];
        }
        else
        {
            $this->load->model('Arsenal_Character_model', 'character_model');

            $this->character_model->initialize($this->charGuid, $this->arsenal_model->getRealm());

            $this->character_model->loadBaseData($detail);

            $talent_data = $this->character_model->getTalentData();
            $activeSpec = $this->character->GetActiveSpec();
            $char->BuildCharacter();


            $this->template->setTitle($this->name);

            $avatarArray = array(
                'class' => $this->class,
                'race' => $this->race,
                'level' => $this->level,
                'gender' => $this->gender
            );

            $charData = array(
                "url" => $this->template->page_url,
                "charUrl" => $this->getCharacterUrl(),

                "name" => $this->name,
                "realmId" => $this->realmId,
                "realmName" => $this->realmName,

                "gender" => $this->gender,
                "race" => $this->race,
                "faction" => $this->arsenal_model->realms->getFactionString($this->race),
                "raceName" => $this->raceName,
                "class" => $this->class,
                "className" => $this->className,
                "level" => $this->level,

                "items" => $this->items,
                "itemLevel" => $this->arsenal_model->getItemLevel(),
                "itemLevelEquipped" => $this->arsenal_model->getItemLevelEquipped(),
                "pvp" => $this->pvpStats,

                "guild" => $this->guildId,
                "guildName" => $this->guildName,


                "avatar" => $this->arsenal_model->realms->formatAvatarPath($avatarArray),
                "stats" => $this->stats,

                "secondBar" => $this->secondBar,
                "secondBarValue" => $this->secondBarValue,

                "bg" => $this->getBackground(),
                "fcms_tooltip" => $this->config->item("use_fcms_tooltip"),
                "has_stats" => $this->arsenal_model->realms->getRealm($this->realmId)->getEmulator()->hasStats()
            );

            $character = $this->template->loadPage("character_profile.tpl", $charData);

            $data = array(
                "module" => "default",
                "headline" => "<span style='cursor:pointer;' data-tip='".lang("view_profile", "character")."' onClick='window.location=\"".$this->template->page_url."profile/".$this->account."\"'>".$this->accountName."</span> &rarr; ".$this->name,
                "content" => $character
            );

            $keywords = "armory,".$charData['name'].",lv".$charData['level'].",".$charData['raceName'].",".$charData['className'].",".$charData['realmName'];
            $description = $charData['name']." - level ".$charData['level']." " .$charData['raceName']." ".$charData['className']." on ".$charData['realmName'];

            $this->template->setDescription($description);
            $this->template->setKeywords($keywords);

            $page = $this->template->loadPage("page.tpl", $data);

            if($this->cacheEnabled)
            {
                // Cache for 30 min
                $this->cache->save($this->getCacheId(), array(
                        'page' => $page,
                        'name' => $this->name,
                        'keywords' => $keywords,
                        'description' => $description
                    ),
                    60*30
                );
            }
        }

        $this->template->view($page, $this->css, $this->js);

    }

    public function getItem($id = false)
    {
        if($id != false)
        {
            $cache = $this->cache->get("items/item_".$this->realmId."_".$id);

            if($cache !== false)
            {
                $cache2 = $this->cache->get("items/display_".$cache['displayid']);

                if($cache2 != false)
                {
                    return "<a href='" . $this->template->page_url . "item/" . $this->realmId . "/" . $id . "' rel='item=".$id."' data-realm='".$this->realmId."'></a><img src='https://wow.zamimg.com/images/wow/icons/large/".$cache2.".jpg' />";
                }
                else
                {
                    return "<a href='" . $this->template->page_url . "item/" . $this->realmId . "/" . $id . "' rel='item=".$id."' data-realm='".$this->realmId."'></a><img src='https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg' />";
                }
            }
            else
            {
                $this->cacheEnabled = false;
                return $this->template->loadPage("icon_ajax.tpl", array('id' => $id, 'realm' => $this->realmId, 'url' => $this->template->page_url));
            }
        }
    }

    private function getCacheId()
    {
        return "arsenal_character_".$this->realmId."_".$this->charGuid."_".getLang();
    }

    private function getBackground()
    {
        switch($this->raceName)
        {
            default: return "shattrath"; break;
            case "Human": return "stormwind"; break;
            case "Blood elf": return "silvermoon"; break;
            case "Night elf": return "darnassus"; break;
            case "Dwarf": return "ironforge"; break;
            case "Gnome": return "ironforge"; break;
            case "Orc": return "orgrimmar"; break;
            case "Draenei": return "theexodar"; break;
            case "Tauren": return "thunderbluff"; break;
            case "Undead": return "undercity"; break;
            case "Troll": return "orgrimmar"; break;
        }
    }

    /**
     * Show error page
     */
    private function showErrorPage($errorMessage)
    {
        $errorTitle = lang("error_title", "arsenal");

        $this->template->setTitle($errorTitle);

        $data = array(
            "module" => "default",
            "headline" => $errorTitle,
            "content" => $errorMessage,
        );

        $page = $this->template->loadPage("page.tpl", $data);

        $this->template->view($page);
    }

    public function getCharacterUrl()
    {
        return site_url("/arsenal/".$this->arsenal_model->realm->getName()."/".urlencode($this->name));
    }

}
