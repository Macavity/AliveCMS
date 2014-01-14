<?php

class Arsenal_Ranking_model extends MY_Model
{

    private $type;

    private $id = 0;
    private $dateChanged;
    private $charGuid = 0;
    private $realmId = 0;
    private $value = 0;
    private $ranking = 0;

    public function __construct($type, $charGuid)
    {
        parent::__construct();

        $this->type = $type;

        $this->charGuid = $charGuid;

        $this->db->select('*')
            ->where('character_guid', $this->charGuid)
            ->where('type', $type)
            ->from('arsenal_ranking');

        $query = $this->db->get();

        if($query->num_rows())
        {
            $row = $query->row();

            $this->id = $row->id;
            $this->dateChanged = $row->date_changed;
            $this->realmId = $row->realm;
            $this->value = $row->value;
            $this->ranking = $row->ranking;

        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }



    public function refresh()
    {
        if($this->id == 0)
        {
            $this->insert();
        }
        else
        {
            $this->update();
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function insert()
    {
        $data = array(
            'date_changed' => time(),
            'value' => $this->value,
            'ranking' => $this->ranking,
        );

        $this->db->insert('arsenal_ranking', $data);

        $this->db->select('*')
            ->where('type', $this->type)
            ->where('character_guid', $this->charGuid)
            ->from('arsenal_ranking');

        $query = $this->db->get();

        if($query->num_rows())
        {
            $row = $query->row();
            $this->id = $row->id;
        }
    }

    public function update()
    {
        $data = array(
            'date_changed' => time(),
            'value' => $this->value,
            'ranking' => $this->ranking,
        );

        $this->db->where('id', $this->id);
        $this->db->update('arsenal_ranking', $data);

    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}