<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Realm_model   $wowrealm
 * @property Template      $template
 */
class Online extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('online_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }
    }

    public function index()
    {
        $data = array(
            'pagetitle' => $this->lang->line('tab_online'),
            'realms'    => $this->wowrealm->getRealms()->result()
        );

        $this->template->build('index', $data);
    }
}
