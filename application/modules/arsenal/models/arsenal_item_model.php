<?php

class Arsenal_Item_model extends MY_Model
{

    private $realm;

    private $entry;

    private $ItemLevel;


    public function __construct($realm, $item)
    {
        $this->realm = $realm;

        foreach($item as $key => $value)
        {
            $this->$key = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getItemLevel()
    {
        return $this->ItemLevel;
    }
}