<?php
/**
 * @var array   $character
 * @var integer $realmid
 * @var integer $id
 * @var integer $patch
 * @var string  $lang
 * @var string  $currentRealmName
 * @var array   $slots
 */

?>
<link rel="stylesheet" href="<?= base_url() . 'application/modules/armory/assets/css/armory.css'; ?>"/>
<link rel="stylesheet" href="<?= base_url() . 'application/modules/database/assets/css/database.css'; ?>"/>
<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section bg-<?= strtolower($character['faction']) ?>" data-uk-height-viewport="expand: true">
    <div id="tooltip" class="tooltip"></div>
    <div class="uk-container">
        <article class="uk-article">
            <div class="uk-card uk-card-body uk-margin-small">
                <div class="uk-overflow-auto uk-margin-small-top uk-flex uk-flex-center">
                    <div class="cp-header padding-bottom-small">
                        <div class="cp-header-character">
                            <div class="cp-header-logo-area">
                                <div class="cp-logo cp-logo-smaller cp-logo-<?= $character['faction'] ?> cp-header-logo"></div>
                            </div>
                            <div class="cp-header-name-area">
                                <div class="cp-header-name-title">
                                    <a class="cp-header-name color-c<?= $character['class'] ?>" href="<?= $_SERVER['REQUEST_URI'] ?>"><?= $character['name'] ?></a>
                                    <div class="cp-header-title cp-header-suffix">
                                        <?php if (! empty($character['guild_id'])) : ?>
                                        <a class="cs-guild " href="<?= base_url() . 'armory/guild/' . $realmid . '/' . $character['guild_id'] ?>">
                                            <?php endif; ?>
                                            &#10094; <?= $character['guild_name'] ?>
                                            &#10095;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cp-header-info">
                            <div class="cp-header-details">
                                <div class="cp-header-detail">
                                    <img class="cp-header-image uk-visible@m uk-border-rounded" src="<?= base_url() . 'application/modules/armory/assets/images/characters/' . getAvatar($character['class'], $character['race'], $character['gender'], $character['level']) ?>"
                                         title="<?= $character['race_name'] ?>" alt="<?= $character['race_name'] ?>">
                                    Level
                                    <b><?= $character['level'] ?></b>, <?= $character['race_name'] ?> <?= $character['class_name'] ?>
                                    <br/>
                                    <?= $currentRealmName ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-overflow-auto cp-padding">
                    <div class="cp-main cp-bg" style="background-image: url('<?= base_url() . 'application/modules/armory/assets/images/bgs/' . $character['race'] ?>.png')">
                        <img id="placeholder" class="uk-align-center img-placeholder" style="--uk-position-margin-offset: 0px;" alt="Armory Background"
                             src="<?= base_url() . 'application/modules/armory/assets/images/placeholders/' . getPlaceholder($character['class'], $character['race'], $character['gender'], $character['level']) ?>">
                        <div class="uk-flex uk-flex-center">
                            <div class="model-area uk-width-expand" id="model3D"></div>
                            <div class="uk-position-bottom-center" style="--uk-position-margin-offset: 0px" uk-margin>
                                <!-- style for ngx pagespeed fix -->
                                <button id='show3DModelFast' class="uk-button uk-button-default uk-button-small model-btn-sm model-btn-<?= strtolower($character['faction']) ?> uk-width-auto">3D Model (Fast)</button>
                                <br class="uk-hidden@m"/>
                                <button id='show3DModelDetailed' class="uk-button uk-button-default uk-button-small model-btn-<?= strtolower($character['faction']) ?> uk-width-auto">3D Model (Detailed)</button>
                                <div class="animation-dropdown">
                                        <span class="model-buttonspan">
                                            <button id='playAnim' class="fa-solid fa-play" style="display: none;"></button>
                                            <button id='pauseAnim' onclick="model.setAnimPaused(true)" class="fa-solid fa-pause"></button>
                                            <button id='fullScreen' onclick="model.setFullscreen(true)" class="fa-solid fa-expand uk-margin-small-left"></button>
                                        </span>
                                    <select id="animationSelect" class="uk-select uk-form-small model-btn-<?= strtolower($character['faction']) ?>" onchange="model.setAnimation(this.value)">
                                        <option value="" selected disabled hidden>Choose animation</option>
                                    </select>
                                </div>
                                <div>
                                    <select name="patch" id="patch" class="uk-select uk-form-small patch-select model-btn-<?= strtolower($character['faction']) ?>" onchange="this.form.submit()">
                                        <option value="" selected disabled hidden><?= (! empty($patch) || strlen($patch) > 0) ? 'Current Patch: ' . getPatchName($patch) : 'Choose Patch (Default: 1.12)' ?></option>
                                        <option value="0">1.2</option>
                                        <option value="1">1.3</option>
                                        <option value="2">1.4</option>
                                        <option value="3">1.5</option>
                                        <option value="4">1.6</option>
                                        <option value="5">1.7</option>
                                        <option value="6">1.8</option>
                                        <option value="7">1.9</option>
                                        <option value="8">1.10</option>
                                        <option value="9">1.11</option>
                                        <option value="10">1.12</option>
                                    </select>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        $('#patch').change(function () {
                                            window.location.href = '<?= base_url($lang . '/armory/character/' . $realmid . '/' . $id); ?>' + '/' + encodeURIComponent($(this).val());
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="cp-gear cp-gear-left">
                            <?php foreach ($slots['L'] as $slot) : ?>
                                <div class="cp-item">
                                    <?php if (isset($character['equipped_items'][$slot])) : ?>
                                        <a <?= showTooltip($character['equipped_items'][$slot]['item_id'], ($character['equipped_items'][$slot]['item_patch'] ?? 99), $patch, $lang) ?> data-item='item=<?= $character['equipped_items'][$slot]['item_id'] ?>' data-patch='<?= dataPatch($patch, $lang) ?>'
                                                                                                                                                                                        data-item-slot='<?= $character['equipped_items'][$slot]['item_slot_id'] ?>' data-realm='1' role="button"
                                                                                                                                                                                        target="_blank"
                                                                                                                                                                                        class="ControlledModalToggle">
                                            <div class="cp-itemSlot cp-item-icon cp-gameicon-slot cp-gameicon cp-gameicon-<?= $character['equipped_items'][$slot]['item_quality'] ?>">
                                                <div class="cp-gameicon-icon" style="background-image: url('<?= $character['equipped_items'][$slot]['item_icon'] ?>');"></div>
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
                                    <?php if (isset($character['equipped_items'][$slot])) : ?>
                                        <a <?= showTooltip($character['equipped_items'][$slot]['item_id'], ($character['equipped_items'][$slot]['item_patch'] ?? 99), $patch, $lang) ?> data-item='item=<?= $character['equipped_items'][$slot]['item_id'] ?>' data-patch='<?= dataPatch($patch, $lang) ?>'
                                                                                                                                                                                        data-item-slot='<?= $character['equipped_items'][$slot]['item_slot_id'] ?>' data-realm='1' role="button"
                                                                                                                                                                                        target="_blank"
                                                                                                                                                                                        class="ControlledModalToggle">
                                            <div class="cp-itemSlot cp-item-icon cp-gameicon-slot cp-gameicon cp-gameicon-<?= $character['equipped_items'][$slot]['item_quality'] ?>">
                                                <div class="cp-gameicon-icon" style="background-image: url('<?= $character['equipped_items'][$slot]['item_icon'] ?>');"></div>
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
                                    <?php if (isset($character['equipped_items'][$slot])) : ?>
                                        <a <?= showTooltip($character['equipped_items'][$slot]['item_id'], ($character['equipped_items'][$slot]['item_patch'] ?? 99), $patch, $lang) ?> data-item='item=<?= $character['equipped_items'][$slot]['item_id'] ?>' data-patch='<?= dataPatch($patch, $lang) ?>'
                                                                                                                                                                                        data-item-slot='<?= $character['equipped_items'][$slot]['item_slot_id'] ?>' data-realm='1' role="button"
                                                                                                                                                                                        target="_blank"
                                                                                                                                                                                        class="ControlledModalToggle">
                                            <div class="cp-itemSlot cp-item-icon cp-gameicon-slot cp-gameicon cp-gameicon-<?= $character['equipped_items'][$slot]['item_quality'] ?>">
                                                <div class="cp-gameicon-icon" style="background-image: url('<?= $character['equipped_items'][$slot]['item_icon'] ?>');"></div>
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
                <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-4@m uk-margin-remove-left" uk-grid>
                    <div>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/primary_health.svg" alt="Health">
                            </div>
                            <div class="ability-med-text cs-primary_health">
                                <span><?= formatStats($character['stats']['maxHealth'] ?? $character['health']) ?></span>
                                <div class="cs-name">Health</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?php [$gPowerIcon, $gPowerText] = getPowerDetails($character['class']); ?>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/secondary_<?= $gPowerIcon ?>.svg" alt="<?= $gPowerIcon ?>">
                            </div>
                            <div class="ability-med-text cs-secondary_<?= $gPowerIcon ?>">
                                <span><?= formatStats($character['stats']['maxPower'] ?? $character['power1']) ?></span>
                                <div class="cs-name "><?= $gPowerText ?></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?php [
                            $mPowerIcon,
                            $mPowerText,
                            $mPowerVal
                        ]
                            = detectMainStat($character['class'], $character['stats']['statSta'] ?? 0, $character['stats']['statStr'] ?? 0, $character['stats']['statAgi'] ?? 0, $character['stats']['statInt'] ?? 0, $character['stats']['statSpr'] ?? 0, $character['stats']['defDefense'] ?? 0); ?>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/<?= $mPowerIcon ?>.svg" alt="<?= $mPowerIcon ?>">
                            </div>
                            <div class="ability-med-text cs-<?= $mPowerIcon ?>"><span><?= $mPowerVal ?></span>
                                <div class="cs-name"><?= $mPowerText ?></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/stat_stamina.svg" alt="Stamina">
                            </div>
                            <div class="ability-med-text cs-stat_stamina">
                                <span><?= $character['stats']['statSta'] ?? 'Unknown' ?></span>
                                <div class="cs-name">Stamina</div>
                            </div>
                        </div>
                    </div><?php $classSection = guessMainSpec($character['class'], $character['stats']);
                    foreach ($classSection as $classStats): ?>
                        <div>
                            <div class="ability-med">
                                <div class="ability-med-image">
                                    <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/<?= $classStats['icon'] ?>.svg" alt="<?= $classStats['icon'] ?>">
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
                    <?php foreach ($character['profession_primary'] as $primary) : ?>
                        <div>
                            <div class="progressbar">
                                <div class="progressbar-border"></div>
                                <div class="progressbar-progress" style="width:<?= percentageOf(
                                    $primary['value'],
                                    $primary['max']
                                ) ?>%"></div>
                                <div class="progressbar-content">
                                    <span class="profession-logo" style="background-image: url('<?= base_url() ?>/application/modules/database/assets/images/icons/<?= $primary['icon'] ?>.png')">&nbsp;</span> <?= $primary['name'] ?>
                                    <span style="float:right"><?= $primary['value'] . ' / ' . $primary['max'] ?></span>
                                    <div class="progressbar-body"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    if (count($character['profession_primary']) < 2) :
                        for ($x = 0; $x < (2 - count($character['profession_primary'])); $x++) : ?>
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
                    <?php foreach ($character['profession_secondary'] as $secondary) : ?>
                        <div>
                            <div class="progressbar">
                                <div class="progressbar-border"></div>
                                <div class="progressbar-progress" style="width:<?= percentageOf(
                                    $secondary['value'],
                                    $secondary['max']
                                ) ?>%"></div>
                                <div class="progressbar-content">
                                    <span class="profession-logo" style="background-image: url('<?= base_url() ?>/application/modules/database/assets/images/icons/<?= $secondary['icon'] ?>.png')">&nbsp;</span> <?= $secondary['name'] ?>
                                    <span style="float:right"><?= $secondary['value'] . ' / ' . $secondary['max'] ?></span>
                                    <div class="progressbar-body"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    if (count($character['profession_secondary']) == 0) : ?>
                        <div>
                            <h4><?= $character['name'] ?> hasn't learned any secondary profession yet.</h4>
                        </div>
                    <?php endif; ?>
                </div>
                <h3>PvP Stats</h3>
                <hr>
                <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-4@m uk-margin-remove-left" uk-grid>
                    <div>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/PvP_TotalHK.svg" alt="Total HK">
                            </div>
                            <div class="ability-med-text">
                                <span><?= formatStats($character['honor_total_hk']) ?></span>
                                <div class="cs-name cs-HEALTH">HONORABLE KILLS</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/<?= $character['honor_current_rank']['icon'] ?>.png" alt="Rank">
                            </div>
                            <div class="ability-med-text">
                                <span><?= $character['faction'] == 'Alliance' ? $character['honor_current_rank']['a_title'] : $character['honor_current_rank']['h_title'] ?> <small>#R<?= $character['honor_current_rank']['rank'] ?></small></span>
                                <div class="cs-name cs-HEALTH">TITLE / <small>#RANK</small></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/PvP_HonorPoints.svg" alt="Honor Points">
                            </div>
                            <div class="ability-med-text">
                                <span><?= formatStats($character['honor_last_week_cp']) ?></span>
                                <div class="cs-name cs-HEALTH">HONOR <small>(Last Week)</small></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="ability-med">
                            <div class="ability-med-image">
                                <img src="<?= base_url() ?>/application/modules/armory/assets/images/stats/PvP_Standing.svg" alt="Standing">
                            </div>
                            <div class="ability-med-text">
                                <span><?= $character['honor_standing'] == 0 ? 'N/A' : $character['honor_standing'] ?></span>
                                <div class="cs-name cs-HEALTH">STANDING <small>(Last Week)</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/tooltip.js'; ?>"></script>
