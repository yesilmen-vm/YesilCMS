<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property $wowgeneral
 * @property $page_model
 * @property $template
 */
class Page extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }
    }

    public function index($uri)
    {
        if (empty($uri) || is_null($uri) || $uri == null) {
            redirect(base_url(), 'refresh');
        }

        if ($this->page_model->getVerifyExist($uri) < 1) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'uri'       => $uri,
            'pagetitle' => $this->page_model->getName($uri),
        );

        $this->template->build('index', $data);
    }
}
