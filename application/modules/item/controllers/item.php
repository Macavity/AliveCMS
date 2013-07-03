<?php

class Item extends MX_Controller
{
	private $realm;

	public function Index($realm = false, $id = false)
	{
		// Make sure item and realm are set
		if(!$id || !$realm)
		{
			die("No item or realm specified!");
		}

		$this->realm = $realm;

		$cache = $this->cache->get("items/tooltip_".$realm."_".$id);
		$cache2 = $this->cache->get("items/item_".$realm."_".$id);

		if($cache2 !== false)
		{
			$itemName = $cache2['name'];
		}
		else
		{
			$itemName = "View item";
		}

		$this->template->setTitle($itemName);

		$icon = $this->getIcon($id);

		if($cache !== false)
		{
			$item = $cache;
		}
		else
		{
			$item = $this->template->loadPage("ajax.tpl", array('module' => 'item', 'id' => $id, 'realm' => $realm, 'icon' => $icon));
		}

		$content = $this->template->loadPage("item.tpl", array('module' => 'item', 'item' => $item, 'icon' => $icon));

		$data3 = array(
				"module" => "default",
				"headline" => "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."armory\"'>Armory</span> &rarr; ".$itemName,
				"content" => $content
			);

		$page = $this->template->loadPage("page.tpl", $data3);

		$this->template->view($page, "modules/item/css/item.css");
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
			return $this->template->loadPage("icon_ajax.tpl", array('id' => $id, 'realm' => $this->realm, 'url' => $this->template->page_url));
		}
	}
}