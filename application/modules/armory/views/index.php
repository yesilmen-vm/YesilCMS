<?php

$currentRealm  = $this->wowrealm->getRealmConnectionData($realmid);
$currentPlayer = $this->armory_model->getPlayerInfo($currentRealm, $id);
// Show 404 If realm or player doesn't exists
if (! $currentRealm) {
    redirect(base_url('404'), 'refresh');
}
if (! $currentPlayer->row()) {
    redirect(base_url('404'), 'refresh');
}
?>
<link rel="stylesheet" href="<?= base_url() . 'application/modules/armory/assets/css/armory.css'; ?>"/>
<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section bg-<?= strtolower($this->wowgeneral->getFaction($this->wowrealm->getCharRace($id, $currentRealm))) ?>" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <article class="uk-article">
            <div class="uk-card uk-card-body uk-margin-small">
                <?php
                foreach ($currentPlayer->result() as $info) : ?>
                    <div class="uk-overflow-auto uk-margin-small-top uk-flex uk-flex-center">
                        <div class="cp-header padding-bottom-small">
                            <div class="cp-header-character">
                                <div class="cp-header-logo-area">
                                    <div class="cp-logo cp-logo-smaller cp-logo-<?= $this->wowgeneral->getFaction($info->race) ?> cp-header-logo"></div>
                                </div>
                                <div class="cp-header-name-area">
                                    <div class="cp-header-name-title">
                                        <a class="cp-header-name color-c<?= $info->class ?>" href="<?= $_SERVER['REQUEST_URI'] ?>"><?= $info->name ?></a>
                                        <div class="cp-header-title cp-header-suffix">
                                            <?php $guildId = $this->armory_model->getGuildInfoByPlayerID($currentRealm, $info->guid)->guild_id ?? '';
                                            if (! empty($guildId)) : ?>
                                            <a class="cs-guild " href="<?= base_url() . 'armory/guild/' . $realmid . '/' . $guildId ?>">
                                                <?php endif; ?>
                                                &#10094; <?= $this->armory_model->getGuildInfoByPlayerID($currentRealm, $info->guid)->name ?? '<i>Guildless</i>' ?>
                                                &#10095;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cp-header-info">
                                <div class="cp-header-details">
                                    <div class="cp-header-detail">
                                        <img class="cp-header-image uk-visible@m uk-border-rounded" src="<?= base_url() . 'application/modules/armory/assets/images/characters/' . getAvatar($info->class, $info->race, $info->gender, $info->level) ?>"
                                             title="<?= $this->wowgeneral->getRaceName($info->race); ?>" alt="<?= $this->wowgeneral->getRaceName($info->race); ?>">
                                        Level
                                        <b><?= $info->level ?></b>, <?= $this->wowgeneral->getRaceName($info->race); ?> <?= $this->wowgeneral->getClassName($info->class); ?>
                                        <br/>
                                        <?= $this->wowrealm->getRealmName($realmid) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $equippedItems = $this->armory_model->getCharEquipments($currentRealm, $id);
                    $slots               = [
                        'L' => ['head', 'neck', 'shoulders', 'back', 'chest', 'shirt', 'tabard', 'wrists'],
                        'R' => ['hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2'],
                        'B' => ['mainhand', 'offhand', 'ranged']
                    ];
                    $equippedItemIDs     = []; ?>
                    <div class="uk-overflow-auto cp-padding">
                        <div class="cp-main cp-bg" style="background-image: url('<?= base_url() . 'application/modules/armory/assets/images/bgs/' . $info->race ?>.png')">
                            <img id="placeholder" class="uk-align-center img-placeholder" style="--uk-position-margin-offset: 0px;" src="<?= base_url() . 'application/modules/armory/assets/images/placeholders/' . getPlaceholder($info->class, $info->race, $info->gender, $info->level) ?>">
                            <div class="uk-flex uk-flex-center">
                                <div class="model-area uk-width-expand" id="model3D"></div>
                                <div class="uk-position-bottom-center" style="--uk-position-margin-offset: 0px" uk-margin>
                                    <!-- style for ngx pagespeed fix -->
                                    <button id='show3DModelFast' class="uk-button uk-button-default uk-button-small model-btn model-btn-sm model-btn-<?= strtolower($this->wowgeneral->getFaction($info->race)) ?> uk-width-auto">3D Model (Fast)</button>
                                    <br class="uk-hidden@m"/>
                                    <button id='show3DModelDetailed' class="uk-button uk-button-default uk-button-small model-btn model-btn-<?= strtolower($this->wowgeneral->getFaction($info->race)) ?> uk-width-auto">3D Model (Detailed)</button>
                                    <div class="animation-dropdown">
                                        <span class="model-buttonspan">
                                            <button id='playAnim' class="fa-solid fa-play" style="display: none;"></button>
                                            <button id='pauseAnim' onclick="model.setAnimPaused(true)" class="fa-solid fa-pause"></button>
                                            <button id='fullScreen' onclick="model.setFullscreen(true)" class="fa-solid fa-expand uk-margin-small-left"></button>
                                        </span>
                                        <select id="mySelect" class="uk-select uk-form-small model-list model-btn-<?= strtolower($this->wowgeneral->getFaction($info->race)) ?>" onchange="model.setAnimation(this.value)">
                                            <option value="" selected disabled hidden>Choose animation</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="cp-gear cp-gear-left">
                                <?php foreach ($slots['L'] as $slot) : ?>
                                    <div class="cp-item">
                                        <?php if (isset($equippedItems->$slot)) :
                                            array_push($equippedItemIDs, $equippedItems->$slot); ?>
                                            <a href="https://classicdb.ch/?item=<?= $equippedItems->$slot ?>" role="button" target="_blank" class="ControlledModalToggle">
                                                <div class="cp-itemSlot cp-item-icon cp-gameicon-slot cp-gameicon cp-gameicon-<?= $this->armory_model->getCharEquipmentQuality($equippedItems->$slot) ?>">
                                                    <div class="cp-gameicon-icon" style="background-image: url('<?= $this->armory_model->getCharEquipDisplayIcon($equippedItems->$slot) ?>');"></div>
                                                </div>
                                            </a>
                                        <?php else : ?>
                                            <div class="cp-itemSlot cp-gameicon-slot cp-gameicon-slot<?= ucfirst($slot) ?> cp-gameicon">
                                                <div class="cp-gameicon-icon"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="cp-gear cp-gear-right">
                                <?php foreach ($slots['R'] as $slot) : ?>
                                    <div class="cp-item">
                                        <?php if (isset($equippedItems->$slot)) :
                                            array_push($equippedItemIDs, $equippedItems->$slot); ?>
                                            <a href="https://classicdb.ch/?item=<?= $equippedItems->$slot ?>" role="button" target="_blank" class="ControlledModalToggle">
                                                <div class="cp-itemSlot cp-item-icon cp-gameicon-slot cp-gameicon cp-gameicon-<?= $this->armory_model->getCharEquipmentQuality($equippedItems->$slot) ?>">
                                                    <div class="cp-gameicon-icon" style="background-image: url('<?= $this->armory_model->getCharEquipDisplayIcon($equippedItems->$slot) ?>');"></div>
                                                </div>
                                            </a>
                                        <?php else : ?>
                                            <div class="cp-itemSlot cp-gameicon-slot cp-gameicon-slot<?= ucfirst($slot) ?> cp-gameicon">
                                                <div class="cp-gameicon-icon"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="cp-gear cp-gear-bottom">
                                <?php foreach ($slots['B'] as $slot) : ?>
                                    <div class="cp-item">
                                        <?php if (isset($equippedItems->$slot)) :
                                            array_push($equippedItemIDs, $equippedItems->$slot); ?>
                                            <a href="https://classicdb.ch/?item=<?= $equippedItems->$slot ?>" role="button" target="_blank" class="ControlledModalToggle">
                                                <div class="cp-itemSlot cp-item-icon cp-gameicon-slot cp-gameicon cp-gameicon-<?= $this->armory_model->getCharEquipmentQuality($equippedItems->$slot) ?>">
                                                    <div class="cp-gameicon-icon" style="background-image: url('<?= $this->armory_model->getCharEquipDisplayIcon($equippedItems->$slot) ?>');"></div>
                                                </div>
                                            </a>
                                        <?php else : ?>
                                            <div class="cp-itemSlot cp-gameicon-slot cp-gameicon-slot<?= ucfirst($slot) ?> cp-gameicon">
                                                <div class="cp-gameicon-icon"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <h3>Base Stats</h3>
                    <hr>
                    <?php $charStats = $this->armory_model->calculateAuras($currentRealm, $id, $info->race, $info->class, $info->level, $equippedItemIDs); ?>
                    <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-4@m uk-margin-remove-left" uk-grid>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/primary_health.svg">
                                </div>
                                <div class="ability-med-text cs-primary_health">
                                    <span><?= formatStats($charStats['maxHealth'] ?? $info->health) ?></span>
                                    <div class="cs-name">Health</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <?php [$gPowerIcon, $gPowerText] = getPowerDetails($info->class); ?>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/secondary_<?= $gPowerIcon ?>.svg">
                                </div>
                                <div class="ability-med-text cs-secondary_<?= $gPowerIcon ?>">
                                    <span><?= formatStats($charStats['maxPower'] ?? $info->power1) ?></span>
                                    <div class="cs-name "><?= $gPowerText ?></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <?php [$mPowerIcon, $mPowerText, $mPowerVal] = detectMainStat($info->class, $charStats['statSta'] ?? 0, $charStats['statStr'] ?? 0, $charStats['statAgi'] ?? 0, $charStats['statInt'] ?? 0, $charStats['statSpr'] ?? 0, $charStats['defDefense'] ?? 0); ?>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/<?= $mPowerIcon ?>.svg">
                                </div>
                                <div class="ability-med-text cs-<?= $mPowerIcon ?>"><span><?= $mPowerVal ?></span>
                                    <div class="cs-name"><?= $mPowerText ?></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/stat_stamina.svg">
                                </div>
                                <div class="ability-med-text cs-stat_stamina">
                                    <span><?= $charStats['statSta'] ?? 'Unknown' ?></span>
                                    <div class="cs-name">Stamina</div>
                                </div>
                            </div>
                        </div><?php $classSection = guessMainSpec($info->class, $charStats);
                        foreach ($classSection as $classStats): ?>
                            <div>
                                <div class="ability-med">
                                    <div class="ability-med-image">
                                        <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/<?= $classStats['icon'] ?>.svg">
                                    </div>
                                    <div class="ability-med-text cs-<?= $classStats['icon'] ?>">
                                        <span><?= $classStats['data'] ?></span>
                                        <div class="cs-name"><?= $classStats['name'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <h3>Primary Professions</h3>
                    <hr>
                    <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-2@m uk-margin-remove-left" uk-grid>
                        <?php $primaryProfessions = $this->armory_model->getCharProfessions($currentRealm, $id, 'P');
                        foreach ($primaryProfessions as $profession) : ?>
                            <div>
                                <div class="progressbar">
                                    <div class="progressbar-border"></div>
                                    <div class="progressbar-progress" style="width:<?= percentageOf(
                                        $profession->value,
                                        $profession->max
                                    ) ?>%"></div>
                                    <div class="progressbar-content">
                                        <span class="profession-logo" style="background-image: url('<?= base_url() ?>/application/modules/armory/assets/images/icons/<?= $profession->icon ?>.png')">&nbsp;</span> <?= $profession->name ?>
                                        <span style="float:right"><?= $profession->value . ' / ' . $profession->max ?></span>
                                        <div class="progressbar-body"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                        if (count($primaryProfessions) < 2) :
                            for ($x = 0; $x < (2 - count($primaryProfessions)); $x++) : ?>
                                <div>
                                    <div class="progressbar">
                                        <div class="progressbar-border"></div>
                                        <div class="progressbar-content none">
                                            <div class="progressbar-body">No Profession</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor;
                        endif; ?>
                    </div>
                    <h3>Secondary Professions</h3>
                    <hr>
                    <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-2@m uk-margin-remove-left" uk-grid>
                        <?php $secondaryProfessions = $this->armory_model->getCharProfessions($currentRealm, $id, 'S');
                        foreach ($secondaryProfessions as $profession) : ?>
                            <div>
                                <div class="progressbar">
                                    <div class="progressbar-border"></div>
                                    <div class="progressbar-progress" style="width:<?= percentageOf(
                                        $profession->value,
                                        $profession->max
                                    ) ?>%"></div>
                                    <div class="progressbar-content">
                                        <span class="profession-logo" style="background-image: url('<?= base_url() ?>/application/modules/armory/assets/images/icons/<?= $profession->icon ?>.png')">&nbsp;</span> <?= $profession->name ?>
                                        <span style="float:right"><?= $profession->value . ' / ' . $profession->max ?></span>
                                        <div class="progressbar-body"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                        if (count($secondaryProfessions) == 0) : ?>
                            <div>
                                <h4><?= $info->name ?> hasn't learned any secondary profession yet.</h4>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3>PvP Stats</h3>
                    <hr>
                    <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-4@m uk-margin-remove-left" uk-grid>
                        <?php $honorInfo = $this->armory_model->getCurrentPVPRank($currentRealm, $id, 'S'); ?>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/PvP_TotalHK.svg">
                                </div>
                                <div class="ability-med-text">
                                    <span><?= formatStats($this->armory_model->getTotalHK($currentRealm, $id)) ?></span>
                                    <div class="cs-name cs-HEALTH">HONORABLE KILLS</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/<?= $honorInfo->icon ?>.png">
                                </div>
                                <div class="ability-med-text">
                                    <span><?= $this->wowgeneral->getFaction($info->race) == 'Alliance' ? $honorInfo->a_title : $honorInfo->h_title ?> <small>#R<?= $honorInfo->rank ?></small></span>
                                    <div class="cs-name cs-HEALTH">TITLE / <small>#RANK</small></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/PvP_HonorPoints.svg">
                                </div>
                                <div class="ability-med-text">
                                    <span><?= formatStats($info->honor_last_week_cp) ?></span>
                                    <div class="cs-name cs-HEALTH">HONOR <small>(Last Week)</small></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/PvP_Standing.svg">
                                </div>
                                <div class="ability-med-text">
                                    <span><?= $info->honor_standing == 0 ? 'N/A' : $info->honor_standing ?></span>
                                    <div class="cs-name cs-HEALTH">STANDING <small>(Last Week)</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </div>
