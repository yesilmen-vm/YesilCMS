<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property                     $multiRealm
 * @property General_model       $wowgeneral
 * @property CI_DB_query_builder $world
 */
class Api_v1_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->world = $this->load->database('world', true);
    }
}