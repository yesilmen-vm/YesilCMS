<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Auth_model $wowauth
 * @property Template   $template
 */
class Mod extends MX_Controller
{
    private $wowlocmod = '';
    private $wowlocref = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_model');
        $this->load->library('pagination');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) < config_item('mod_access_level')) {
            redirect(base_url(), 'refresh');
        }

        $this->template->set_theme('mod');

        $this->wowlocmod = base_url('application/themes/' . $this->template->get_theme() . '/');
        $this->wowlocref = base_url('application/themes/' . config_item('theme_name') . '/');
    }

    public function index()
    {
        $data = array(
            'pagetitle' => $this->lang->line('button_mod_panel'),
        );

        $this->template->build('index', $data);
    }

    public function queue()
    {
        $data = array(
            'pagetitle' => $this->lang->line('button_mod_panel'),
        );

        $this->template->build('queue/index', $data);
    }

    public function reports()
    {
        $data = array(
            'pagetitle' => $this->lang->line('button_mod_panel'),
        );

        $this->template->build('reports/index', $data);
    }

    public function logs()
    {
        $data = array(
            'pagetitle' => $this->lang->line('button_mod_panel'),
        );

        $this->template->build('logs/index', $data);
    }

    public function bannings()
    {
        $data = array(
            'pagetitle' => $this->lang->line('button_mod_panel'),
        );

        $this->template->build('bannings/index', $data);
    }

    public function warnings()
    {
        $data = array(
            'pagetitle' => $this->lang->line('button_mod_panel'),
        );

        $this->template->build('warnings/index', $data);
    }
}
