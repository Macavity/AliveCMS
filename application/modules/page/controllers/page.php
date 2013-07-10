<?php

class Page extends MX_Controller
{
	public function index($page = "error")
	{
		if($page == "error")
		{
			redirect('error');
		}
		else
		{
			$cache = $this->cache->get("page_".$page."_".getLang());

			if($cache !== false)
			{
				$this->template->setTitle($cache['title']);
				$out = $cache['content'];

                /**
                 * @alive
                 */
                $path = $cache['path'];

                foreach($path as $row){
                    $this->template->addBreadcrumb($row["title"], $row["path"]);
                }

                if($cache['permission'] && !hasViewPermission($cache['permission'], "--PAGE--"))
				{
					$this->template->showError(lang("permission_denied", "error"));
				}
			}
			else
			{
				$page_content = $this->cms_model->getPage($page);
			
				if($page_content == false)
				{
					redirect('error');
				}
				else
				{
					$this->template->setTitle(langColumn($page_content['name']));

                    /**
                     * @alive
                     */
                    $this->template->addBreadcrumb($page_content["name"], "/".$this->uri->uri_string()."/");

                    if($page_content["top_category"] != 0){

                        $path = $this->cms_model->getCategoryPath($page_content["top_category"]);

                        foreach($path as $row){
                            $this->template->addBreadcrumb($row["title"], $row["path"]);
                        }
                    }


                    $page_data = array(
						"module" => "default", 
						"headline" => langColumn($page_content['name']), 
						"content" => langColumn($page_content['content'])
					);

					$out = $this->template->loadPage("page.tpl", $page_data);
					
					$this->cache->save("page_".$page."_".getLang(), array(
						"title" => langColumn($page_content['name']),
						"content" => $out,
                        "path" => $path, /* @alive */
                        "permission" => $page_content['permission'])
					);

					if($page_content['permission'] && !hasViewPermission($page_content['permission'], "--PAGE--"))
					{
						$this->template->showError(lang("permission_denied", "error"));
					}
				}
			}
		}
		
		$this->template->view($out);
	}
}
