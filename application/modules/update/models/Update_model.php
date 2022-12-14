<?php

defined('BASEPATH') or exit('No direct script access allowed');

use VisualAppeal\AutoUpdate;

class Update_model extends CI_Model
{
    /**
     * Update_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getCurrentVersion(): string
    {
        $version = '1.1.0.1';

        return $version;
    }


    /**
     * @return void
     */
    public function getWebServer()
    {
        echo $_SERVER['SERVER_SOFTWARE'];
    }


    /**
     * @return void
     */
    public function getPHPExtensions()
    {
        $extensions = get_loaded_extensions();
        foreach ($extensions as $extension) {
            echo "$extension ";
        }
    }


    /**
     * @return bool|string|void
     */
    public function checkUpdates()
    {
        $update = new AutoUpdate(FCPATH . 'temp', FCPATH . '', 60);
        $update->setCurrentVersion($this->getCurrentVersion()); // Current version of your application. This value should be from a database or another file which will be updated with the installation of a new version
        $update->setUpdateUrl(''); //Replace the url with your server update url

        // The following two lines are optional
        $update->addLogHandler(new Monolog\Handler\StreamHandler(FCPATH . 'logs/update.log'));
        // $update->setCache(new Desarrolla2\Cache\Adapter\File(APPPATH.'/cache'), 3600);

        //Check for a new update
        if ($update->checkUpdate() === false) {
            die('Could not check for updates! See log file for details.');
        }

        // Check if new update is available
        if ($update->newVersionAvailable()) {
            echo 'New Version: ' . $update->getLatestVersion();
            // Simulate or install?
            $simulate = false;
            //Install new update
            $result = $update->update($simulate);
            if ($result === true) {
                return true;
            } else {
                return 'UpdErr';
            }
        } else {
            // No new update
            return 'UpdnotFound';
        }
    }
}
