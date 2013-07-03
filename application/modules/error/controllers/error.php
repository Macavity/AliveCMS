<?php

class Error extends MX_Controller
{
	public function index()
	{
		debug("Error");
        $this->template->setTitle($this->config->item("server_name")." - 404");
        
		$data = array(
		  "module" => "default", 
          "theme_path" => "/".APPPATH.$this->template->theme_path,
		  "headline" => "Vier,<br /> Null, Vier.", 
		  "content" => "<h3>Seite nicht gefunden</h3>
            <p>Hier war mal eine<br />
                <strong>SEITE</strong><br />.<br />
                Die ist nu wech.<br /><br />
                <em>(Wir haben die Seite gewarnt: Geh nicht allein in den Wald! Das hat sie jetzt davon!)</em></p>");
		
		$this->template->view($this->template->loadPage("views/404.tpl", $data));
	}

	public function rank()
	{
		if(!$this->user->isOnline())
		{
			redirect('login');
		}
		else
		{
			$this->template->setTitle("Permission denied");

			$data = array("module" => "default", "headline" => "Permission denied", "content" => "<center style='margin:10px;font-weight:bold;'>You don't have permission to access this page!</center>");
			
			$this->template->view($this->template->loadPage("page.tpl", $data));
		}
	}
}
