<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Auth_model          $wowauth
 * @property General_model       $wowgeneral
 * @property CI_DB_query_builder $auth
 */
class User_model extends CI_Model
{
    /**
     * User_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth = $this->load->database('auth', true);
    }


    /**
     * @param $email
     *
     * @return mixed
     */
    public function getExistEmail($email)
    {
        return $this->auth->select('email')->where('email', $email)->get('account')->num_rows();
    }

    /**
     * @return CI_DB_result
     */
    public function getAllAvatars(): CI_DB_result
    {
        return $this->db->select()->order_by('id ASC')->get('avatars');
    }

    /**
     * @param $avatar
     *
     * @return bool
     */
    public function changeAvatar($avatar): bool
    {
        $this->db->set('profile', $avatar)->where('id', $this->session->userdata('wow_sess_id'))->update('users');

        return true;
    }

    /**
     * @param $id
     *
     * @return array|mixed|object|string|null
     */
    public function getDateMember($id)
    {
        $qq = $this->db->select('joindate')->where('id', $id)->get('users');

        if ($qq->num_rows()) {
            return $qq->row('joindate');
        } else {
            return 'Unknown';
        }
    }

    /**
     * @param $id
     *
     * @return array|mixed|object|string|null
     */
    public function getExpansion($id)
    {
        $qq = $this->db->select('expansion')->where('id', $id)->get('users');

        if ($qq->num_rows()) {
            return $qq->row('expansion');
        } else {
            return 'Unknown';
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getLastIp($id)
    {
        return $this->auth->select('last_ip')->where('id', $id)->get('account')->row('last_ip');
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function find_user($id): bool
    {
        $query = $this->db->where('id', $id)->get('users')->num_rows();

        return ($query == 1);
    }

    /**
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public function authentication($username, $password): bool
    {
        $accgame  = $this->auth->where('username', $username)->or_where('email', $username)->get('account')->row();
        $emulator = $this->config->item('emulator');

        if (empty($accgame)) {
            return false;
        }

        switch ($emulator) {
            case 'srp6':
                if ($this->auth->field_exists('verifier', 'account')) :
                    $validate = ($accgame->verifier === $this->wowauth->game_hash($accgame->username, $password, 'srp6', $accgame->salt));
                else :
                    $validate = ($accgame->v === $this->wowauth->game_hash($accgame->username, $password, 'srp6', $accgame->s));
                endif;
                break;
            case 'hex':
                $validate = (strtoupper($accgame->v) === $this->wowauth->game_hash($accgame->username, $password, 'hex', $accgame->s));
                break;
            case 'old-trinity':
                $validate = hash_equals(strtoupper($accgame->sha_pass_hash), $this->wowauth->game_hash($accgame->username, $password));
                break;
        }

        if (! isset($validate) || ! $validate) {
            return false;
        }

        // if account on website don't exist sync values from game account
        if (! $this->find_user($accgame->id)) {
            $this->db->insert('users', [
                'id'       => $accgame->id,
                'username' => $accgame->username,
                'email'    => $accgame->email,
                'joindate' => strtotime($accgame->joindate)
            ]);
        }

        $this->wowauth->arraySession($accgame->id);

        return true;
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $emulator
     *
     * @return bool
     * @throws Exception
     */
    public function insertRegister($username, $email, $password, $emulator): bool
    {
        $date      = $this->wowgeneral->getTimestamp();
        $expansion = $this->wowgeneral->getRealExpansionDB();
        $emulator  = $this->config->item('emulator');

        if ($emulator == "srp6") {
            $salt = random_bytes(32);

            if ($this->auth->field_exists('session_key', 'account')) :
                $data = [
                    'username'    => $username,
                    'salt'        => $salt,
                    'verifier'    => $this->wowauth->game_hash($username, $password, 'srp6', $salt),
                    'email'       => $email,
                    'expansion'   => $expansion,
                    'session_key' => null
                ];
            else :
                $data = [
                    'username'         => $username,
                    'salt'             => $salt,
                    'verifier'         => $this->wowauth->game_hash($username, $password, 'srp6', $salt),
                    'email'            => $email,
                    'expansion'        => $expansion,
                    'session_key_auth' => null,
                    'session_key_bnet' => null
                ];
            endif;

            $this->auth->insert('account', $data);
        } elseif ($emulator == "hex") {
            $salt = strtoupper(bin2hex(random_bytes(32)));

            $data = [
                'username'  => $username,
                'v'         => $this->wowauth->game_hash($username, $password, 'hex', $salt),
                's'         => $salt,
                'email'     => $email,
                'expansion' => $expansion,
                'last_ip'   => '127.0.0.1'
            ];

            $this->auth->insert('account', $data);
        } elseif ($emulator == "old-trinity") {
            $data = [
                'username'      => $username,
                'sha_pass_hash' => $this->wowauth->game_hash($username, $password),
                'email'         => $email,
                'expansion'     => $expansion,
                'sessionkey'    => '',
            ];

            $this->auth->insert('account', $data);
        }

        $id = $this->wowauth->getIDAccount($username);

        if ($this->config->item('bnet_enabled')) {
            $data1 = [
                'id'            => $id,
                'email'         => $email,
                'sha_pass_hash' => $this->wowauth->game_hash($email, $password, 'bnet')
            ];

            $this->auth->insert('battlenet_accounts', $data1);

            $this->auth->set('battlenet_account', $id)->where('id', $id)->update('account');
            $this->auth->set('battlenet_index', '1')->where('id', $id)->update('account');
        }

        $website = [
            'id'       => $id,
            'username' => $username,
            'email'    => $email,
            'joindate' => $date,
            'dp'       => 0,
            'vp'       => 0
        ];

        $this->db->insert('users', $website);

        return true;
    }

    /**
     * @param $username
     *
     * @return mixed
     */
    public function checkuserid($username)
    {
        return $this->auth->select('id')->where('username', $username)->get('account')->row('id');
    }

    /**
     * @param $email
     *
     * @return mixed
     */
    public function checkemailid($email)
    {
        return $this->auth->select('id')->where('email', $email)->get('account')->row('id');
    }

    /**
     * @param $username
     * @param $email
     *
     * @return bool|string|void
     * @throws Exception
     */
    public function sendRecoveryEmail($username, $email)
    {
        $ucheck = $this->checkuserid($username);
        $echeck = $this->checkemailid($email);

        if ($ucheck == $echeck) {
            $hash = generateToken(64);

            $data = [
                'email' => $email,
                'hash'  => $hash,
                'date'  => time()
            ];

            $template                 = file_get_contents($this->config->item('template_recover_p1'));
            $mail_data                = [];
            $mail_data['projectname'] = $this->config->item('website_name');
            $mail_data['baseurl']     = base_url();
            $mail_data['email']       = $email;
            $mail_data['link']        = base_url('recoverpass/' . $hash);
            $mail_data['discordlink'] = $this->config->item('discord_invitation');
            $mail_data['copyright']   = 'Copyright &copy; ' . date("Y") . ' ' . $this->config->item('website_name');

            foreach ($mail_data as $key => $value) {
                $template = str_replace('{{' . $key . '}}', $value, $template);
            }
            if ($this->db->insert('users_password_resets', $data)) {
                if ($this->wowgeneral->smtpSendEmail($email, $this->lang->line('email_password_recovery_p1'), $template)) {
                    $this->session->set_flashdata('recovery_email_sent', true);

                    return true;
                }
            } else {
                $this->session->set_flashdata('recovery_email_sent', false);
            }
        } else {
            return 'emailOrUserErrP2';
        }
    }


    /**
     * @param $hash
     *
     * @return void
     * @throws Exception
     */
    public function receiveRecoveryPassword($hash)
    {
        $db    = $this->db->select('email, date')->where('hash', $hash)->get('users_password_resets');
        $email = $db->row('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && $db->num_rows() > 0) {
            $date = $db->row('date');

            if ((time() - $date) <= 3600) {
                $allowed_chars      = "0123456789abcdefghijklmnopqrstuvwxyz";
                $password_generated = substr(str_shuffle($allowed_chars), 0, 14);
                $newpass            = $password_generated;

                $db       = $this->auth->select('id, username')->where('email', $email)->get('account');
                $accgame  = $this->auth->where('id', $db->row('id'))->get('account')->row();
                $emulator = $this->config->item('emulator');

                if (empty($accgame)) {
                    $this->session->set_flashdata('recovery_email_sent', false);
                } else {
                    $template                 = file_get_contents($this->config->item('template_recover_p2'));
                    $mail_data                = [];
                    $mail_data['projectname'] = $this->config->item('website_name');
                    $mail_data['baseurl']     = base_url();
                    $mail_data['username']    = ucfirst($db->row('username'));
                    $mail_data['password']    = $newpass;
                    $mail_data['discordlink'] = $this->config->item('discord_invitation');
                    $mail_data['copyright']   = 'Copyright &copy; ' . date("Y") . ' ' . $this->config->item('website_name');

                    foreach ($mail_data as $key => $value) {
                        $template = str_replace('{{' . $key . '}}', $value, $template);
                    }
                    if ($this->generateHash($emulator, $accgame->username, $newpass)) {
                        if ($this->wowgeneral->smtpSendEmail($email, $this->lang->line('email_password_recovery_p2'), $template)) {
                            $this->db->where('email', $email)->delete('users_password_resets');
                            $this->session->set_flashdata('recovery_token_valid', true);
                        }
                    } else {
                        $this->session->set_flashdata('recovery_email_sent', false);
                    }
                }
            } else {
                $this->db->where('email', $email)->where('hash', $hash)->delete('users_password_resets'); //get rid of expired tokens
                $this->session->set_flashdata('recovery_token_expired', false);
            }
        } else {
            $this->session->set_flashdata('recovery_token_valid', false);
        }
        redirect(base_url('login'));
    }

    /**
     * @param        $email
     * @param  bool  $expired
     *
     * @return CI_DB_result|false
     */
    public function getActivationDetails($email, bool $expired = false)
    {
        //make sure to update status of expired tokens of current user
        if ($this->db->where('email', $email)->where('requested_at <=', time() - 3600)->where('status', 0)->update('users_activation_status', ['status' => -1])) {
            if ($expired) {
                return $this->db->select()->where('email', $email)->where('status', -1)->order_by('id', 'DESC')->limit(1)->get('users_activation_status');
            }

            return $this->db->select()->where('email', $email)->where('status', 0)->order_by('id', 'DESC')->limit(1)->get('users_activation_status');
        }

        return false;
    }

    /**
     * @param $email
     *
     * @return float|int
     */
    public function getActivationTries($email)
    {
        $db    = $this->db->select('requested_at')->where('email', $email)->where('requested_at >=', time() - 3600)->where('status <', 1)->order_by('id DESC')->get('users_activation_status');
        $tries = $db->num_rows() ?? 0;
        switch ($tries) {
            case 0:
                $decrease = 60;
                break;
            case 1:
                $decrease = 55;
                break;
            case 2:
                $decrease = 50;
                break;
            case 3:
                $decrease = 45;
                break;
            case 4:
                $decrease = 30;
                break;
            default:
                $decrease = 0;
                break;
        }
        $timeleft = 3600 + (int)($db->row('requested_at') - time()) - ($decrease * 60);

        return max($timeleft, 0);
    }


    /**
     * @param $username
     * @param $email
     *
     * @return bool
     * @throws Exception
     */
    public function resendActivationEmail($username, $email): bool
    {
        if ($this->getActivationTries($email) == 0) {
            $this->db->where('email', $email)->where('status >=', 0)->update('users_activation_status', ['status' => -1]);
            if ($this->sendActivationEmail($username, $email)) {
                $this->session->set_flashdata('activation_email_resent', true);

                return true;
            }
        }
        $this->session->set_flashdata('activation_email_resent', false);

        return false;
    }

    /**
     * @param $username
     * @param $email
     *
     * @return bool|void
     * @throws Exception
     */
    public function sendActivationEmail($username, $email)
    {
        $ucheck = $this->checkuserid($username);
        $echeck = $this->checkemailid($email);

        $verified = $this->auth->where('id', $ucheck)->where('email', $email)->get('account')->row('email_verif');

        if ($ucheck == $echeck && ! $verified) {
            $hash = generateToken(64);
            $data = [
                'email'        => $email,
                'hash'         => $hash,
                'status'       => 0,
                'requested_at' => time(),
                'ip'           => inet_pton($_SERVER['REMOTE_ADDR'])
            ];

            $template                 = file_get_contents($this->config->item('template_verification'));
            $mail_data                = [];
            $mail_data['projectname'] = $this->config->item('website_name');
            $mail_data['baseurl']     = base_url();
            $mail_data['username']    = ucfirst($username);
            $mail_data['email']       = $email;
            $mail_data['link']        = base_url('activate/' . $hash);
            $mail_data['discordlink'] = $this->config->item('discord_invitation');
            $mail_data['copyright']   = 'Copyright &copy; ' . date("Y") . ' ' . $this->config->item('website_name');

            foreach ($mail_data as $key => $value) {
                $template = str_replace('{{' . $key . '}}', $value, $template);
            }
            if ($this->db->insert('users_activation_status', $data)) {
                if ($this->wowgeneral->smtpSendEmail($email, sprintf($this->lang->line('email_account_activation'), $this->config->item('website_name')), $template)) {
                    $this->session->set_flashdata('activation_email_sent', true);

                    return true;
                }
            } else {
                $this->session->set_flashdata('activation_email_sent', false);

                return false;
            }
        } else {
            $this->session->set_flashdata('activation_token_expired', true); //already activated

            return false;
        }
    }

    /**
     * @param $hash
     *
     * @return bool
     */
    public function verifyActivationEmail($hash): bool
    {
        $db    = $this->db->select('email, status, requested_at')->where('hash', $hash)->get('users_activation_status');
        $email = $db->row('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && $db->num_rows() > 0) {
            $date = $db->row('requested_at');

            if ((time() - $date) <= 3600) {
                $dba     = $this->auth->select('id, username')->where('email', $email)->get('account');
                $accgame = $this->auth->where('id', $dba->row('id'))->get('account')->row();

                if (empty($accgame)) {
                    $this->session->set_flashdata('activation_not_found', false);

                    return false;
                } else {
                    if ($db->row('status') == '0') {
                        if ($this->auth->where('id', $dba->row('id'))->where('email', $email)->update('account', ['email_verif' => 1])) {
                            $update = array(
                                'status'       => 1,
                                'activated_at' => time()
                            );

                            $this->db->where('email', $email)->where('hash', $hash)->update('users_activation_status', $update);
                            $this->session->set_flashdata('activation_token_valid', true);

                            return true;
                        } else {
                            $this->session->set_flashdata('activation_token_valid', false);

                            return false;
                        }
                    } else {
                        $this->session->set_flashdata('activation_token_expired', true); //already activated

                        return false;
                    }
                }
            } else {
                $this->db->where('email', $email)->where('hash', $hash)->update('users_activation_status', ['status' => -1]);
                $this->session->set_flashdata('activation_token_expired', false);

                return false;
            }
        } else {
            $this->session->set_flashdata('activation_token_valid', false);

            return false;
        }
    }

    /**
     * @param $account
     *
     * @return int
     */
    public function getIDPendingUsername($account): int
    {
        return $this->db->select('id')->where('username', $account)->get('pending_users')->num_rows();
    }

    /**
     * @param $email
     *
     * @return int
     */
    public function getIDPendingEmail($email): int
    {
        return $this->db->select('id')->where('email', $email)->get('pending_users')->num_rows();
    }

    /**
     * @param $key
     *
     * @return int
     */
    public function checkPendingUser($key): int
    {
        return $this->db->select('id')->where('key', $key)->get('pending_users')->num_rows();
    }

    /**
     * @param $key
     *
     * @return array|null
     */
    public function getTempUser($key): ?array
    {
        return $this->db->select()->where('key', $key)->get('pending_users')->row_array();
    }

    /**
     * @param $key
     *
     * @return false|mixed|string
     */
    public function removeTempUser($key)
    {
        return $this->db->where('key', $key)->delete('pending_users');
    }

    /**
     * @param $key
     *
     * @return void
     */
    public function activateAccount($key)
    {
        $check = $this->checkPendingUser($key);
        $temp  = $this->getTempUser($key);

        if ($check == "1") {
            if ($this->wowgeneral->getExpansionAction() == 1) {
                $data = [
                    'username'      => $temp['username'],
                    'sha_pass_hash' => $temp['password'],
                    'email'         => $temp['email'],
                    'expansion'     => $temp['expansion'],
                ];

                $this->auth->insert('account', $data);
            } else {
                $data = [
                    'username'        => $temp['username'],
                    'sha_pass_hash'   => $temp['password'],
                    'email'           => $temp['email'],
                    'expansion'       => $temp['expansion'],
                    'battlenet_index' => '1',
                ];

                $this->auth->insert('account', $data);

                $id = $this->wowauth->getIDAccount($temp['username']);

                $data1 = [
                    'id'            => $id,
                    'email'         => $temp['email'],
                    'sha_pass_hash' => $temp['password_bnet']
                ];

                $this->auth->insert('battlenet_accounts', $data1);

                $this->auth->set('battlenet_account', $id)->where('id', $id)->update('account');
            }

            $id = $this->wowauth->getIDAccount($temp['username']);

            $data3 = [
                'id'       => $id,
                'username' => $temp['username'],
                'email'    => $temp['email'],
                'joindate' => $temp['joindate']
            ];

            $this->db->insert('users', $data3);

            $this->removeTempUser($key);

            $this->session->set_flashdata('valid_key', true);
            redirect(base_url('login'));
        } else {
            $this->session->set_flashdata('valid_key', false);
        }
        redirect(base_url('login'));
    }


    /**
     * @param $username
     * @param $newusername
     * @param $password
     *
     * @return bool
     * @throws Exception
     */
    public function changeUsername($username, $newusername, $password): bool
    {
        $accgame  = $this->auth->where('username', $username)->or_where('email', $username)->get('account')->row();
        $id       = $this->session->userdata('wow_sess_id');
        $emulator = $this->config->item('emulator');

        if (empty($accgame)) {
            return false;
        }

        switch ($emulator) {
            case 'srp6':
                $validate = ($accgame->verifier === $this->wowauth->game_hash($accgame->username, $password, 'srp6', $accgame->salt));
                break;
            case 'hex':
                $validate = (strtoupper($accgame->v) === $this->wowauth->game_hash($accgame->username, $password, 'hex', $accgame->s));
                break;
            case 'old_trinity':
                $validate = hash_equals(strtoupper($accgame->sha_pass_hash), $this->wowauth->game_hash($accgame->username, $password));
                break;
        }

        if (! isset($validate) || ! $validate) {
            return false;
        }

        $query = $this->db->set('username', $newusername)->where('id', $id)->or_where('username', $username)->update('users');

        if (empty($query)) {
            return false;
        } else {
            $this->auth->set('username', $newusername)->where('id', $id)->or_where('username', $username)->update('account');
        }
        if ($this->generateHash($emulator, $newusername, $password)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $newpass
     *
     * @return bool
     * @throws Exception
     */
    public function changePassword($newpass): bool
    {
        $accgame  = $this->auth->where('id', $this->session->userdata('wow_sess_id'))->get('account')->row();
        $emulator = $this->config->item('emulator');

        if (empty($accgame)) {
            return false;
        }

        if ($this->generateHash($emulator, $accgame->username, $newpass)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $email
     * @param $newemail
     * @param $password
     *
     * @return bool
     * @throws Exception
     */
    public function changeEmail($email, $newemail, $password): bool
    {
        $accgame  = $this->auth->where('email', $email)->get('account')->row();
        $id       = $this->session->userdata('wow_sess_id');
        $emulator = $this->config->item('emulator');

        if (empty($accgame)) {
            return false;
        }

        switch ($emulator) {
            case 'srp6':
                $validate = ($accgame->verifier === $this->wowauth->game_hash($accgame->username, $password, 'srp6', $accgame->salt));
                break;
            case 'hex':
                $validate = (strtoupper($accgame->v) === $this->wowauth->game_hash($accgame->username, $password, 'hex', $accgame->s));
                break;
            case 'old_trinity':
                $validate = hash_equals(strtoupper($accgame->sha_pass_hash), $this->wowauth->game_hash($accgame->username, $password));
                break;
        }

        if (! isset($validate) || ! $validate) {
            return false;
        }

        $query = $this->db->set('email', $newemail)->where('id', $id)->or_where('email', $email)->update('users');

        if (empty($query)) {
            return false;
        } else {
            $this->auth->set('email', $newemail)->where('id', $id)->or_where('email', $email)->update('account');
        }
        if ($this->generateHash($emulator, $accgame->username, $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $emulator
     * @param $username
     * @param $password
     *
     * @return bool
     * @throws Exception
     */
    public function generateHash($emulator, $username, $password): bool
    {
        if ($emulator == "srp6") {
            $salt = random_bytes(32);

            $data = [
                'salt'     => $salt,
                'verifier' => $this->wowauth->game_hash($username, $password, 'srp6', $salt)
            ];

            $this->auth->where('username', $username)->update('account', $data);
        } elseif ($emulator == "hex") {
            $salt = strtoupper(bin2hex(random_bytes(32)));

            $data = [
                'v' => $this->wowauth->game_hash($username, $password, 'hex', $salt),
                's' => $salt,
            ];

            $this->auth->where('username', $username)->update('account', $data);
        } elseif ($emulator == "old-trinity") {
            $data = [
                'sha_pass_hash' => $this->wowauth->game_hash($username, $password),
            ];

            $this->auth->where('username', $username)->update('account', $data);
        }

        return true;
    }
}
