<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model    $wowgeneral
 * @property Module_model     $wowmodule
 * @property Auth_model       $wowauth
 * @property Bugtracker_model $bugtracker_model
 * @property Template         $template
 */
class Bugtracker extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bugtracker_model');
        $this->load->config('bugtracker');
        $this->load->library('pagination');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getBugtrackerStatus()) {
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
            'pagetitle' => $this->lang->line('tab_bugtracker'),
        );

        $config['total_rows'] = $this->bugtracker_model->getAllBugs();
        $data['total_count']  = $config['total_rows'];
        $config['suffix']     = '';

        if ($config['total_rows'] > 0) {
            $page_number        = $this->uri->segment(3);
            $config['base_url'] = base_url() . 'bugtracker/';

            if (empty($page_number)) {
                $page_number = 1;
            }

            $offset = ($page_number - 1) * $this->pagination->per_page;
            $this->bugtracker_model->setPageNumber($this->pagination->per_page);
            $this->bugtracker_model->setOffset($offset);
            $this->pagination->initialize($config);

            $data['pagination_links'] = $this->pagination->create_links();
            $data['bugtrackerList']   = $this->bugtracker_model->bugtrackerList();
        }

        $this->template->build('index', $data);
    }

    public function newreport()
    {
        if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) >= config_item('admin_access_level')) {
            $tiny = $this->wowgeneral->tinyEditor('Admin');
        } else {
            $tiny = $this->wowgeneral->tinyEditor('User');
        }

        $data = array(
            'pagetitle' => $this->lang->line('tab_bugtracker'),
            'lang'      => $this->lang->lang(),
            'tiny'      => $tiny,
        );

        $this->template->build('new_report', $data);
    }

    public function report($id)
    {
        if (empty($id) || is_null($id) || $id == '0') {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowmodule->getBugtrackerStatus()) {
            redirect(base_url(), 'refresh');
        }

        if ($this->bugtracker_model->ReportExist($id) == 0) {
            redirect(base_url('404'), 'refresh');
        }

        $data = array(
            'idlink'    => $id,
            'pagetitle' => $this->lang->line('tab_bugtracker'),
        );

        $this->template->build('report', $data);
    }

    public function create()
    {
        $title       = $this->input->post('title');
        $description = $_POST['description'];
        $type        = $this->input->post('type');
        $priority    = $this->input->post('priority');

        echo $this->bugtracker_model->insertIssue($title, $description, $type, $priority);
    }
}
