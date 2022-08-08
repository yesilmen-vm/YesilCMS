<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api_v1 extends
    REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
    }

    /**
     * Hello.
     *
     * @return Response
     */
    public function index_get()
    {
        $data = 'Ready for action.';
        $this->response([
            'status'  => true,
            'message' => $data
        ], REST_Controller::HTTP_OK);
    }

    // This list can also be installed to own database by downloading it from WoWTools
    public function classic_displayid_get($id = 0)
    {
        $build = '1.14.3.44403';
        if ($id > 0) {
            $classicDisplayCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('itemClassicDisplayID_' . $id) : false;
            if ($classicDisplayCache) {
                $status = REST_Controller::HTTP_OK;
                $data   = ['ItemDisplayInfoID' => (int)$classicDisplayCache];
            } else {
                $appearanceId = json_decode($this->getUrlContents('https://wow.tools/dbc/api/peek/itemmodifiedappearance?build=' . $build . '&col=ItemID&val=' . $id))->values->ItemAppearanceID ?? 0;
                if ($appearanceId > 0) {
                    $displayId = json_decode($this->getUrlContents('https://wow.tools/dbc/api/peek/itemappearance?build=' . $build . '&col=ID&val=' . $appearanceId))->values->ItemDisplayInfoID ?? 0;
                    if ($displayId > 0) {
                        $status = REST_Controller::HTTP_OK;
                        $data   = ['ItemDisplayInfoID' => (int)$displayId];
                        if ($this->wowgeneral->getRedisCMS()) {
                            // Cache for 1 day
                            $this->cache->redis->save('itemClassicDisplayID_' . $id, $displayId, 86400);
                        }
                    } else {
                        $status = REST_Controller::HTTP_NOT_FOUND;
                        $data   = [
                            'status'       => $status,
                            'errorMessage' => 'Item display info ID not found.'
                        ];
                    }
                } else {
                    $status = REST_Controller::HTTP_NOT_FOUND;
                    $data   = [
                        'status'       => $status,
                        'errorMessage' => 'Item appearance ID not found.'
                    ];
                }
            }
        } else {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => $id . ' is not supported for item_id field.'
            ];
        }
        $this->response($data, $status);
    }

    // Need to use this instead of file_get_contents thanks to OpenSSL bug (0A000126:SSL)
    private function getUrlContents($url)
    {
        if (! function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
