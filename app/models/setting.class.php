<?php

class Setting
{
    private $error = array();

    public function get_all()
    {
        $db = Database::newInstance();
        $query = "select * from settings";
        return $db->read($query);
    }

    public function save($POST)
    {
        $this->error = array();
        $db = Database::newInstance();

        foreach ($POST as $key => $value) {
            // code...

            $arr = array();
            $arr['setting'] = $key;
            $arr['value'] = $value;
             $query = "update settings set value = :value where setting = :setting limit 1";
            $db->write($query, $arr);

            
        }
        return $this->error;

    }
}