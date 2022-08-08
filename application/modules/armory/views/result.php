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
                        <?php if (empty($_GET['search'])) {
                            echo "\n<br/>There are no recent searches.";
                        } else {
                            echo "\n<br/>Recent search: <span class=\"system\">[" . $_GET['search'] . "]</span>";
                        } ?>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="uk-container">
        <div class="uk-grid uk-grid-medium uk-margin-small" data-uk-grid>
            <div class="uk-width-3-3@s">
                <article class="uk-article">
                    <div class="uk-card uk-card-default uk-card-body uk-margin-small">
                        <h3 class="uk-text-center">Search Results</h3>
                        <div class="uk-button-group uk-width-1-1">
                            <button id="PlayerInit"
                                    class="uk-button uk-button-default uk-width-1-2 uk-margin-small-right"
                                    style="display:inline;"
                                    onclick="opentab('Player')">Players
                            </button>
                            <button class="uk-button uk-button-default uk-width-1-2" style="display:inline;"
                                    onclick="opentab('Guilds')">Guilds
                            </button>
                        </div>
                        <div id="Player" class="tab uk-margin-small-top">
                            <?php
                                                    $currentRealm = $this->wowrealm->getRealmConnectionData($realm);
         $searchArr    = $this->armory_model->searchChar($currentRealm, $search);
         $tHeadOnce    = 0;
         if ($searchArr->num_rows() == 0): ?>
                                <div class="uk-alert-danger uk-margin-top" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <p><i class="fas fa-exclamation-triangle"></i> No player was found as a result of
                                        your search, please review your search.</p>
                                </div>
                            <?php endif;
         foreach ($searchArr->result() as $player):
             if ($tHeadOnce == 0): ?>
                            <div class="uk-overflow-auto uk-margin-small">
                                <table class="uk-table dark-table uk-table-divider uk-table-small uk-table-middle">
                                    <thead>
                                    <tr>
                                        <th class="uk-table-expand uk-text-center">Player</th>
                                        <th class="uk-table-expand uk-text-center">Level</th>
                                        <th class="uk-table-expand uk-text-center">Total Played Time</th>
                                        <th class="uk-table-expand uk-text-center">Faction</th>
                                        <th class="uk-table-expand uk-text-center">Race</th>
                                        <th class="uk-table-expand uk-text-center">Class</th>
                                    </tr>
                                    </thead>
                                    <?php endif; ?>
                                    <tbody>
                                    <tr class="pg-td">
                                        <td class="uk-table-expand uk-text-center "><a
                                                    href="<?= base_url() . 'armory/character/' . $realm . '/' ?><?= $player->guid ?>"><?= $player->name ?></a>
                                        </td>
                                        <td class="uk-table-expand uk-text-center"><?= $player->level ?></td>
                                        <td class="uk-table-expand uk-text-center"><?= secondsToTime($player->played_time_total) ?></td>
                                        <td class="uk-table-expand uk-text-center"><img align="center"
                                                                                        src="<?= base_url('assets/images/factions/' . $this->wowgeneral->getFactionIcon($player->race)); ?>"
                                                                                        width="40" height="40"
                                                                                        title="<?= $this->wowgeneral->getRaceName($player->race); ?>"
                                                                                        alt="<?= $this->wowgeneral->getRaceName($player->race); ?>">
                                        </td>
                                        <td class="uk-table-expand uk-text-center"><img align="center"
                                                                                        class="uk-border-circle"
                                                                                        src="<?= base_url() . 'application/modules/armory/assets/images/characters/' . getAvatar($player->class, $player->race, $player->gender, $player->level); ?>"
                                                                                        width="40" height="40"
                                                                                        title="<?= $this->wowgeneral->getRaceName($player->race); ?>"
                                                                                        alt="<?= $this->wowgeneral->getRaceName($player->race); ?>">
                                        </td>
                                        <td class="uk-table-expand uk-text-center"><img align="center"
                                                                                        src="<?= base_url('assets/images/class/' . $this->wowgeneral->getClassIcon($player->class)); ?>"
                                                                                        width="40" height="40"
                                                                                        title="<?= $this->wowgeneral->getClassName($player->class); ?>"
                                                                                        alt="<?= $this->wowgeneral->getClassName($player->class); ?>">
                                        </td>
                                    </tr>
                                    </tbody>
                                    <?php $tHeadOnce++;
         endforeach;
         if ($searchArr->num_rows() > 0): ?>
                                </table>
                            </div>
                        <?php endif; ?>
                        </div>
                        <div id="Guilds" class="tab uk-margin-small-top" style="display:none">
                            <?php
                            $searchArr = $this->armory_model->searchGuild($currentRealm, $search);
         if ($searchArr->num_rows() == 0): ?>
                                <div class="uk-alert-danger uk-margin-top" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <p><i class="fas fa-exclamation-triangle"></i> No guild was found as a result of
                                        your search, please review your search.</p>
                                </div>
                            <?php endif;
         $tHeadOnce = 0;
         foreach ($searchArr->result() as $guild):
             if ($tHeadOnce == 0): ?>
                            <div class="uk-overflow-auto uk-margin-small">
                                <table class="uk-table dark-table uk-table-divider uk-table-small">
                                    <thead>
                                    <tr class="pg-td">>
                                        <th class="uk-table-expand uk-text-center">Name</th>
                                        <th class="uk-table-expand uk-text-center">Guild Message</th>
                                    </tr>
                                    </thead>
                                    <?php endif; ?>
                                    <tbody>
                                    <tr>
                                        <td class="uk-table-expand uk-text-center"><a
                                                    href="<?= base_url() . 'armory/guild/' . $realm . '/' ?><?= $guild->guild_id ?>"><?= $guild->name ?></a>
                                        </td>
                                        <td class="uk-table-expand uk-text-center"><?= $guild->motd ?></td>
                                    </tr>
                                    </tbody>
                                    <?php $tHeadOnce++;
         endforeach;
         if ($searchArr->num_rows() > 0): ?>
                                </table>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
<script>
    document.getElementById('PlayerInit').focus();

    function opentab(tabname) {
        var i;
        var x = document.getElementsByClassName("tab");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        document.getElementById(tabname).style.display = "block";
    }
</script>
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