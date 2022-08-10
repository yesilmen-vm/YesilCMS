<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property $MultiRealm
 */
class Pvp_model extends CI_Model
{
    /**
     * Pvp_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getTop20PVP($MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('guid, name, race, class, gender, level, honor_rank_points, honor_last_week_cp, honor_stored_hk')->where('name !=', '')->order_by('honor_stored_hk', 'DESC')->limit('20')->get('characters');
    }

    public function getKillsByDate($MultiRealm, $id, $date)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('count(guid)')->where('guid', $id)->where('date', $date)->where('type', 1)->get('character_honor_cp')->row('count(guid)');
    }

    public function getTopArena2v2($MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('rating, seasonWins, arenaTeamId, name')->where('type', '2')->order_by('rating', 'DESC')->limit('10')->get('arena_team');
    }

    public function getTopArena3v3($MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('rating, seasonWins, arenaTeamId, name')->where('type', '3')->order_by('rating', 'DESC')->limit('10')->get('arena_team');
    }

    public function getTopArena5v5($MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('rating, seasonWins, arenaTeamId, name')->where('type', '5')->order_by('rating', 'DESC')->limit('10')->get('arena_team');
    }

    public function getMemberTeam($id, $MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('*')->where('arenaTeamId', $id)->get('arena_team_member');
    }

    public function getRaceGuid($id, $MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('race')->where('guid', $id)->get('characters')->row('race');
    }

    public function getClassGuid($id, $MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('class')->where('guid', $id)->get('characters')->row('class');
    }

    public function getNameGuid($id, $MultiRealm)
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('name')->where('guid', $id)->get('characters')->row('name');
    }
}
