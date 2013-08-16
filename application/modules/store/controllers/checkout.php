<?php

class Checkout extends MX_Controller
{
	private $vp;
	private $dp;
	private $count;

	public function __construct()
	{
		parent::__construct();
		
		$this->user->userArea();

		$this->load->model("store_model");

		requirePermission("view");
	}

	/**
	 * Main method to serve the checkout action
	 */
	public function index(){
        $message = array();

        $cart = $this->input->post("cart");
        $cart = json_decode($cart, true);


		// Make sure they sent us a cart object
		if(!$cart){
            $this->show_error('Ungültiger Aufruf.');
		}

		// Make sure they don't submit an empty array
		if(count($cart) == 0){
            $this->show_error('Dein Einkaufswagen enthält nichts.');
		}

        $activeRealmId = $this->user->getActiveRealmId();

        $items = array();
        $realms = array();

        // Load all items
        foreach($cart as $item){

            // Load the item
            $items[$item['id']] = $this->store_model->getItem($item['id']);

            $itemCount = abs($item['count']);

            // Make sure the item exists
            if($items[$item['id']] != false){
                $this->count++;


                // Keep track of how much it costs
                if($item['type'] == "vp" && !empty($items[$item['id']]['vp_price'])){
                    $this->vp += $items[$item['id']]['vp_price'] * $itemCount;
                }
                elseif($item['type'] == "dp" && !empty($items[$item['id']]['dp_price'])){
                    $this->dp += $items[$item['id']]['dp_price'] * $itemCount;
                }
                else{
                    $this->show_error(lang("free_items", "store"));
                }
            }

            // Put it in the realm array
            if(!isset($realms[$items[$item['id']]['realm']]))
            {
                $realms[$items[$item['id']]['realm']] = array(
                    'name' => $this->realms->getRealm($activeRealmId)->getName(),
                    'items' => array(),
                    'characters' => $this->realms->getRealm($activeRealmId)->getCharacters()->getCharactersByAccount(),
                );
            }

            array_push($realms[$items[$item['id']]['realm']]['items'], $items[$item['id']]);
        }

        // Make sure the user can afford it
        if(!$this->canAfford()){
            $output = $this->template->loadPage("checkout_error.tpl");

            $this->template->handleJsonOutput(array(
                'type' => 'success',
                'content' => $output,
            ));
        }

        // Prepare the data
        $data = array(
            'realms' => $realms,
            'url' => $this->template->page_url,
            'vp' => $this->vp,
            'dp' => $this->dp,
            'count' => $this->count
        );

        // Load the checkout view
        $output = $this->template->loadPage("checkout.tpl", $data);

        // Output the content
        $this->template->handleJsonOutput(array(
            'type' => 'success',
            'content' => $output,
        ));
	}

    private function show_error($message){
        $message = array(
            'type' => 'error',
            'msg' => $message,
        );
        $this->template->handleJsonOutput($message);
        die();
    }

	/**
	 * Check if the user can afford what he's trying to buy
	 * @return Boolean
	 */
	private function canAfford()
	{
		if($this->vp > 0 && $this->vp > $this->user->getVp())
		{
			return false;
		}
		elseif($this->dp > 0 && $this->dp > $this->user->getDp())
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}