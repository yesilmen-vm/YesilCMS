<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section"
         style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <div class="uk-grid uk-grid-medium uk-margin-small" data-uk-grid>
            <div class="uk-width-3-3@s">
                <article class="uk-article">
                    <div class="uk-card uk-card-default uk-card-body uk-margin-small">
                        <?= form_open('armory/result', array('id' => "searcharmoryForm", 'method' => "get")); ?>
                        <div class="uk-margin">
                            <div class="uk-form-controls uk-light">
                                <div class="uk-inline uk-width-1-1">
                                    <h2 class="uk-text-center">Armory Search</h2>
                                    <table class="uk-table uk-table-small uk-table-responsive">
                                        <tr>
                                            <td><input class="uk-input" style="display:inline;" id="search"
                                                       name="search" type="text" minlength="2"
                                                       placeholder="Search by Player Name or Guild Name" required></td>
                                            <td><select class="uk-inline uk-input minimal" style="display:inline;"
                                                        id="realm"
                                                        name="realm">
                                                    <?php foreach ($realms as $realmInfo): ?>
                                                        <option value="<?= $realmInfo->realmID ?>"><?= $this->wowrealm->getRealmName($realmInfo->realmID); ?></option>
                                                    <?php endforeach; ?>
                                                </select></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input class="uk-button uk-button-default uk-width-1-1" type="submit" value="search">
                        <?= form_close(); ?>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
<script>
    function SearchArmoryForm(e) {
        e.preventDefault();

        var search = $('#search').val();
        var realm = $('#realm').val();
        if (search == '') {
            $.amaran({
                'theme': 'awesome error',
                'content': {
                    title: '<?= $this->lang->line('notification_title_error'); ?>',
                    message: '<?= $this->lang->line('notification_title_empty'); ?>',
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
            url: "<?= base_url($lang . '/armory/result'); ?>",
            method: "GET",
            data: {search, realm},
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
                if (!response)
                    alert(response);

                if (response) {
                    $.amaran({
                        'theme': 'awesome ok',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_success'); ?>',
                            message: 'Search performed..',
                            info: '',
                            icon: 'fas fa-check-circle'
                        },
                        'delay': 5000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                }
                $('#searcharmoryForm')[0].reset();
                window.location.replace("<?= base_url('armory/result'); ?>");
            }
        });
    }
</script>