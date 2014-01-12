<?php

/**
 * @property Internal_User_model internal_user_model
 * @property CI_Cache $cache
 * @property CI_Config $config
 * @property Template $template
 *
 * @property Arsenal_Character_model $character
 */
class Arsenal extends MX_Controller
{
    /**
     * @var bool
     */
    private $canCache;


    private $js;
    private $css;

    private $realmId;
    private $realmName;

    private $account;
    private $accountId;
    private $accountName;

    private $id;

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


        if(false){
            $this->arsenal_model = new Arsenal_model();
            $this->load = new CI_Loader();
        }

        $this->load->model('arsenal_model');

        $this->canCache = false;
        $this->items = array();
    }

    /**
     * Initialize
     * @param bool|String $realm
     * @param bool|String $characterName
     * @param string $detail
     */
    public function index($realm = false, $characterName = false, $detail = "simple")
    {
        $this->arsenal_model->initialize($realm, $characterName);

        $this->id = $this->arsenal_model->getCharacterGUID();

        $this->realmId = $this->arsenal_model->realm->getId();
        $this->realmName = $this->arsenal_model->realm->getName();

        $cacheId = "arsenal_character_".$this->arsenal_model->realm->getId()."_".$this->arsenal_model->getCharacterGUID()."_".getLang();

        $cache = $this->cache->get($cacheId);

        if($this->canCache && $cache !== false)
        {
            $this->template->setTitle($cache['name']);
            $this->template->setDescription($cache['description']);
            $this->template->setKeywords($cache['keywords']);

            $page = $cache['page'];
        }
        else
        {
            if($this->arsenal_model->characterExists())
            {
                $this->load->model('Arsenal_Character_model', 'character');

                $this->character->initialize($this->id, $this->arsenal_model->realm);

                $this->character->loadBaseData($detail);

                $talent_data = $this->character->GetTalentData();
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
            }
            else
            {
                $keywords = "";
                $description = "";

                $page = $this->getError(true);
            }

            if($this->canCache)
            {
                // Cache for 30 min
                $this->cache->save($cacheId, array('page' => $page, 'name' => $this->name, 'keywords' => $keywords, 'description' => $description), 60*30);
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
                $this->canCache = false;
                return $this->template->loadPage("icon_ajax.tpl", array('id' => $id, 'realm' => $this->realmId, 'url' => $this->template->page_url));
            }
        }
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
     * Show "character doesn't exist" error
     */
    private function getError($get = false)
    {
        $this->template->setTitle(lang("doesnt_exist", "character"));

        $data = array(
            "module" => "default",
            "headline" => lang("doesnt_exist", "character"),
            "content" => "<center style='margin:10px;font-weight:bold;'>".lang("doesnt_exist_long", "character")."</center>"
        );

        $page = $this->template->loadPage("page.tpl", $data);

        if($get)
        {
            return $page;
        }
        else
        {
            $this->template->view($page);
        }
    }

    public function getCharacterUrl()
    {
        return site_url("/arsenal/".$this->arsenal_model->realm->getName()."/".urlencode($this->name));
    }

}
