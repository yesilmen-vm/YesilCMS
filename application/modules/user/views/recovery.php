<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <h4 class="uk-h4 uk-heading-line uk-text-uppercase uk-text-bold"><span><i class="fas fa-user-cog"></i> <?= $this->lang->line('tab_reset'); ?></span></h4>
        <?= form_open('', 'id="recoveryForm" onsubmit="RecoveryForm(event)"'); ?>
        <div class="uk-margin-small uk-light">
            <div class="uk-form-controls">
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon"><i class="fas fa-user fa-lg"></i></span>
                    <input class="uk-input" name="username" type="text" id="recovery_username" placeholder="<?= $this->lang->line('placeholder_username'); ?>" required>
                </div>
            </div>
        </div>
        <div class="uk-margin uk-light">
            <div class="uk-form-controls">
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon"><i class="fas fa-envelope fa-lg"></i></span>
                    <input class="uk-input" name="email" type="email" id="recovery_email" placeholder="<?= $this->lang->line('placeholder_email'); ?>" required>
                </div>
            </div>
        </div>
        <div class="uk-grid uk-grid-small uk-grid-margin-small" data-uk-grid>
            <div class="uk-width-1-4@m">
                <?php if ($this->wowmodule->getreCaptchaStatus() == '1') : ?>
                    <div class="uk-margin-small">
                        <div class="g-recaptcha" data-sitekey="<?= $recapKey; ?>"></div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="uk-width-1-2@m"></div>
            <div class="uk-width-1-4@m">
                <button class="uk-button uk-button-default uk-width-1-1" id="button_recovery" type="submit"><i class="fas fa-paper-plane"></i> <?= $this->lang->line('button_send'); ?></button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</section>
<?= $this->wowmodule->getreCaptchaStatus() == '1' ? '<script src="https://www.google.com/recaptcha/api.js" async defer></script>' : '' ?>
<script>
    var csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash() ?>";

    function RecoveryForm(e) {
        e.preventDefault();

        var restatus = "<?= $this->wowmodule->getreCaptchaStatus(); ?>";
        if (restatus) {
            var ren = grecaptcha.getResponse();
            if (ren.length == 0) {
                $.amaran({
                    'theme': 'awesome error',
                    'content': {
                        title: '<?= $this->lang->line('notification_title_error'); ?>',
                        message: '<?= $this->lang->line('notification_captcha_error'); ?>',
                        info: '',
                        icon: 'fas fa-shield-alt'
                    },
                    'delay': 5000,
                    'position': 'top right',
                    'inEffect': 'slideRight',
                    'outEffect': 'slideRight'
                });
                return false;
            }
        }

        var username = $('#recovery_username').val();
        var email = $('#recovery_email').val();
        var recaptcha = grecaptcha.getResponse();
        if (username == '') {
            $.amaran({
                'theme': 'awesome error',
                'content': {
                    title: '<?= $this->lang->line('notification_title_error'); ?>',
                    message: '<?= $this->lang->line('notification_username_empty'); ?>',
                    info: '',
                    icon: 'fas fa-times-circle'
                },
                'delay': 5000,
                'position': 'top right',
                'inEffect': 'slideRight',
                'outEffect': 'slideRight'
            });
            return false;
        }
        if (email == '') {
            $.amaran({
                'theme': 'awesome error',
                'content': {
                    title: '<?= $this->lang->line('notification_title_error'); ?>',
                    message: '<?= $this->lang->line('notification_email_empty'); ?>',
                    info: '',
                    icon: 'fas fa-times-circle'
                },
                'delay': 5000,
                'position': 'top right',
                'inEffect': 'slideRight',
                'outEffect': 'slideRight'
            });
            return false;
        }
        $.ajax({
            url: "<?= base_url($lang . '/forgotpassword'); ?>",
            method: "POST",
            data: {
                username,
                email,
                recaptcha,
                [globalThis.csrfName]: globalThis.csrfHash
            },
            dataType: "text",
            beforeSend: function () {
                $.amaran({
                    'theme': 'awesome info',
                    'content': {
                        title: '<?= $this->lang->line('notification_title_info'); ?>',
                        message: '<?= $this->lang->line('notification_checking'); ?>',
                        info: '',
                        icon: 'fas fa-sign-in-alt'
                    },
                    'delay': 5000,
                    'position': 'top right',
                    'inEffect': 'slideRight',
                    'outEffect': 'slideRight'
                });
            },
            success: function (response) {

                if (response == 'emailOrUserErrP1') {
                    $.amaran({
                        'theme': 'awesome error',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_error'); ?>',
                            message: '<?= $this->lang->line('notification_check_email'); ?>',
                            info: '',
                            icon: 'fas fa-times-circle'
                        },
                        'delay': 5000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                    $('#recoveryForm')[0].reset();
                    setTimeout(function () {
                        location.reload();
                    }, 2500);
                    return false;
                }

                if (response == 'emailOrUserErrP2') {
                    $.amaran({
                        'theme': 'awesome error',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_error'); ?>',
                            message: '<?= $this->lang->line('notification_check_email'); ?>',
                            info: '',
                            icon: 'fas fa-times-circle'
                        },
                        'delay': 5000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                    $('#recoveryForm')[0].reset();
                    setTimeout(function () {
                        location.reload();
                    }, 2500);
                    return false;
                }

                if (response == 'captchaErr') {
                    $.amaran({
                        'theme': 'awesome error',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_error'); ?>',
                            message: '<?= $this->lang->line('notification_recaptcha_error'); ?>',
                            info: '',
                            icon: 'fas fa-times-circle'
                        },
                        'delay': 5000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                    $('#recoveryForm')[0].reset();
                    setTimeout(function () {
                        location.reload();
                    }, 2500);
                    return false;
                }

                if (response) {
                    $.amaran({
                        'theme': 'awesome ok',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_success'); ?>',
                            message: '<?= $this->lang->line('notification_email_sent'); ?>',
                            info: '',
                            icon: 'fas fa-envelope'
                        },
                        'delay': 5000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                }
                $('#recoveryForm')[0].reset();
                window.location.replace("<?= base_url('login'); ?>");
            }
        });
    }
</script>