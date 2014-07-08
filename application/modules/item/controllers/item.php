<?php

class Item extends MY_Controller
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
		$item = $this->realms->getRealm($realm)->getWorld()->getItem($id);

		if($item !== false || $item != "empty"){
			$itemName = $item['name'];
		}
		else{
			$itemName = lang("view_item", "item");
		}

        $this->template->setTitle($itemName);
        $this->template->addBreadcrumb("Spiel", site_url("game/index"));
        $this->template->addBreadcrumb("Gegenstände", site_url("game/index"));
        $this->template->addBreadcrumb($itemName, site_url("game/index"));

		$icon = $this->getIcon($id);

        // Rotating Image
        $this->getRotateImage($id);

        // Icon
        $item['icon'] = "";
        $iconQuery = $this->db->select('icon')->from('arsenal_icons')->where('displayid',$item['displayid'])->get();

        if($iconQuery->num_rows() > 0){
            $iconRow = $iconQuery->row_array();

            $this->getSizedIcon($iconRow['icon'], 18);
            $this->getSizedIcon($iconRow['icon'], 56);

            $item['icon'] = $iconRow['icon'];
        }

        // Counterpart
        $item['has_counterpart'] = false;
        $item['counterpart_icon'] = "";
        $item['counterpart_name'] = "";

        if(!empty($item['counterpart'])){
            $counterItem = $this->realms->getRealm($realm)->getWorld()->getItem($item['counterpart']);

            if($counterItem && $counterItem != "empty"){
                $item['has_counterpart'] = true;
                $item['counterpart_name'] = $counterItem['name'];

                $counterIcon = $this->db->select('icon')->from('arsenal_icons')->where('displayid',$counterItem['displayid'])->get();

                if($counterIcon->num_rows() > 0){
                    $counterIconRow = $counterIcon->row_array();
                    $this->getSizedIcon($counterIconRow['icon'], 18);
                    $this->getSizedIcon($counterIconRow['icon'], 56);

                    $item['counterpart_icon'] = $counterIconRow['icon'];
                }
            }

        }


		/*if($cache !== false)
		{
            $tooltipData = $cache;
		}
		else
		{
            $itemData = array(
                'module' => 'item',
                'id' => $id,
                'realm' => $realm,
                //'icon' => $icon
            );

            $tooltipData = $this->template->loadPage("ajax.tpl", $itemData);
		}*/

        $contentData = array(
            'module' => 'item',
            'realm' => $realm,
            'entry' => $id,
            'item' => $item,
            'tooltipData' => "",
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
        $localUrl = $_SERVER['DOCUMENT_ROOT'].'/application/images/icons/'.$size.'/'.$icon.".jpg";

        if(file_exists($localUrl)){
            return;
        }

        $contents = file_get_contents($url);
        if(strlen($contents) > 0){
            file_put_contents($localUrl, $contents);
        }
    }

    private function getRotateImage($entry){
        $url = "http://eu.media.blizzard.com/wow/renders/items/item".$entry.".jpg";
        $localUrl = $_SERVER['DOCUMENT_ROOT']."/application/images/armory/renders/item".$entry.".jpg";

        if(file_exists($localUrl)){
            return;
        }

        try {
            /*
             * I don't like "@" but we don't know if $url is valid
             */
            $contents = @file_get_contents($url);
            if(strlen($contents) > 0){
                @file_put_contents($localUrl, $contents);
            }
        }
        catch(Exception $exception){

        }

    }
}