<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

/**
 * @property General_model $wowgeneral
 * @property Module_model  $wowmodule
 * @property Auth_model    $wowauth
 * @property Template      $template
 * @property Donate_model  $donate_model
 */
class Donate extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('donate_model');
        $this->load->config('donate');

        if (! ini_get('date.timezone')) {
            date_default_timezone_set($this->config->item('timezone'));
        }

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getDonationStatus()) {
            redirect(base_url(), 'refresh');
        }

        if (! $this->wowauth->isLogged()) {
            redirect(base_url('login'), 'refresh');
        }

        if (! $this->wowauth->getActivationStatus()) {
            redirect(base_url('pending'));
        }
    }

    public function index()
    {
        $data = array(
            'pagetitle' => $this->lang->line('tab_donate'),
        );

        $this->template->build('index', $data);
    }

    public function check($id)
    {
        $execute = new PaymentExecution();

        $paymentId = $_GET['paymentId'];
        $payerId   = $_GET['PayerID'];
        $payment   = Payment::get($paymentId, $this->donate_model->getApi());

        $execute->setPayerId($payerId);
        try {
            $result = $payment->execute($execute, $this->donate_model->getApi());
        } catch (Exception $e) {
            die($e);
        }
        $this->donate_model->completeTransaction($id, $_GET['paymentId']);
    }

    public function canceled()
    {
        $this->session->set_flashdata('donation_status', 'canceled');
        redirect(base_url($this->lang->lang() . '/donate'));
    }
}
