<?php

/**
 * Class FixBugShit_model
 *
 * @property Project_model $project_model
 */
class Fixbugshit_model extends MY_Model
{
    /**
     * @var int
     */
    private $bugId = 0;

    /**
     * @var bool
     */
    private $showFieldset = false;

    /**
     * @var array|string
     */
    private $bugshitCategories = array();

    /**
     * @var array
     */
    private $quests = array();


    private $realmId;

    public function __construct()
    {
        parent::__construct();

        $this->load->config('fixbugshit_config');

        $this->load->model('project_model');


        $this->bugshitCategories = $this->config->item('bugshit_categories');
    }

    /**
     * @param $bug
     */
    public function initialize($bug)
    {
        $this->bugId = $bug['id'];

        if(in_array($bug['project'], $this->bugshitCategories))
        {

            $realmOfCat = $this->project_model->getRealmOfProject($bug['project']);

            if($realmOfCat)
            {
                $this->realmId = $realmOfCat;

                $realm = $this->realms->getRealm($this->realmId);

                $world = $realm->getWorld();
                $world->connect();

                $worldDb = $world->getConnection();

                // Analyze Links
                $questIds = $this->parseLinks(json_decode($bug['link'], true));

                if(count($questIds))
                {

                    $worldDb->select('Id, Method, Title')
                        ->where_in('Id', $questIds)
                        ->from('quest_template');

                    $query = $worldDb->get();

                    if($query && $query->num_rows() > 0)
                    {
                        foreach($query->result_array() as $row)
                        {
                            $this->quests[] =  array(
                                'id' => $row['Id'],
                                'title' => $row['Title'],
                                'isAutocomplete' => ($row['Method'] == 0) ? true : false,
                            );
                        }
                    }
                }


            }

            if(count($this->quests))
            {
                $this->showFieldset = true;
            }

        }

        return;

    }

    /**
     * @return boolean
     */
    public function getShowFieldset()
    {
        return $this->showFieldset;
    }

    public function parseLinks($links)
    {

        $uniqueIds = array();

        if(!is_array($links))
        {
            return $uniqueIds;
        }

        foreach($links as $link)
        {
            if(preg_match('/quest\=(\d+)/i', $link, $matches))
            {
                $matchedId = $matches[1];

                if(!in_array($matchedId, $uniqueIds))
                {
                    $uniqueIds[] = $matchedId;
                }
            }
        }

        return $uniqueIds;
    }

    /**
     * @return array
     */
    public function getQuests()
    {
        return $this->quests;
    }

    public function updateQuestMethod($questId, $value)
    {
        if($value != 0 && $value != 2)
        {
            return false;
        }

        $realm = $this->realms->getRealm($this->realmId);

        $world = $realm->getWorld();
        $world->connect();

        $worldDb = $world->getConnection();

        if($value == 0)
        {
            $logAction = 'Realm '.$this->realmId.', Quest '.$questId.': Set Autocomplete ON';
        }
        else
        {
            $logAction = 'Realm '.$this->realmId.', Quest '.$questId.': Set Autocomplete OFF';
        }
        $this->logger->createLog('Update Quest Method', $logAction);

        $worldDb->where('Id', $questId)
            ->update('quest_template', array('Method' => $value));


    }

    /**
     * @return array|string
     */
    public function getBugshitCategories()
    {
        return $this->bugshitCategories;
    }

}