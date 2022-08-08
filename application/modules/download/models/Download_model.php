<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Download_model extends CI_Model
{
    /**
     * Download_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getGame(): CI_DB_result
    {
        return $this->db->select('*')->where('category', '1')->order_by('id', 'ASC')->get('download');
    }

    public function getAddons(): CI_DB_result
    {
        return $this->db->select('*')->where('category', '2')->order_by('id', 'ASC')->get('download');
    }
}
