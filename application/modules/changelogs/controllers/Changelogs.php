<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 */
class Changelogs extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('changelogs_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getChangelogsStatus()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->isLogged()) {
            redirect(base_url('login'), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }
    }

    public function index()
    {
        $data = array(
            'pagetitle' => $this->lang->line('tab_changelogs'),
        );

        $this->template->build('index', $data);
    }
}
