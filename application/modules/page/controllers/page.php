<?php

class Page extends MX_Controller{
    
    public function __construct(){
        //Call the constructor of MX_Controller
        parent::__construct();
    }
    
    public function index($page = "error"){
        if($page == "error"){
            redirect('error');
        }
        else{
            $cache = $this->cache->get("page_".$page);

            if($cache !== false){
                $this->user->requireRank($cache['rank']);

                $this->template->setTitle($cache['title']);
                
                foreach($path as $row){
                    $this->template->addBreadcrumb($row["title"], $row["path"]);
                }
                $out = $cache['content'];
            }
            else{
                $page_content = $this->cms_model->getPage($page);
                
                if($page_content == false){
                    redirect('error');
                }
                else{
                    $this->user->requireRank($page_content['rank_needed']);
                    $this->template->setTitle($page_content['name']);
                    
                    $this->template->setTopHeader($page_content["name"]);
                    
                    if($page_content["top_category"] != 0){
                        
                        $path = $this->cms_model->getCategoryPath($page_content["top_category"]);
                        
                        foreach($path as $row){
                            $this->template->addBreadcrumb($row["title"], $row["path"]);
                        }
                    }
                    
                    $this->template->addBreadcrumb($page_content["name"], "/".$this->uri->uri_string()."/");
                    
                    $page_data = array(
                        "module" => "default", 
                        "headline" => $page_content['name'], 
                        "content" => $page_content['content']
                    );
                    
                    $out = $this->template->loadPage("page.tpl", $page_data);
                    
                    $cache_data = array(
                        "rank" => $page_content['rank_needed'],
                        "title" => $page_content['name'], 
                        "path" => $path,
                        "content" => $out, 
                    );
                    
                    $this->cache->save("page_".$page, $cache_data);
                }
            }
        }
        
        $css = array(
            base_url().APPPATH.$this->template->theme_path."css/server.css",
            base_url().APPPATH.$this->template->theme_path."css/page.css",
        );
        
        $this->template->view($out, $css);
    }
}
