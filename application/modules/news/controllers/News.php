<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 * @property News_model    $news_model
 */
class News extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getNewsStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function article($id)
    {
        $this->load->model('forum/forum_model');

        if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) >= config_item('admin_access_level')) {
            $tiny = $this->wowgeneral->tinyEditor('Admin');
        } else {
            $tiny = $this->wowgeneral->tinyEditor('User');
        }

        $data = array(
            'idlink'    => $id,
            'pagetitle' => $this->lang->line('tab_news'),
            'lang'      => $this->lang->lang(),
            'tiny'      => $tiny
        );

        $this->template->build('article', $data);
    }

    public function reply()
    {
        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        $ssesid = $this->session->userdata('wow_sess_id');
        $newsid = $this->input->post('news');
        $reply  = $_POST['reply'];

        echo $this->news_model->insertComment($reply, $newsid, $ssesid);
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

        echo $this->news_model->removeComment($id);
    }
}
