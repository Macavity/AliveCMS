<?php

class Item extends MX_Controller
{
	private $realm;

	public function Index($realm = false, $id = false)
	{
		clientLang("loading", "item");

		// Make sure item and realm are set
		if(!$id || !$realm)
		{
			die(lang("no_item", "item"));
		}

		$this->realm = $realm;

		$cache = $this->cache->get("items/tooltip_".$realm."_".$id."_".getLang());
		$cache2 = $this->cache->get("items/item_".$realm."_".$id);

		if($cache2 !== false)
		{
			$itemName = $cache2['name'];
		}
		else
		{
			$itemName = lang("view_item", "item");
		}

		$this->template->setTitle($itemName);

		$icon = $this->getIcon($id);

        // Rotating Image
        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/application/themes/shattered/images/items/item'.$id.".jpg")){
            $this->getRotateImage($id);
        }

		if($cache !== false)
		{
			$item = $cache;
		}
		else
		{
            $itemData = array(
                'module' => 'item',
                'id' => $id,
                'realm' => $realm,
                //'icon' => $icon
            );

			$item = $this->template->loadPage("ajax.tpl", $itemData);
		}

        $contentData = array(
            'module' => 'item',
            'realm' => $realm,
            'entry' => $id,
            'item' => $item,
            'icon' => $icon
        );

        $this->template->hideSidebar();
        $content = $this->template->loadPage("item.tpl", $contentData);

		$this->template->view($content, "modules/item/css/item.css");
	}

    private function getSources($realmId, $itemId){
        $sources = array();
        $referenceIds = array(0);

        $worldDb = $this->realms->getRealm($realmId)->getWorld();

        $referenceLoot = $worldDb->select('entry')->from('reference_loot_template')->where('item',$itemId)->get();
        $rows = $referenceLoot->result_array();

        foreach($rows as $row){
            $refId = $row["entry"];
            $referenceIds[] = "-".abs($refId);
        }
        debug("Ref", $referenceIds);

        $creatureLoot = $worldDb->query("
            SELECT ct.`entry`
              FROM `creature_loot_template` cl JOIN creature_template ct ON (cl.entry = ct.lootid)
			  WHERE (`item`= ?d AND mincountOrRef > 0) OR (mincountOrRef IN(?a)) ;", $itemId, $referenceIds)->get();
        $rows = $creatureLoot->result_array();
        foreach($rows as $row){
            $sources['creature'][] = array(
                "type" => "creature",
                "entry" => $row["entry"],
            );
        }

        $objectLoot = $worldDb->query("
            SELECT gt.`entry`
              FROM `gameobject_loot_template` as gl JOIN gameobject_template gt ON (gl.entry = gt.data1)
			  WHERE (`item`= ?d AND mincountOrRef > 0) OR (mincountOrRef IN(?a)) ;", $itemId, $referenceIds)->get();
        $rows = $objectLoot->result_array();
        foreach($rows as $row){
            $sources['creature'][] = array(
                "type" => "object",
                "entry" => $row["entry"],
            );
        }

        // Vendor
        $vendorLoot = $worldDb->query("SELECT `entry`, `ExtendedCost` FROM `npc_vendor` WHERE `item`=?d;", $itemId);
        $rows = $vendorLoot->result_array();
        foreach($rows as $row){
            $sources['vendor'][] = array(
                "type" => "vendor",
                "entry" => $row["entry"],
                "cost" => $row["ExtendedCost"],
            );
        }

        // Quest Loot
        $questLoot = $worldDb->select('entry')
            ->where('RewChoiceItemId1', $itemId)
            ->or_where('RewChoiceItemId2', $itemId)
            ->or_where('RewChoiceItemId3', $itemId)
            ->or_where('RewChoiceItemId4', $itemId)
            ->or_where('RewChoiceItemId5', $itemId)
            ->or_where('RewChoiceItemId6', $itemId)
            ->from('quest_template')
            ->get();
        $rows = $questLoot->result_array();
        foreach($rows as $row){
            $sources['quest'][] = array(
                "type" => "quest",
                "entry" => $row["entry"],
            );
        }

        // Crafting
        $craftLoot = $this->db->select('id')
            ->where('EffectItemType_1', $itemId)
            ->or_where('EffectItemType_2', $itemId)
            ->or_where('EffectItemType_3', $itemId)
            ->from('armory_spell')
            ->get();
        $rows = $craftLoot->result_array();
        foreach($rows as $row){
            $sources['crafting'][] = array(
                "type" => "crafting",
                "entry" => $row["id"],
            );
        }

        // Achievement
        $achLoot = $worldDb->select('id')
            ->from('achievement_reward')
            ->where('item', $item_id)
            ->get();
        //if(!$achLoot)
        //	self::debug("Achievement Loot", $WSDB);
        foreach($achLoot as $row){
            $sources[] = array(
                "type" => "achievement",
                "entry" => $row["id"],
            );
            $this->sourceTypeCount["achievement"]++;
        }

        self::debug("Loot Data:",$sources);

        $this->sources = $sources;
    }

	private function getIcon($id)
	{
		$cache = $this->cache->get("items/item_".$this->realm."_".$id);

		if($cache !== false)
		{
			$cache2 = $this->cache->get("items/display_".$cache['displayid']);

			if($cache2 != false)
			{
				return "<div class='item'><a></a><img src='https://wow.zamimg.com/images/wow/icons/large/".$cache2.".jpg' /></div>";
			}
			else
			{
				return "<div class='item'><a></a><img src='https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg' /></div>";
			}
		}
		else
		{
			return $this->template->loadPage('icon_ajax.tpl', array('id' => $id, 'realm' => $this->realm, 'url' => $this->template->page_url));
		}
	}

    private function getSizedIcon($icon,$size = 56){
        $url = 'http://media.blizzard.com/wow/icons/'.$size.'/'.$icon.'.jpg';
        $contents = file_get_contents($url);
        if(strlen($contents) > 0){
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/application/themes/shattered/images/icons/'.$size.'/'.$icon.".jpg", $contents);
        }
    }

    private function getRotateImage($entry){
        $url = "http://eu.media.blizzard.com/wow/renders/items/item".$entry.".jpg";
        $contents = file_get_contents($url);
        if(strlen($contents) > 0){
            file_put_contents($_SERVER['DOCUMENT_ROOT']."/application/themes/shattered/images/items/item".$entry.".jpg", $contents);
        }
    }
}