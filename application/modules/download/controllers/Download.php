<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 */
class Download extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('download_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getDownloadStatus()) {
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

            'pagetitle' => 'Download Center',
        );

        $this->template->build('index', $data);
    }
}
