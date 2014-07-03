<?php

class CartItem_model extends MY_Model {

    public $id;

    /**
     * Type of the price, either vp or dp
     * @var
     */
    public $type;

    public $vp_price;
    public $dp_price;

    public $realm;

    public $charGuid;

    public $count;
} 