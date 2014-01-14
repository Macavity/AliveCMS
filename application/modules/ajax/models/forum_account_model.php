<?php

/**
 * Class Forum_account_model
 *
 */
class Forum_account_model extends MY_Model
{

    private $userId;
    private $activeCharGuid;
    private $activeRealmId;

    public function initialize($forumAccountId)
    {
        $this->db->select('*')->from('account_data')->where('forum_account_id', $forumAccountId);

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $row = $query->row_array();

            $this->userId = $row['id'];
            $this->activeCharGuid = $row['active_char_guid'];
            $this->activeRealmId = $row['active_realm_id'];

            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getActiveCharGuid()
    {
        return $this->activeCharGuid;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getActiveRealmId()
    {
        return $this->activeRealmId;
    }

}