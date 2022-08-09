<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section"
         style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <h4 class="uk-h4 uk-text-uppercase uk-text-bold"><i class="fas fa-fist-raised"></i> <?= $this->lang->line('tab_pvp_statistics'); ?></h4>
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .pvp-statistics">
            <?php foreach ($realms as $charsMultiRealm): ?>
                <li><a href="#"><i class="fas fa-server"></i> <?= $this->wowrealm->getRealmName($charsMultiRealm->realmID); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul class="uk-switcher pvp-statistics uk-margin-small">
            <?php foreach ($realms as $charsMultiRealm):
                $multiRealm = $this->wowrealm->getRealmConnectionData($charsMultiRealm->id);
                ?>
                <li>
                    <div class="uk-margin">
                        <span class="uk-label uk-text-bold"><?= $this->lang->line('statistics_top_20'); ?></span>
                        <div class="uk-overflow-auto uk-margin-small">
                            <table class="uk-table dark-table uk-table-hover uk-table-divider uk-table-small">
                                <thead>
                                <tr>
                                    <th class="uk-preserve-width"><i class="fas fa-id-card"></i> <?= $this->lang->line('table_header_name'); ?></th>
                                    <th class="uk-preserve-width"><i class="fas fa-arrow-up-right-dots"></i> <?= $this->lang->line('table_header_level'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><i class="fas fa-people-group"></i> <?= $this->lang->line('table_header_race'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><i class="fas fa-hat-wizard"></i> <?= $this->lang->line('table_header_class'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><i class="fas fa-circle-half-stroke"></i> <?= $this->lang->line('table_header_faction'); ?></th>
                                    <th class="uk-table-expand uk-text-center"><i class="fas fa-book-skull"></i> <?= $this->lang->line('table_header_total_kills'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><i class="fas fa-skull"></i> <?= $this->lang->line('table_header_today_kills'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><i class="fas fa-skull-crossbones"></i> <?= $this->lang->line('table_header_yersterday_kills'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($this->pvp_model->getTop20PVP($multiRealm)->result()):
                                    foreach ($this->pvp_model->getTop20PVP($multiRealm)->result() as $tops): ?>
                                        <tr>
                                            <td class="uk-text-capitalize uk-text-middle"><a class="pvp-name" href="<?= base_url() . 'armory/character/' . $charsMultiRealm->id . '/' ?><?= $tops->guid ?>"><?= $tops->name ?></a></td>
                                            <td class="uk-text-capitalize uk-text-middle"><?= $tops->level ?></td>
                                            <td class="uk-text-center uk-text-middle"><img class="uk-border-rounded" src="<?= base_url() . 'application/modules/armory/assets/images/characters/' . getAvatar($tops->class, $tops->race, $tops->gender, $tops->level) ?>" width="32" height="32" title="<?= $this->wowgeneral->getRaceName($tops->race); ?>" alt="<?= $this->wowgeneral->getRaceName($tops->race); ?>"></td>
                                            <td class="uk-text-center uk-text-middle"><img class="uk-border-rounded" src="<?= base_url('assets/images/class/' . $this->wowgeneral->getClassIcon($tops->class)); ?>" width="32" height="32" title="<?= $this->wowgeneral->getClassName($tops->class); ?>" alt="<?= $this->wowgeneral->getClassName($tops->class); ?>"></td>
                                            <td class="uk-text-center uk-text-middle"><img class="uk-border-circle" src="<?= base_url('assets/images/factions/' . $this->wowgeneral->getFaction($tops->race) . '.png'); ?>" width="32" height="32" title="<?= $this->wowgeneral->getFaction($tops->race); ?>" alt="<?= $this->wowgeneral->getFaction($tops->race); ?>"></td>
                                            <td class="uk-text-center uk-text-middle pvp-total"><?= ($this->wowrealm->getCharHKs($tops->guid, $multiRealm) + $tops->honor_stored_hk) ?></td>
                                            <td class="uk-text-center uk-text-middle pvp-today"><?= $this->pvp_model->getKillsByDate($multiRealm, $tops->guid, (new DateTime())->diff(new DateTime('1970-01-01'))->format('%a')) ?></td>
                                            <td class="uk-text-center uk-text-middle pvp-yesterday"><?= $this->pvp_model->getKillsByDate($multiRealm, $tops->guid, (new DateTime())->diff(new DateTime('1970-01-01'))->format('%a') - 1) ?></td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <td class="uk-text-center" colspan="10">
                                        There are currently no PVP statistics available for this realm.
                                    </td>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<script>
    $('tr').click( function() {
        window.location = $(this).find('a').attr('href');
    }).hover( function() {
        $(this).toggleClass('hover');
    });
</script>