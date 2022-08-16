<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Module_model $wowmodule
 */
class Mod_model extends CI_Model
{
    private $_limit;
    private $_pageNumber;
    private $_offset;

    /**
     * Mod_model constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (! $this->wowmodule->getACPStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    public function setPageNumber($pageNumber)
    {
        $this->_pageNumber = $pageNumber;
    }

    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    public function getLogs(): CI_DB_result
    {
        return $this->db->select('*')->get('mod_logs');
    }

    public function getReports(): CI_DB_result
    {
        return $this->db->select('*')->get('mod_reports');
    }
}