<script type="module" src="<?= base_url() . 'application/modules/armory/assets/js/armory.js'; ?>"></script>
<script>
    const baseURL = "<?= base_url($lang); ?>";
    const imgURL = "<?= base_url() . 'application/modules/database/assets/images/icons/'; ?>";
    const tooltipEqItemArr = <?= json_encode($character['equipped_item_ids'], JSON_NUMERIC_CHECK) ?>;
    const tooltipCharData = <?= json_encode($character['enchanted_items'] ?? [], JSON_NUMERIC_CHECK) ?>;
    const character = {
        "race": <?= $character['race'] ?>,
        "gender": <?= $character['gender'] ?>,
        "skin": <?= $character['skin'] ?>,
        "face": <?= $character['face'] ?>,
        "hairStyle": <?= $character['hair_style'] ?>,
        "hairColor": <?= $character['hair_color'] ?>,
        "facialStyle": <?= $character['facial_hair'] ?>,
        "items": <?= count($character['equipped_item_ids']) === 0
            ? '[]' . PHP_EOL
            : json_encode(
                  array_map(function ($i) {
                      return array_values(get_object_vars($i));
                  }, $character['equipped_item_model'])
              ) . PHP_EOL ?>
        <?php /*for php>=7.4 */ //"items": <?= count($character['equipped_item_ids']) === 0 ? '[]' . PHP_EOL : json_encode(array_map(fn ($i) => array_values(get_object_vars($i)), $character['equipped_item_model'])) . PHP_EOL?>
    };
    const equipments = <?= count($character['equipped_item_ids']) === 0 ? '[]' . PHP_EOL : $character['equipped_item_id_model'] ?>;
</script>
<script type="text/javascript" src="https://wow.zamimg.com/modelviewer/classic/viewer/viewer.min.js"></script>