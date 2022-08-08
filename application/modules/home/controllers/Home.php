<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property $wowgeneral
 * @property $home_model
 * @property $news_model
 * @property $admin_model
 * @property $wowrealm
 * @property $template
 */
class Home extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('news/news_model');
        $this->load->config('home');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }
    }

    public function index()
    {
        if ($this->config->item('migrate_status') == '1') {
            $data = array(
                'lang' => $this->lang->lang()
            );
            $this->load->view('migrate', $data);
        } else {
            $discord = $this->home_model->getDiscordInfo();

            $data = array(
                'pagetitle'      => $this->lang->line('tab_home'),
                'slides'         => $this->home_model->getSlides()->result(),
                'NewsList'       => $this->news_model->getNewsList()->result(),
                'realmsList'     => $this->wowrealm->getRealms()->result(),
                // Discord
                'discord_code'   => $discord['code'],
                'discord_id'     => $discord['guild']['id'],
                'discord_icon'   => $discord['guild']['icon'],
                'discord_name'   => $discord['guild']['name'],
                'discord_counts' => $discord['approximate_presence_count'],
            );

            $this->template->build('home', $data);
        }
    }

    public function downloadRealmlist()
    {
        if ($this->config->item('realmlist')) {
            header('Content-type: text/plain; charset=UTF-8');
            header('Content-Disposition: attachment; filename="realmlist.wtf"');

            print "Set Realmlist " . $this->config->item('realmlist');
        } else {
            header("Content-Type: application/json; charset=UTF-8");

            http_response_code(404);

            echo json_encode(array("message" => "No realmlist has been set yet."));
        }
        die();
    }

    public function migrateNow()
    {
        $this->load->library('migration');

        if ($this->migration->current() === false) {
            show_error($this->migration->error_string());
        } else {
            redirect(base_url());
        }
    }

    public function setconfig()
    {
        $this->load->library('migration');

        $data = array(
            'name'       => $this->input->post('website_name'),
            'invitation' => $this->input->post('website_invitation'),
            'realmlist'  => $this->input->post('website_realmlist'),
            'expansion'  => $this->input->post('website_expansion'),
            'bnet'       => $this->input->post('website_bnet'),
            'emulator'   => $this->input->post('website_emulator')
        );

        $realmid   = $this->input->post('realm_id');
        $char_host = $this->input->post('character_hostname');
        $char_db   = $this->input->post('character_database');
        $char_user = $this->input->post('character_username');
        $char_pass = $this->input->post('character_password');
        $soap_host = $this->input->post('soap_hostname');
        $soap_port = $this->input->post('soap_port');
        $soap_user = $this->input->post('soap_username');
        $soap_pass = $this->input->post('soap_password');
        $emulator  = $this->input->post('emulator');

        // If OS is Windows, redis is not officially supported, so don't add it to checklist
        if (! strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0) {
            $data[] = ['redis' => $this->input->post('redis')];
        } else {
            $data[] = ['redis' => false];
        }

        $this->home_model->updateconfigs($data);

        if ($this->migration->current() === false) {
            show_error($this->migration->error_string());
        } else {
            $this->home_model->insertRealm($char_host, $char_user, $char_pass, $char_db, $realmid, $soap_host, $soap_user, $soap_pass, $soap_port, $emulator);
            sleep(3); //make sure redirected to correct location
            redirect(base_url());
        }
    }
}
