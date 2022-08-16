<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 * @property Vote_model    $vote_model
 */
class Vote extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vote_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getVoteStatus()) {
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
            'pagetitle' => $this->lang->line('tab_vote'),
            'voteList'  => $this->vote_model->getVotes(),
        );

        $this->template->build('index', $data);
    }

    public function voteNow($id)
    {
        echo $this->vote_model->voteNow($id);
    }

    public function voteNowCount()
    {
        $id      = $this->input->post('value', true);
        $seconds = $this->vote_model->getVoteTime($id);

        echo $this->vote_model->getCountDownHTML($id, $seconds);
    }

    public function voteCallURL()
    {
        $id = $this->input->post('value', true);

        echo $this->vote_model->getVoteUrl($id);
    }
}
