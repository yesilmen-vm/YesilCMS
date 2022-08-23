<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Realm_model   $wowrealm
 * @property Module_model  $wowmodule
 * @property Template      $template
 * @property Armory_model  $armory_model
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

    public function character($realmid, $id, int $patch = null)
    {
        if (empty($id) && empty($realmid)) {
            redirect(base_url(), 'refresh');
        }

        $currentRealm     = $this->wowrealm->getRealmConnectionData($realmid);
        $currentRealmName = $this->wowrealm->getRealmName($realmid);
        $character        = $this->armory_model->getPlayerInfo($currentRealm, $id);
        $equippedItemIDs  = [];

        if (! $currentRealm) {
            redirect(base_url('404'), 'refresh');
        }
        if (! $character) {
            redirect(base_url('404'), 'refresh');
        }
        if (! empty($patch) && $patch > 10) {
            redirect(base_url('404'), 'refresh');
        }

        $guildInfo = $this->armory_model->getGuildInfoByPlayerID($currentRealm, $character['guid']);
        $slots     = [
            'L' => ['head', 'neck', 'shoulders', 'back', 'chest', 'shirt', 'tabard', 'wrists'],
            'R' => ['hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2'],
            'B' => ['mainhand', 'offhand', 'ranged']
        ];

        $character['faction']        = $this->wowgeneral->getFaction($this->wowrealm->getCharRace($id, $currentRealm)) ?? '';
        $character['guild_id']       = $guildInfo['guild_id'] ?? '';
        $character['guild_name']     = $guildInfo['name'] ?? '<i>Guildless</i>';
        $character['race_name']      = $this->wowgeneral->getRaceName($character['race']) ?? '';
        $character['class_name']     = $this->wowgeneral->getClassName($character['class']) ?? '';
        $character['equipped_items'] = $this->armory_model->getCharEquipments($currentRealm, $id, $patch);

        foreach ($character['equipped_items'] as $items) {
            $equippedItemIDs[] = $items['item_id'];
        }

        if (! empty($equippedItemIDs)) {
            sort($equippedItemIDs);

            $character['equipped_item_ids']      = $equippedItemIDs;
            $character['equipped_item_model']    = $this->armory_model->getCharEquipDisplayModel($id, $character['equipped_item_ids'], $character['class'], false, $patch);
            $character['equipped_item_id_model'] = json_encode($this->armory_model->getCharEquipDisplayModel($id, $character['equipped_item_ids'], $character['class'], true, $patch));
        } else {
            $character['equipped_item_ids'] = [];
        }

        $character['stats']                = $this->armory_model->calculateAuras($currentRealm, $id, $character['race'], $character['class'], $character['level'], $equippedItemIDs, $patch);
        $character['profession_primary']   = $this->armory_model->getCharProfessions($currentRealm, $id, 'P');
        $character['profession_secondary'] = $this->armory_model->getCharProfessions($currentRealm, $id, 'S');
        $character['honor_current_rank']   = $this->armory_model->getCurrentPVPRank($currentRealm, $id);
        $character['honor_total_hk']       = $this->armory_model->getTotalHK($currentRealm, $id);

        $data = [
            'id'               => $id,
            'patch'            => $patch ?? '',
            'realmid'          => $realmid,
            'pagetitle'        => 'Character Armory',
            'currentRealm'     => $currentRealm,
            'currentRealmName' => $currentRealmName,
            'slots'            => $slots,
            'character'        => $character,
            'lang'             => $this->lang->lang(),
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
