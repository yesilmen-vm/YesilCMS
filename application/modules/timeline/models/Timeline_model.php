<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 */
class Timeline_model extends CI_Model
{
    /**
     * Timeline_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getTimeline(): array
    {
        $timelineCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('timelineContent') : false;

        if ($timelineCache) {
            $timeline = $timelineCache;
        } else {
            $timeline = $this->db->order_by('order ASC, id ASC')->select('*')->get('timeline')->result_array();

            if ($this->wowgeneral->getRedisCMS() && $timeline) {
                // Cache for 1 day
                $this->cache->redis->save('timelineContent', $timeline, 86400);
            }
        }

        return $timeline;
    }
}
