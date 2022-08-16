<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 * @property Service_model $service
 * @property Forum_model   $forum_model
 */
class Forum extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('forum_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->service->modService->checkAccBan($this->session->userdata('wow_sess_id'))) {
            redirect(base_url('accBanned'), 'refresh');
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getForumStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function index()
    {
        $data = [
            'pagetitle' => $this->lang->line('tab_forum'),
        ];

        $this->template->build('index', $data);
    }

    public function category($id)
    {
        if (empty($id) || is_null($id)) {
            redirect(base_url('forum'), 'refresh');
        }

        if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) >= config_item('admin_access_level')) {
            $tiny = $this->wowgeneral->tinyEditor('Admin');
        } else {
            $tiny = $this->wowgeneral->tinyEditor('User');
        }

        $data = [
            'idlink'    => $id,
            'pagetitle' => $this->lang->line('tab_forum'),
            'tiny'      => $tiny
        ];

        if ($this->forum_model->authType($id) == 2) :
            if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) <= config_item('mod_access_level')) :
                redirect(base_url('forum'), 'refresh');
            endif;
        endif;

        $this->template->build('category', $data);
    }

    public function topic($id)
    {
        if (empty($id) || is_null($id)) {
            redirect(base_url('forum'), 'refresh');
        }

        if ($this->forum_model->authType('1') == 2 && $this->wowauth->getRank($this->session->userdata('wow_sess_id')) >= config_item('mod_access_level')) {
            redirect(base_url('forum'), 'refresh');
        } elseif ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) >= config_item('admin_access_level')) {
            $tiny = $this->wowgeneral->tinyEditor('Admin');
        } else {
            $tiny = $this->wowgeneral->tinyEditor('User');
        }

        $data = [
            'idlink'    => $id,
            'pagetitle' => $this->lang->line('tab_forum'),
            'lang'      => $this->lang->lang(),
            'tiny'      => $tiny
        ];

        $this->template->build('topic', $data);
    }

    public function newtopic($idlink)
    {
        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) >= config_item('admin_access_level')) {
            $tiny = $this->wowgeneral->tinyEditor('Admin');
        } else {
            $tiny = $this->wowgeneral->tinyEditor('User');
        }

        $data = [
            'idlink'    => $idlink,
            'pagetitle' => $this->lang->line('tab_forum'),
            'lang'      => $this->lang->lang(),
            'tiny'      => $tiny,
        ];

        if ($this->input->method() == 'post') {
            $category    = $this->input->post('category');
            $title       = $this->input->post('title');
            $ssesid      = $this->session->userdata('wow_sess_id');
            $description = $_POST['description'];
            echo $this->forum_model->newTopic($category, $title, $ssesid, $description, '0', '0');
        } else {
            $this->template->build('new_topic', $data);
        }
    }

    public function reply()
    {
        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        $ssesid  = $this->session->userdata('wow_sess_id');
        $topicid = $this->input->post('topic');
        $reply   = $_POST['reply'];

        echo $this->forum_model->newComment($reply, $topicid, $ssesid);
    }

    public function update()
    {
        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        $idlink      = $this->input->post('topic');
        $title       = $this->input->post('title');
        $description = $this->input->post('content');

        echo $this->forum_model->updateTopic($idlink, $title, $description);
    }

    public function deletereply()
    {
        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        $id = $this->input->post('value');

        echo $this->forum_model->removeComment($id);
    }

    public function addtopic()
    {
        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }
    }
}
