<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property $wowgeneral
 * @property $wowrealm
 * @property $wowmodule
 * @property $template
 * @property $armory_model
 */
class Armory extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('armory_model');

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getArmoryStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function search()
    {
        $data = [
            'pagetitle' => 'Armory Search',
            'lang'      => $this->lang->lang(),
            'realms'    => $this->wowrealm->getRealms()->result(),
        ];

        $this->template->build('search', $data);
    }

    public function index()
    {
        $data = [
            'pagetitle' => 'Armory',
            'lang'      => $this->lang->lang(),
            'realms'    => $this->wowrealm->getRealms()->result(),
        ];

        $this->template->build('search', $data);
    }

    public function character($realmid, $id)
    {
        if (empty($id) && empty($realmid)) {
            redirect(base_url(), 'refresh');
        }

        $data = [
            'id'        => $id,
            'realmid'   => $realmid,
            'pagetitle' => 'Character Armory',
            'lang'      => $this->lang->lang(),
        ];

        $this->template->build('index', $data);
    }

    public function guild($realmid, $guildid)
    {
        if (empty($guildid)) {
            redirect(base_url(), 'refresh');
        }

        $data = [
            'guildid'   => $guildid,
            'realmid'   => $realmid,
            'pagetitle' => 'Guild Members',
            'lang'      => $this->lang->lang(),
            'realms'    => $this->wowrealm->getRealms()->result(),
        ];

        $this->template->build('guild', $data);
    }

    public function result()
    {
        $data   = [
            'pagetitle' => 'Armory Search',
            'lang'      => $this->lang->lang(),
            'realms'    => $this->wowrealm->getRealms()->result(),
            'search'    => $this->input->get('search'),
            'realm'     => $this->input->get('realm')
        ];
        $search = $this->input->get('search');
        $realm  = $this->input->get('realm');

        if (! empty($search) && ! empty($realm)) {
            $MultiRealm = $this->wowrealm->getRealmConnectionData($realm);

            $data['chars'] = $this->armory_model->searchChar($MultiRealm, $search);
            $data['guild'] = $this->armory_model->searchGuild($MultiRealm, $search);
        }

        $this->template->build('result', $data);
    }
}
