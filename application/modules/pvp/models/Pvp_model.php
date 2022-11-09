<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property               $MultiRealm
 * @property General_model wowgeneral
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

    public function getPVPList($MultiRealm, $pData = null): array
    {
        $draw        = $pData['draw'];
        $start       = $pData['start'];
        $rowperpage  = $pData['length'];
        $searchValue = $pData['search']['value'];
        $faction     = (int)($pData['faction'] ?? -1);
        $interval    = (int)($pData['interval'] ?? 0);

        $this->MultiRealm = $MultiRealm;
        $races            = [0 => [1, 3, 4, 7], 1 => [2, 5, 6, 8]];
        $race_q           = $faction === 0 || $faction === 1 ? $races[$faction] : array_merge(...$races);

        // Total number of records without filtering
        $recordsCountFCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('characterRecordsCount_NoFilter-F_' . $faction . '-I_' . $interval) : false;
        if ($recordsCountFCache) {
            $totalRecords = $recordsCountFCache;
        } else {
            $this->MultiRealm->select('count(*) as total');
            $this->MultiRealm->where_in('race', $race_q);
            $this->MultiRealm->where($interval === 0 ? 'honor_stored_hk >=' : 'honor_last_week_hk >=', 15);

            $records      = $this->MultiRealm->get('characters')->result_array();
            $totalRecords = $records[0]['total'];

            if ($this->wowgeneral->getRedisCMS() && $totalRecords) {
                // Cache for 1 hour
                $this->cache->redis->save('characterRecordsCount_NoFilter-F_' . $faction . '-I_' . $interval, $totalRecords, 60 * 60);
            }
        }

        // Total number of record with filtering, no point of caching this query
        $this->MultiRealm->select('count(*) as total');
        $this->MultiRealm->where_in('race', $race_q);
        $this->MultiRealm->where($interval === 0 ? 'honor_stored_hk >=' : 'honor_last_week_hk >=', 15);

        if ($searchValue != '') {
            $this->MultiRealm->like('name', $searchValue);
        }
        $records               = $this->MultiRealm->get('characters')->result_array();
        $totalRecordwithFilter = $records[0]['total'];

        $pvpResultCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('pvpResult-F_' . $faction . '-I_' . $interval . '-R_' . $rowperpage . '-S_' . $start) : false;
        if ($pvpResultCache && $searchValue === '') {
            $records = $pvpResultCache;
        } else {
            // Fetch records, consider caching this for only when there is no search query
            $defaults = 'guid, account, name, race, class, gender, level';
            if ($interval === 0) {
                $subQ  = $this->MultiRealm->select($defaults . ', honor_rank_points, honor_stored_hk, honor_stored_dk')->where("honor_stored_hk >=", 15)->where_in('race', $race_q)->order_by('honor_rank_points, honor_stored_hk', 'DESC')->get_compiled_select("(characters, (SELECT @rank := 0) r)");
                $subQ2 = $this->MultiRealm->select("(@rank := @rank + 1) AS honor_standing, m.*", false)->get_compiled_select('(' . $subQ . ') m');
                $this->MultiRealm->select($defaults . ', honor_rank_points, honor_stored_hk, honor_stored_dk, honor_standing');
            } else {
                $this->MultiRealm->select($defaults . ', honor_last_week_cp as honor_rank_points, honor_last_week_hk as honor_stored_hk, honor_stored_dk, honor_standing');
            }

            $this->MultiRealm->where_in('race', $race_q);
            $this->MultiRealm->where($interval === 0 ? 'honor_stored_hk >=' : 'honor_last_week_hk >=', 15);

            if ($searchValue != '') {
                $this->MultiRealm->like('name', $searchValue);
            }

            $this->MultiRealm->order_by('honor_rank_points, honor_stored_hk', 'DESC');
            $this->MultiRealm->limit($rowperpage, $start);
            if ($interval === 0) {
                $records = $this->MultiRealm->get('(' . $subQ2 . ') t')->result_array();
            } else {
                $records = $this->MultiRealm->get('characters')->result_array();
            }

            if ($this->wowgeneral->getRedisCMS() && $records && $searchValue === '') {
                // Cache for 1 hour
                $this->cache->redis->save('pvpResult-F_' . $faction . '-I_' . $interval . '-R_' . $rowperpage . '-S_' . $start, $records, 60 * 60);
            }
        }

        // Prepare response for controller
        return [
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalRecordwithFilter),
            "data"            => $records
        ];
    }

    public function getTop20PVP($MultiRealm, $race_idx = -1)
    {
        $this->MultiRealm = $MultiRealm;
        $races            = [0 => [1, 3, 4, 7], 1 => [2, 5, 6, 8]];

        return $this->MultiRealm->select('guid, account, name, race, class, gender, level, honor_rank_points, honor_last_week_cp, honor_stored_hk, honor_stored_dk')->where('name !=', '')->where_in('race', $race_idx === 0 || $race_idx === 1 ? $races[$race_idx] : array_merge(...$races))->order_by('honor_stored_hk', 'DESC')->limit('20')->get('characters');
    }

    public function getKillsByDate($MultiRealm, $id, $date, $type = 1, $date_interval = '')
    {
        $this->MultiRealm = $MultiRealm;

        return $this->MultiRealm->select('count(guid)')->where('guid', $id)->where('date' . $date_interval, $date)->where('type', $type)->get('character_honor_cp')->row('count(guid)');
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
