<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Changelogs_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return CI_DB_result
     */
    public function getAll(): CI_DB_result
    {
        return $this->db->select('id')->get('changelogs');
    }


    /**
     * @return CI_DB_result
     */
    public function getChangelogs(): CI_DB_result
    {
        return $this->db->select('*')->order_by('id', 'DESC')->limit('20')->get('changelogs');
    }


    /**
     * @return array|mixed|object|null
     */
    public function getLastID()
    {
        return $this->db->select('id')->order_by('id', 'DESC')->limit('1')->get('changelogs')->row('id');
    }
}
