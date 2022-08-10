<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property $wowgeneral
 * @property $wowmodule
 * @property $wowrealm
 * @property $template
 */
class Pvp extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pvp_model');
        $this->load->model('Armory/armory_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getPVPStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function index()
    {
        $data = array(
            'pagetitle' => $this->lang->line('tab_pvp_statistics'),
            'realms'    => $this->wowrealm->getRealms()->result()
        );

        $this->template->build('index', $data);
    }
}