</section>
<script type="module" src="<?= base_url() . 'application/modules/armory/assets/js/wmv3d.js'; ?>"></script>
<script>
    const baseURL = "<?= base_url(); ?>";

    const character = {
        "race": <?= $info->race ?>,
        "gender": <?= $info->gender ?>,
        "skin": <?= $info->skin ?>,
        "face": <?= $info->face ?>,
        "hairStyle": <?= $info->hair_style ?>,
        "hairColor": <?= $info->hair_color ?>,
        "facialStyle": <?= $info->facial_hair ?>,
        "items": <?= count($equippedItemIDs) === 0 ? '[]' . PHP_EOL : json_encode(array_map(function ($i) {return array_values(get_object_vars($i));}, $this->armory_model->getCharEquipDisplayModel($id, $equippedItemIDs, $info->class))) . PHP_EOL ?>
        <?php /*for php>=7.4 */ //"items": <?= count($equippedItemIDs) === 0 ? '[]' . PHP_EOL : json_encode(array_map(fn ($i) => array_values(get_object_vars($i)), $this->armory_model->getCharEquipDisplayModel($equippedItemIDs, $info->class))) . PHP_EOL?>
    };

    const equipments = <?= count($equippedItemIDs) === 0 ? '[]' . PHP_EOL : json_encode($this->armory_model->getCharEquipDisplayModel($id, $equippedItemIDs, $info->class, true)) ?>;
</script>
<script type="text/javascript" src="https://wow.zamimg.com/modelviewer/live/viewer/viewer.min.js"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/armory/assets/js/power.js'; ?>"></script>