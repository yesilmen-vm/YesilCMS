<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <div class="uk-width-1-1@m">
            <div class="uk-alert-danger" id="na" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><i class="fas fa-triangle-exclamation"></i> <strong>Attention!</strong> Your account has not been activated.</p>
            </div>
            <script>
                setTimeout(function() {
                    UIkit.alert('#na').close();
                }, 2500);
            </script>
            <?php if ($this->session->flashdata('activation_email_resent') == 'true') : ?>
                <div class="uk-alert-success" id="sa" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p><i class="far fa-check-circle"></i> <?= lang('notification_activation_email_resent_success'); ?></p>
                </div>
                <script>
                    setTimeout(function() {
                        UIkit.alert('#sa').close();
                    }, 3500);
                </script>
            <?php elseif ($this->session->flashdata('activation_email_resent') == 'false') : ?>
                <div class="uk-alert-danger" id="sa" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p><i class="far fa-times-circle"></i> <?= lang('notification_activation_email_resent_fail'); ?></p>
                </div>
            <?php endif; //let error message stay forever
    ?>
            <p>Hello <strong><?= ucfirst($this->wowauth->getUsernameID($this->session->userdata('wow_sess_id'))); ?></strong>,</p>
            <p>It seems that your account has not been activated yet.</p>
            <p>Please check your email (including spam/junk folder) for activation mail from <strong><?= $this->config->item('smtp_user'); ?></strong> and click the activation link. You will be able to gain full control after that.</p>
            <div class="uk-card-default myaccount-card uk-margin-small">
                <div class="uk-card-header">
                    <div class="uk-grid uk-grid-small">
                        <div class="uk-width-expand@m">
                            <h5 class="uk-h5 uk-text-uppercase uk-text-bold"><i class="fas fa-info-circle"></i> <?= $this->lang->line('panel_account_details'); ?></h5>
                        </div>
                    </div>
                </div>
                <?php
        $email = $this->wowauth->getEmailID($this->session->userdata('wow_sess_id'));
    $username = $this->wowauth->getUsernameID($this->session->userdata('wow_sess_id'));
    $db = $this->user_model->getActivationDetails($email);
    $dbEx = $this->user_model->getActivationDetails($email, true);
    ?>
                <div class="uk-card-body">
                    <div class="uk-overflow-auto uk-margin-small">
                        <table class="uk-table uk-table-small uk-table-responsive uk-table-hover">
                            <tbody>
                            <tr>
                                <td class="uk-width-medium"><span class="uk-h5 uk-text-bold"><?= $this->lang->line('placeholder_username'); ?></span></td>
                                <td class="uk-table-expand"><?= ucfirst($username); ?></td>
                            </tr>
                            <tr>
                                <td class="uk-width-medium"><span class="uk-h5 uk-text-bold"><?= $this->lang->line('placeholder_email'); ?></span></td>
                                <td class="uk-table-expand"><?= $email; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-width-medium"><span class="uk-h5 uk-text-bold"><?= $this->lang->line('panel_request_ip'); ?></span></td>
                                <?php if ($db->num_rows()) : ?>
                                    <td class="uk-table-expand"><?= inet_ntop($db->row('ip')); ?></td>
                                <?php else : ?>
                                    <td class="uk-table-expand"><?= inet_ntop($dbEx->row('ip')) ?: 'N/A'; ?></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="uk-width-medium"><span class="uk-h5 uk-text-bold">Token Status</span></td>
                                <?php if ($db->num_rows()) : ?>
                                    <td class="uk-table-expand"><strong style="color:#4caf50">Active</strong> - Sent at <?= date("m/d/Y H:i:s", $db->row('requested_at')); ?></td>
                                <?php else : ?>
                                    <td class="uk-table-expand"><strong style="color:#ff3636">Passive</strong> - Sent at <?= date("m/d/Y H:i:s", $dbEx->row('requested_at')); ?></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="uk-width-medium"><span class="uk-h5 uk-text-bold"><?= $this->lang->line('button_request'); ?></span></td>
                                <?php if ($db->num_rows() && $this->user_model->getActivationTries($email) > 0) : ?>
                                    <td class="uk-table-expand">You have an active token to activate your account, please check your email.<br> You can request new code request in <strong><?= secondsToTime($this->user_model->getActivationTries($email)); ?><strong></td>
                                <?php else : ?>
                                    <?= form_open(base_url($this->lang->lang() . '/resend'), '', ['email' => $email, 'username' => $username]); ?>
                                    <td class="uk-table-expand "><button class="uk-button uk-button-small uk-button-primary" type="submit"><i class="fas fa-user-edit"></i> Request Now</button></td>
                                    <?= form_close(); ?>
                                <?php endif; ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>