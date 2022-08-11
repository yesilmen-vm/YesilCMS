<?php

class Core
{

    private
        $appPath,
        $reCMS,
        $reConfig,
        $reDatabase,
        $input,
        $error = array();

    public function init($config): array
    {
        $this->appPath    = $config['application'];
        $this->reCMS      = [$config['theme_name']];
        $this->reConfig   = [
            $config['language'],
            $config['cookie_secure']
        ];
        $this->reDatabase = [
            $config['cms_host'],
            $config['cms_user'],
            $config['cms_pass'],
            $config['cms_db'],
            $config['auth_host'],
            $config['auth_user'],
            $config['auth_pass'],
            $config['auth_db'],
            $config['world_host'],
            $config['world_user'],
            $config['world_pass'],
            $config['world_db']
        ];

        if (! is_dir($this->appPath)) {
            $this->error[] = "Incorrect application folder";
        }

        return $this->error;
    }

    public function setInput($input)
    {
        $this->input = (object)$input;
    }

    public function reWrite(): bool
    {
        $reWriteFile = ['blizzcms', 'config', 'database'];

        foreach ($reWriteFile as $fileName):
            $filePath = "$this->appPath/config/$fileName.php";

            if (is_writeable($filePath)):
                switch ($fileName):
                    case 'blizzcms':
                        $find    = $this->reCMS;
                        $replace = [$this->input->theme];
                        break;
                    case 'config':

                        $find    = $this->reConfig;
                        $replace = [
                            $this->input->language,
                            $this->input->cookie_secure
                        ];
                        break;
                    case 'database':
                        $find    = $this->reDatabase;
                        $replace = [
                            $this->input->cms_host,
                            $this->input->cms_user,
                            $this->input->cms_pass,
                            $this->input->cms_db,
                            $this->input->auth_host,
                            $this->input->auth_user,
                            $this->input->auth_pass,
                            $this->input->auth_db,
                            $this->input->world_host,
                            $this->input->world_user,
                            $this->input->world_pass,
                            $this->input->world_db
                        ];
                        break;
                    default:
                        break;
                endswitch;
                $file    = file_get_contents($filePath);
                $file    = str_replace($find, $replace, $file);
                $reWrite = file_put_contents($filePath, $file);
            else:
                $this->error[] = "File $fileName.php can not be changed";
            endif;
        endforeach;

        return $this->error ? false : true;
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function PHPVersion()
    {
        if ((version_compare(PHP_VERSION, '7.1') >= 0) && (version_compare(PHP_VERSION, '8.1.9') <= 0)) {
            return true;
        }
    }

    public function checkExtension(): bool
    {
        return ! in_array(false, $this->getExtensionState(), true) && count($this->getExtensionState());
    }

    public function getExtensionState(): array
    {
        $redis = [];
        // If OS is Windows, redis is not officially supported, so don't add it to checklist
        if (! strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0) {
            $redis = array('redis' => extension_loaded('redis'));
        }

        $extensions = array(
            'curl'     => extension_loaded('curl'),
            'gd'       => extension_loaded('gd'),
            'mbstring' => extension_loaded('mbstring'),
            'mysqli'   => extension_loaded('mysqli'),
            'openssl'  => extension_loaded('openssl'),
            'soap'     => extension_loaded('soap'),
            'gmp'      => extension_loaded('gmp'),
            'json'     => extension_loaded('json'),
            'ctype'    => extension_loaded('ctype'),
        );

        return $extensions + $redis;
    }

    public function removeFiles($target)
    {
        $path   = preg_replace('#/[^/]*$#i', '', $_SERVER['PHP_SELF']);
        $schema = (! empty($_SERVER['HTTPS']) ? 'https://' : 'http://');
        $host   = str_replace('/installation', '', (rtrim($schema . $_SERVER['HTTP_HOST'] . $path, '/')));
        if (is_writeable($target)):
            if (is_dir($target)):
                $files = glob($target . '*', GLOB_MARK);

                foreach ($files as $file):
                    $this->removeFiles($file);
                endforeach;
                rmdir($target);
            elseif (is_file($target)):
                unlink($target);
            endif;
            header('Location: ' . $host);
        else:
            $this->error[] = "Folder is not writable, please enable mod_rewrite and set permissions";
        endif;
    }
}
