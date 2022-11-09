<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Realm_model   $wowrealm
 * @property Pvp_model     $pvp_model
 * @property Armory_model  $armory_model
 * @property Template      $template
 */
class Pvp extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pvp_model');
        $this->load->model('Armory/armory_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getPVPStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function index()
    {
        $realmNames = [];

        foreach ($this->wowrealm->getRealms()->result() as $charsMultiRealm) {
            $id              = $charsMultiRealm->id;
            $multiRealm      = $this->wowrealm->getRealmConnectionData($charsMultiRealm->id);
            $realmNames[$id] = $this->wowrealm->getRealmName($charsMultiRealm->realmID);
        }
        $data = array(
            'pagetitle' => $this->lang->line('tab_pvp_statistics'),
            'lang'      => $this->lang->lang(),
            'realms'    => $realmNames,
            'logged'    => $this->session->logged_in ?? false,
            'a_id'      => $this->session->wow_sess_id ?? false,
        );

        $this->template->build('index', $data);
    }

    public function pvpstats()
    {
        $postData   = $this->input->post();
        $list       = [];
        $data       = [];
        $id         = $postData['realm'] ?? 1;
        $multiRealm = $this->wowrealm->getRealmConnectionData($id);
        $list       = $this->pvp_model->getPVPList($multiRealm, $postData);

        if ($list) {
            foreach ($list['data'] as $key => $player) {
                $fact     = $this->wowgeneral->getFaction($player['race']);
                $guild    = $this->armory_model->getGuildInfoByPlayerID($multiRealm, $player['guid']);
                $hk       = $this->pvp_model->getKillsByDate($multiRealm, $player['guid'], (new DateTime())->diff(new DateTime('1970-01-01'))->format('%a'), 1);
                $dk       = $this->pvp_model->getKillsByDate($multiRealm, $player['guid'], (new DateTime())->diff(new DateTime('1970-01-01'))->format('%a'), 2);
                $yhk      = $this->pvp_model->getKillsByDate($multiRealm, $player['guid'], (new DateTime())->diff(new DateTime('1970-01-01'))->format('%a') - 1, 1);
                $ydk      = $this->pvp_model->getKillsByDate($multiRealm, $player['guid'], (new DateTime())->diff(new DateTime('1970-01-01'))->format('%a') - 1, 2);
                $pvp_info = $this->armory_model->getCurrentPVPRank($multiRealm, $player['guid']);
                if ($fact === 'Alliance') {
                    $title = $pvp_info['a_title'];
                } else {
                    $title = $pvp_info['h_title'];
                }

                // Datatables part, for easier handling apply table styling here
                $data[$key][] = '<strong>' . ordinalNumber($player['honor_standing'] ?? 0, true) . '</strong>';
                $data[$key][] = '<div class="uk-flex uk-text-bold"><img class="uk-preserve-width uk-border-rounded" src="' . base_url('application/modules/pvp/assets/images/ranks/PvPRank' . $pvp_info['rank']) . '.png" width="18" height="18" title="' . $title . '" alt="' . $title . '"><a class="pvp-name pvp-name-' . $fact . '" href="' . base_url() . 'armory/character/' . $id . '/' . $player['guid'] . '">' . $player['name'] . '</a></div>';
                $data[$key][] = $guild['name'] ?? '';
                $data[$key][] = $player['level'];
                $data[$key][] = '<div class="uk-flex uk-flex-center"><img class="uk-border-rounded" src="' . base_url() . 'application/modules/armory/assets/images/characters/' . getAvatar($player['class'], $player['race'], $player['gender'], $player['level']) . '" width="24" height="24" title="' . $this->wowgeneral->getRaceName($player['race']) . '" alt="' . $this->wowgeneral->getRaceName($player['race']) . '"><img class="uk-border-rounded uk-margin-small-left" src="' . base_url('assets/images/class/' . $this->wowgeneral->getClassIcon($player['class'])) . '" width="24" height="24" title="' . $this->wowgeneral->getClassName($player['class']) . '" alt="' . $this->wowgeneral->getClassName($player['class']) . '"></div>';
                $data[$key][] = '<img class="uk-border-circle" src="' . base_url('assets/images/factions/' . $this->wowgeneral->getFaction($player['race']) . '.png') . '" width="24" height="24" title="' . $this->wowgeneral->getFaction($player['race']) . '" alt="' . $this->wowgeneral->getFaction($player['race']) . '">';
                $data[$key][] = '<span title="Honorable Kills" class="pvp-kill-hk">' . ($hk + $player['honor_stored_hk']) . '</span> - <span title="Dishonorable Kills" class="pvp-kill-dk">' . ($dk + $player['honor_stored_dk']) . '</span>';
                $data[$key][] = '<span class="pvp-honor">' . formatStats($player['honor_rank_points']) . '</span>';
                $data[$key][] = '<span title="Honorable Kills" class="pvp-today-hk">' . $hk . '</span> - <span title="Dishonorable Kills" class="pvp-today-dk">' . $dk . '</span>';
                $data[$key][] = '<span title="Honorable Kills" class="pvp-yesterday-hk">' . $yhk . '</span> - <span title="Dishonorable Kills" class="pvp-yesterday-dk">' . $ydk . '</span>';
                $data[$key][] = $player['account'];
                $data[$key][] = $fact;
            }
        }

        if ($data) {
            $list['data'] = $data;
        }

        $list['token'] = $this->security->get_csrf_hash();

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($list));
    }
}
