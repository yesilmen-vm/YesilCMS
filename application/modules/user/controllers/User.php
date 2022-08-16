<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 * @property User_model    $user_model
 */
class User extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }
    }

    private function recaptcha_validate($secret, $response): bool
    {
        $user_ip = $this->input->ip_address();
        $url     = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $response . "&remoteip=" . $user_ip;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $status = json_decode($output, true);

        if ($status['success']) {
            return true;
        }

        return false;
    }

    public function login()
    {
        if (! $this->wowmodule->getLoginStatus()) {
            redirect(base_url(), 'refresh');
        }

        if ($this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        // Return true if module is not enabled, else validate captcha
        $recaptcha = $this->wowmodule->getreCaptchaStatus() ? $this->recaptcha_validate($this->config->item('recaptcha_secret'), trim($this->input->post('g-recaptcha-response') ?? '')) : true;

        $data = [
            'pagetitle' => $this->lang->line('tab_login'),
            'recapKey'  => $this->config->item('recaptcha_sitekey'),
            'lang'      => $this->lang->lang()
        ];

        if ($this->input->method() == 'post') {
            $rules = [
                [
                    'field'  => 'username',
                    'label'  => 'Username/Email',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'password',
                    'label'  => 'Password',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == false) {
                $this->template->build('login', $data);
            } else {
                if ($recaptcha) {
                    $username = $this->input->post('username', true);
                    $password = $this->input->post('password');
                    $response = $this->user_model->authentication($username, $password);

                    if (! $response) {
                        $data['msg_error_login'] = lang('notification_user_error');
                        $this->template->build('login', $data);
                    } else {
                        redirect(site_url('panel'));
                    }
                } else {
                    $data['msg_error_login'] = lang('notification_recaptcha_error');
                    $this->template->build('login', $data);
                }
            }
        } else {
            $this->template->build('login', $data);
        }
    }

    public function register()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getRegisterStatus()) {
            redirect(base_url(), 'refresh');
        }

        if ($this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        // Return true if module is not enabled, else validate captcha
        $recaptcha = $this->wowmodule->getreCaptchaStatus() ? $this->recaptcha_validate($this->config->item('recaptcha_secret'), trim($this->input->post('g-recaptcha-response') ?? '')) : true;

        $data = [
            'pagetitle' => $this->lang->line('tab_register'),
            'recapKey'  => $this->config->item('recaptcha_sitekey'),
            'lang'      => $this->lang->lang()
        ];

        if ($this->input->method() == 'post') {
            $rules = [
                [
                    'field'  => 'username',
                    'label'  => 'Username',
                    'rules'  => 'trim|required|alpha_numeric|min_length[3]|max_length[16]|differs[nickname]',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'email',
                    'label'  => 'Email',
                    'rules'  => 'trim|required|valid_email',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'password',
                    'label'  => 'Password',
                    'rules'  => 'trim|required|alpha_numeric|min_length[8]',
                    'errors' => [
                        'required'      => 'You must provide a %s.',
                        'alpha_numeric' => 'The %s must be at least 8 alphanumeric characters long.',
                        'min_length[8]' => 'The %s must contain numbers and letters.'
                    ]
                ],
                [
                    'field'  => 'confirm_password',
                    'label'  => 'Confirm password',
                    'rules'  => 'trim|required|min_length[8]|matches[password]',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == false) {
                $this->template->build('register', $data);
            } else {
                if ($recaptcha) {
                    $username = $this->input->post('username', true);
                    $email    = $this->input->post('email', true);
                    $password = $this->input->post('password');
                    $emulator = $this->config->item('emulator');

                    if (! $this->wowauth->account_unique($username, 'username')) {
                        $data['msg_notification_account_already_exist'] = lang('notification_account_already_exist');
                        $this->template->build('register', $data);

                        return false;
                    }

                    if (! $this->wowauth->account_unique($email, 'email')) {
                        $data['msg_notification_used_email'] = lang('notification_used_email');
                        $this->template->build('register', $data);

                        return false;
                    }

                    $register = $this->user_model->insertRegister($username, $email, $password, $emulator);

                    if ($register) {
                        if ($this->user_model->sendActivationEmail($username, $email)) {
                            redirect(site_url('login'));
                        } else {
                            $data['resendActivation'] = lang('notification_account_not_created');
                            $this->template->build('login', $data);
                        }
                    } else {
                        $data['msg_notification_account_not_created'] = lang('notification_account_not_created');
                        $this->template->build('register', $data);
                    }
                } else {
                    $data['msg_error_login'] = lang('notification_recaptcha_error');
                    $this->template->build('register', $data);

                    return false;
                }
            }
        } else {
            $this->template->build('register', $data);
        }
    }

    public function logout()
    {
        $this->wowauth->logout();
    }

    public function pending()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url(), 'refresh');
        }

        if ($this->wowauth->getActivationStatus()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        $data = [
            'pagetitle' => $this->lang->line('tab_pending'),
            'recapKey'  => $this->config->item('recaptcha_sitekey'),
            'lang'      => $this->lang->lang(),
        ];

        $this->template->build('pending', $data);
    }

    public function recovery()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getRecoveryStatus()) {
            redirect(base_url(), 'refresh');
        }

        if ($this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        $data = [
            'pagetitle' => $this->lang->line('tab_reset'),
            'recapKey'  => $this->config->item('recaptcha_sitekey'),
            'lang'      => $this->lang->lang(),
        ];

        $this->template->build('recovery', $data);
    }

    public function forgotpassword()
    {
        // Return true if module is not enabled, else validate captcha
        $recaptcha = $this->wowmodule->getreCaptchaStatus() ? $this->recaptcha_validate($this->config->item('recaptcha_secret'), trim($this->input->post('recaptcha') ?? '')) : true;

        if ($recaptcha) {
            $username = $this->input->post('username');
            $email    = $this->input->post('email');

            $rules = [
                [
                    'field'  => 'username',
                    'label'  => 'Username',
                    'rules'  => 'trim|required|alpha_numeric|min_length[3]|max_length[16]|differs[nickname]',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'email',
                    'label'  => 'Email',
                    'rules'  => 'trim|required|valid_email',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == false) {
                echo 'emailOrUserErrP1';

                return false;
            } else {
                echo $this->user_model->sendRecoveryEmail($username, $email);
            }
        } else {
            echo 'captchaErr';

            return false;
        }
    }

    public function recoverpass($token)
    {
        echo $this->user_model->receiveRecoveryPassword($token);
    }

    public function activate($token)
    {
        $this->user_model->verifyActivationEmail($token);

        if ($this->wowauth->isLogged()) {
            redirect(base_url('panel'));
        }
        redirect(base_url('login'));
    }

    public function resendactivation()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url(), 'refresh');
        }

        $email    = $this->input->post('email');
        $username = $this->input->post('username');

        if ($email != false && $username != false) {
            $this->user_model->resendActivationEmail($username, $email);
            redirect(base_url('pending'));
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function panel()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        if (! $this->wowmodule->getUCPStatus()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        $data = [
            'pagetitle' => $this->lang->line('tab_account'),
            'lang'      => $this->lang->lang(),
        ];

        $this->template->build('panel', $data);
    }

    public function settings()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        if (! $this->wowmodule->getUCPStatus()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->isLogged()) {
            redirect(base_url(), 'refresh');
        }

        $data = [
            'pagetitle' => $this->lang->line('tab_account'),
            'lang'      => $this->lang->lang(),
        ];

        $this->template->build('settings', $data);
    }

    public function newusername()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        if ($this->input->method() == 'post') {
            $rules = [
                [
                    'field'  => 'newusername',
                    'label'  => 'New username',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'confirmusername',
                    'label'  => 'Confirm Username',
                    'rules'  => 'trim|required|matches[newusername]',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == false) {
                redirect(base_url('settings'), 'refresh');
            } else {
                $username    = $this->wowauth->getSiteUsernameID($this->session->userdata('wow_sess_id'));
                $newusername = $this->input->post('newusername', true);
                $password    = $this->input->post('password');
                $change      = $this->user_model->changeUsername($username, $newusername, $password);

                if ($change) {
                    redirect(site_url('logout'), 'refresh');
                } else {
                    redirect(site_url('settings'), 'refresh');
                }
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function newpass()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        if ($this->input->method() == 'post') {
            $rules = [
                [
                    'field'  => 'change_oldpass',
                    'label'  => 'Old password',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'change_password',
                    'label'  => 'New password',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'change_renewchange_password',
                    'label'  => 'Confirm password',
                    'rules'  => 'trim|required|matches[change_password]',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == false) {
                redirect(base_url('settings'), 'refresh');
            } else {
                $oldpass   = $this->input->post('change_oldpass');
                $newpass   = $this->input->post('change_password');
                $renewpass = $this->input->post('change_renewchange_password');

                if (! $this->wowauth->valid_password($this->session->userdata('wow_sess_username'), $oldpass)) {
                    redirect(site_url('settings'));
                }

                $change = $this->user_model->changePassword($newpass);

                if ($change) {
                    redirect(site_url('logout'), 'refresh');
                } else {
                    redirect(site_url('settings'), 'refresh');
                }
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function newemail()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        if ($this->input->method() == 'post') {
            $rules = [
                [
                    'field'  => 'change_newemail',
                    'label'  => 'New email',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'change_renewemail',
                    'label'  => 'Confirm email',
                    'rules'  => 'trim|required|matches[change_newemail]',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ],
                [
                    'field'  => 'change_password',
                    'label'  => 'Password',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'You must provide a %s.'
                    ]
                ]
            ];

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == false) {
                redirect(base_url('settings'), 'refresh');
            } else {
                $email    = $this->wowauth->getEmailID($this->session->userdata('wow_sess_id'));
                $newemail = $this->input->post('change_newemail', true);
                $password = $this->input->post('change_password');
                $change   = $this->user_model->changeEmail($email, $newemail, $password);

                if ($change) {
                    redirect(site_url('logout'), 'refresh');
                } else {
                    redirect(site_url('settings'), 'refresh');
                }
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function newavatar()
    {
        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }

        $avatar = $this->input->post('change_avatar');
        $change = $this->user_model->changeAvatar($avatar);

        if ($change) {
            redirect(site_url('panel'), 'refresh');
        } else {
            redirect(site_url('settings'), 'refresh');
        }
    }
}
