<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_model extends CI_Model
{
    protected $modules_table;

    /**
     * Module_model constructor.
     */
    public function __construct()
    {
        $this->modules_table = 'modules';
    }

    /**
     * @return bool
     */
    public function getDiscordStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '1')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getreCaptchaStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '2')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getSlideshowStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '3')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getRealmStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '4')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getRegisterStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '5')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getLoginStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '6')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getRecoveryStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '7')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getUCPStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '8')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getACPStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '9')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getNewsStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '10')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getForumStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '11')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getStoreStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '12')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getDonationStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '13')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getVoteStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '14')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getPVPStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '15')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getBugtrackerStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '16')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getChangelogsStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '17')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getDownloadStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '18')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getArmoryStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '19')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getTimelineStatus(): bool
    {
        $qq = $this->db->select('status')->where('id', '20')->get($this->modules_table)->row('status');

        if ($qq == '1') {
            return true;
        } else {
            return false;
        }
    }
}
