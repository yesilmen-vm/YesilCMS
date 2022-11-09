<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section"
         style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <h4 class="uk-h4 uk-text-uppercase uk-text-bold"><i class="fas fa-users"></i> <?= $this->lang->line('tab_online'); ?></h4>
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .onlineplayers">
            <?php foreach ($realms as $realm): ?>
                <li><a href="#"><i class="fas fa-server"></i> <?= $this->wowrealm->getRealmName($realm->realmID); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul class="uk-switcher onlineplayers uk-margin-small">
            <?php foreach ($realms as $charsMultiRealm):
                $multiRealm = $this->wowrealm->getRealmConnectionData($charsMultiRealm->id);
                ?>
                <li>
                    <div class="uk-overflow-auto uk-margin-small">
                        <table class="uk-table dark-table uk-table-hover uk-table-divider uk-table-small">
                            <thead>
                            <tr>
                                <th class="uk-table-expand"><?= $this->lang->line('table_header_name'); ?></th>
                                <th class="uk-table-expand uk-text-center"><?= $this->lang->line('table_header_level'); ?></th>
                                <th class="uk-table-expand uk-text-center"></i> <?= $this->lang->line('table_header_faction'); ?></th>
                                <th class="uk-table-expand uk-text-center"><?= $this->lang->line('table_header_race'); ?></th>
                                <th class="uk-table-expand uk-text-center"><?= $this->lang->line('table_header_class'); ?></th>
                                <th class="uk-table-expand uk-text-center"><?= $this->lang->line('table_header_zone'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $result = $this->online_model->getOnlinePlayers($multiRealm)->result();
                            if ($result):
                                foreach ($result as $online):
                                    $faction = $this->wowgeneral->getFaction($online->race); ?>
                                    <tr>
                                        <td class="uk-text-capitalize uk-text-middle"><a class="pvp-name-<?= $faction ?>" href="<?= base_url() . 'armory/character/' . $charsMultiRealm->id . '/' ?><?= $online->guid ?>"><?= $online->name ?></td>
                                        <td class="uk-text-center uk-text-middle"><?= $online->level ?></td>
                                        <td class="uk-text-center uk-text-middle"><img class="uk-border-rounded" src="<?= base_url('assets/images/factions/' . $this->wowgeneral->getFactionIcon($online->race)); ?>" width="32" height="32" title="<?= $this->wowgeneral->getFaction($online->race); ?>"
                                                                                       alt="<?= $this->wowgeneral->getFaction($online->race); ?>"></td>
                                        <td class="uk-text-center uk-text-middle"><img class="uk-border-rounded" src="<?= base_url() . 'application/modules/armory/assets/images/characters/' . getAvatar($online->class, $online->race, $online->gender, $online->level) ?>" width="32" height="32"
                                                                                       title="<?= $this->wowgeneral->getRaceName($online->race); ?>" alt="<?= $this->wowgeneral->getRaceName($online->race); ?>"></td>
                                        <td class="uk-text-center uk-text-middle"><img class="uk-border-rounded" src="<?= base_url('assets/images/class/' . $this->wowgeneral->getClassIcon($online->class)); ?>" width="32" height="32" title="<?= $this->wowgeneral->getClassName($online->class); ?>"
                                                                                       alt="<?= $this->wowgeneral->getClassName($online->class); ?>"></td>
                                        <td class="uk-text-center uk-text-middle"><?= $this->wowgeneral->getSpecifyZone($online->zone); ?></td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <td class="uk-text-center" colspan="10">
                                    There are currently no players online.
                                </td>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<script>
    $('tr').click(function () {
        window.location = $(this).find('a').attr('href');
    }).hover(function () {
        $(this).toggleClass('hover');
    });
</script>