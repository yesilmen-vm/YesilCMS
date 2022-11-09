<link rel="stylesheet" href="<?= base_url() . 'application/modules/database/assets/css/database.css'; ?>"/>
<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <?php if ($spell) : ?>
            <div class="text">
                <h1><?= $spell['name'] ?></h1>
            </div>
            <div class="uk-text-left" uk-grid>
                <div class="uk-width-auto@m">
                    <div style="float: left; padding-right:10px; margin-top:-2px">
                        <div class="iconlarge">
                            <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $spell['icon'] ?>.png');"></ins>
                            <del></del>
                        </div>
                    </div>
                    <div class="uk-card uk-card-default uk-card-body" style="max-width:350px; padding: 0">
                        <div class="yesilcms-tooltip">
                            <table>
                                <tr>
                                    <td>
                                        <?php if ($spell['nameSubtext']) : ?>
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td><b><?= $spell['name'] ?></b></td>
                                                    <th><b class="q0"><?= $spell['nameSubtext'] ?></b></th>
                                                </tr>
                                            </table>
                                        <?php else: ?>
                                            <b><?= $spell['name'] ?></b><br/>
                                        <?php endif;
                                        if ($spell['cost'] && $spell['range']) : ?>
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td><?= $spell['cost'] ?></td>
                                                    <th><?= $spell['range'] ?></th>
                                                </tr>
                                            </table>
                                        <?php elseif ($spell['cost'] || $spell['range']) : ?>
                                            <?= $spell['range'] . $spell['cost'] ?>
                                        <?php endif;
                                        if (($spell['cost'] xor $spell['range']) && ($spell['cast'] xor $spell['cooldown'])) : ?>
                                            <br/>
                                        <?php endif;
                                        if ($spell['cast'] && $spell['cooldown']) :?>
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td><?= $spell['cast'] ?> </td>
                                                    <th><?= $spell['cooldown'] ?></th>
                                                </tr>
                                            </table>
                                        <?php else : ?>
                                            <?= $spell['cast'] . $spell['cooldown'] ?>
                                        <?php endif; ?>
                                        <?php if ($spell['tools'] || $spell['reagents']) : ?>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <?php if ($spell['tools']) : ?>
                                                            Tools: <br/>
                                                            <div class="indent q1">
                                                                <?php $numItems = count($spell['tools']);
                                                                $i              = 0; ?>
                                                                <?php foreach ($spell['tools'] as $t) :
                                                                    if (isset($t['id'])) : ?>
                                                                        <a href="<?= base_url($lang) ?>/item/<?= $t['id'] ?>/<?= $patch ?>" data-item="item=<?= $t['id'] ?>" data-patch='<?= dataPatch($patch) ?>'><span class="q<?= $t['quality'] ?>"><?= $t['name'] ?></span></a>
                                                                    <?php else : ?>
                                                                        <?= $t['name'] ?>
                                                                    <?php endif;
                                                                    echo empty(++$i !== $numItems) ? '<br />' : ', ';
                                                                endforeach; ?>
                                                            </div>
                                                            <br/>
                                                        <?php endif; ?>
                                                        <?php if ($spell['reagents']) : ?>
                                                            Reagents: <br/>
                                                            <div class="indent q1">
                                                                <?php $numItems = count($spell['reagents']);
                                                                $i              = 0; ?>
                                                                <?php foreach ($spell['reagents'] as $r) : ?>
                                                                    <a href="<?= base_url($lang) ?>/item/<?= $r['id'] ?>/<?= $patch ?>" data-item="item=<?= $r['id'] ?>" data-patch='<?= dataPatch($patch) ?>'><?= $r['name'] ?></a>
                                                                    <?php if ($r['count'] > 1) {
                                                                        echo '(' . $r['count'] . ')';
                                                                    } //prevent space
                                                                    echo empty(++$i !== $numItems) ? '<br />' : ', ';
                                                                endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php endif ?>
                                        <?php if ($spell['desc']) : ?>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <span class="q"><?= $spell['desc'] ?></span>
                                                        <br/>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php if ($spell['reagents'] || $spell['tools']) : ?>
                        <div class="text">
                            <?php if ($spell['reagents']) : ?>
                                <div class="uk-align-left@m"><h2>Reagents</h2>
                                    <table>
                                        <tbody>
                                        <?php $spell['reagents'] = array_reverse($spell['reagents']);
                                        while ($r = array_pop($spell['reagents'])) : ?>
                                            <tr>
                                                <th>
                                                    <div class="iconsmall">
                                                        <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $r['icon'] ?>.png'); background-size: 18px;"></ins>
                                                        <del></del>
                                                        <?php if ($r['count'] > 1) : ?>
                                                            <span class="stackable stackable-r"><?= $r['count'] ?></span>
                                                        <?php endif; ?>
                                                </th>
                                                <td><span class="q<?= $r['quality'] ?>"><a href="<?= base_url($lang) ?>/item/<?= $r['id'] ?>/<?= $patch ?>" data-item="item=<?= $r['id'] ?>" data-patch='<?= dataPatch($patch) ?>'><?= $r['name'] ?></a></span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif;
                            if ($spell['tools']) : ?>
                                <div class="<?= $spell['reagents'] ? 'uk-align-right@m' : 'uk-align-left@m' ?>"><h2>Tools</h2>
                                    <table>
                                        <tbody>
                                        <?php $spell['tools'] = array_reverse($spell['tools']);
                                        while ($t = array_pop($spell['tools'])) : ?>
                                            <tr>
                                                <th>
                                                    <div class="iconsmall">
                                                        <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $t['icon'] ?>.png'); background-size: 18px;"></ins>
                                                        <del></del>
                                                </th>
                                                <td><span class="q<?= $t['quality'] ?>"><a href="<?= base_url($lang) ?>/item/<?= $t['id'] ?>/<?= $patch ?>" data-item="item=<?= $t['id'] ?>" data-patch='<?= dataPatch($patch) ?>'><?= $t['name'] ?></a></span></td>
                                            </tr>
                                        <?php endwhile; ?>

                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="uk-width-expand@m">
                    <!--<div class="uk-card uk-card-default uk-card-body">TODO: 1-3 Either keep it hidden or put some data here</div>-->
                </div>
                <div class="uk-width-auto@m uk-align-right@m">
                    <div class="uk-card uk-card-default uk-card-body">

                        <table class="infobox">
                            <tbody>
                            <tr>
                                <th>Quick Facts</th>
                            </tr>
                            <tr>
                                <td>Added in Build: <?= $spell['added_build']; ?></td>
                            </tr>
                            <?php if (count($spell['build_list']) > 1) : ?>
                                <tr>
                                    <td>
                                        Build Versions:
                                        <ul>
                                            <?php foreach ($spell['build_list'] as $sbuild) : ?>
                                                <li><a href="<?= base_url($lang) ?>/spell/<?= $spell['entry'] ?>/<?= buildToPatch($sbuild['build']) ?>" data-spell="spell=<?= $spell['entry'] ?>" data-patch='<?= buildToPatch($sbuild['build']) ?>'>Build <?= $sbuild['build'] ?> </a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>
                                    <div>
                                        <ul class="first last">
                                            <?php if ($spell['baseLevel']) : ?>
                                                <li>
                                                    <div>Requires Level <?= $spell['baseLevel']; ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <div>Icon: <a href="<?= base_url() . 'application/modules/database/assets/images/icons/' . $spell['icon'] ?>.png" target="_blank"><span class="iconsmall"><ins
                                                                    class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $spell['icon'] ?>.png'); background-size: 18px;"></ins><del></del></span><?= $spell['icon'] ?></a></div>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text">
                <h2 class="clear" style="margin-top:0">Spell Details</h2>
                <div class="uk-overflow-auto uk-margin-small">
                    <table class="uk-table uk-table-divider uk-table-hover spell-details-table" id="spelldetails">
                        <colgroup>
                            <col style="width:8%">
                            <col style="width:42%">
                            <col style="width:50%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <td colspan="2" style="padding: 0;"></td>
                            <td rowspan="6" style="padding: 0; border-left: 3px solid #404040">
                                <table class="spell-details-table" style="border: 0">
                                    <tbody>
                                    <tr>
                                        <td style="height: 0; padding: 0; border: 0" colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 0; border-top: 0">Duration</th>
                                        <td style="width: 100%; border-top: 0"><?= tableText($spell['duration'], true) ?></td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 0">School</th>
                                        <td style="width: 100%; border-top: 0"><?= tableText($spell['school_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 0">Mechanic</th>
                                        <td style="width: 100%; border-top: 0"><?= tableText($spell['mechanics'], true) ?></td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 0">Dispel type</th>
                                        <td style="width: 100%; border-top: 0"><?= tableText($spell['dispel_type'], true) ?></td>
                                    </tr>
                                    <tr>
                                        <th style="border-bottom: 0; border-left: 0">Spell category</th>
                                        <td style="border-bottom: 0"><?= tableText($spell['cat'], true, '(' . $spell['category'] . ')') ?></td>
                                    </tr>
                                    <tr>
                                        <th style="border-bottom: 0; border-left: 0">GCD category</th>
                                        <td style="border-bottom: 0"><?= tableText($spell['gcd_cat'], true, '(' . $spell['startRecoveryCategory'] . ')') ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th style="border-top: 0">Cost</th>
                            <td style="border-top: 0"><?= tableText($spell['cost']) ?></td>
                        </tr>
                        <tr>
                            <th>Range</th>
                            <td><?= tableText($spell['range_t']) ?></td>
                        </tr>
                        <tr>
                            <th>Cast time</th>
                            <td><?= tableText($spell['cast']) ?></td>
                        </tr>
                        <tr>
                            <th>Cooldown</th>
                            <td><?= tableText($spell['cooldown'], true) ?></td>
                        </tr>
                        <tr>
                            <th><dfn title="Global Cooldown">GCD</dfn></th>
                            <td><?= tableText($spell['gcd'], true) ?></td>
                        </tr>
                        <?php if ($spell['effects']) :
                            foreach ($spell['effects'] as $key => $eff) : ?>
                                <?php /*if (array_filter(array_map('array_filter', $eff))) :*/ ?>
                                <tr>
                                    <th>Effect #<?= $key + 1 ?></th>
                                    <td colspan="3">
                                        <?php if ($eff) : //default, if none exists?>
                                            (<?= $spell['effect' . ($key + 1)] ?>) <?= $eff['eff_name'] ?><?= (int)$spell['effect' . ($key + 1)] === 6 ? ' #' . $eff['a_id'] . ':' : ($eff['type'] === 1 ? '<span class="q2">' . $eff['value'] . '</span>' : '') ?>
                                            <?php if ((int)$spell['effect' . ($key + 1)] === 6) : // aura ?>
                                                <?= $eff['a_name'] ?> <?= ($eff['eff_val'] ? '(' . $eff['eff_val'] . ')' : '') ?>
                                            <?php endif; ?>
                                            <?php if ($eff['type'] === 3) : //trigger ?>
                                                <table class="icontab">
                                                    <tbody>
                                                    <tr>
                                                        <th>
                                                            <div class="iconmedium">
                                                                <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $eff['icon'] ?>.png'); background-size: 36px;"></ins>
                                                                <del></del>
                                                                <a href="<?= base_url($lang) ?>/spell/<?= $eff['id'] ?>/<?= buildToPatch($eff['build']) ?>" data-spell="spell=<?= $eff['id'] ?>" data-patch='<?= buildToPatch($eff['build']) ?>'></a></div>
                                                        </th>
                                                        <td>
                                                            <a class="q" href="<?= base_url($lang) ?>/spell/<?= $eff['id'] ?>/<?= buildToPatch($eff['build']) ?>" data-spell="spell=<?= $eff['id'] ?>"
                                                               data-patch='<?= buildToPatch($eff['build']) ?>'><?= $eff['name'] ?></a>
                                                            <?php if ($eff['interval']) : ?>
                                                                <br><small>Interval: <?= $eff['interval'] ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                            <?php if ($eff['type'] === 4) : //trigger ?>
                                                <table class="icontab">
                                                    <tbody>
                                                    <tr>
                                                        <th>
                                                            <div class="iconmedium">
                                                                <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $eff['icon'] ?>.png'); background-size: 36px;"></ins>
                                                                <del></del>
                                                                <a href="<?= base_url($lang) ?>/item/<?= $eff['id'] ?>/<?= $eff['patch'] ?>" data-item="item=<?= $eff['id'] ?>" data-patch='<?= $eff['patch'] ?>'></a></div>
                                                        </th>
                                                        <td>
                                                            <a class="q<?= $eff['quality'] ?>" href="<?= base_url($lang) ?>/item/<?= $eff['id'] ?>/<?= $eff['patch'] ?>" data-item="item=<?= $eff['id'] ?>"
                                                               data-patch='<?= $eff['patch'] ?>'><?= $eff['name'] ?></a>
                                                            <?php if ($eff['interval']) : ?>
                                                                <br><small>Interval: <?= $eff['interval'] ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                            <small>
                                                <?php if ($eff['value'] && $eff['value'] !== 1) : ?>
                                                    <br>Value: <?= $eff['value'] ?>
                                                <?php endif;
                                                if ($eff['radius']) : ?>
                                                    <br>Radius: <?= $eff['radius'] ?> yards
                                                <?php endif; ?>
                                                <?php if ($eff['interval'] && $eff['type'] !== 3) : ?>
                                                    <br>Interval: <?= $eff['interval'] ?>
                                                <?php endif; ?>
                                                <?php if ($eff['mechanic']) : ?>
                                                    <br>Mechanic: <?= $eff['mechanic'] ?>
                                                <?php endif; ?>
                                            </small>
                                            <?php if ($eff['type'] === 5) : // affected spells 
                                                $i = 1; ?>
                                                <br><small>Affected Spells:</small>
                                                <table class="icontab">
                                                    <tbody>
                                                    <?php foreach ($eff['list'] as $spell_affected) :
                                                        if ($i + 1 % 5 == 0): ?>
                                                            <tr>
                                                        <?php endif; ?>
                                                        <th class="uk-padding-small">
                                                            <div class="iconmedium">
                                                                <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $spell_affected['icon'] ?>.png'); background-size: 36px;"></ins>
                                                                <del></del>
                                                                <a href="<?= base_url($lang) ?>/spell/<?= $spell_affected['entry'] ?>/<?= buildToPatch($spell_affected['build']) ?>" data-spell="spell=<?= $spell_affected['entry'] ?>" data-patch='<?= buildToPatch($spell_affected['build']) ?>'></a>
                                                            </div>
                                                        </th>
                                                        <td>
                                                            <a class="q" href="<?= base_url($lang) ?>/spell/<?= $spell_affected['entry'] ?>/<?= buildToPatch($spell_affected['build']) ?>" data-spell="spell=<?= $spell_affected['entry'] ?>"
                                                               data-patch='<?= buildToPatch($spell_affected['build']) ?>'><?= $spell_affected['name'] ?></a>
                                                            <br><small><?= $spell_affected['nameSubtext'] ?></small>
                                                        </td>
                                                        <?php
                                                        if ($i % 5 == 0): ?>
                                                            </tr>
                                                        <?php endif;
                                                        $i++;
                                                    endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php /*endif;*/ ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($spell['flags']) : ?>
                            <tr>
                                <th>Flags</th>
                                <td colspan="3" style="line-height:17px">
                                    <ul style="margin:0">
                                        <?php foreach ($spell['flags'] as $flag) : ?>
                                            <li><span class="q"><?= $flag ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="uk-alert-danger uk-text-center uk-position-center" style="width:75%" uk-alert>
                <p>We searched all over Azeroth, but unfortunately we couldn't find the spell you are looking for..:(</p>
            </div>
        <?php endif; ?>
    </div>
</section>
<div id="tooltip" class="tooltip yesilcms-tooltip"></div>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/tooltip.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/timeline/assets/js/jquery.lazy.min.js'; ?>"></script>
<script>
    const baseURL = "<?= base_url($lang); ?>";
    const imgURL = "<?= base_url() . 'application/modules/database/assets/images/icons/'; ?>";
    $(function () {
        $('.yesilcms-lazy').lazy({
            combined: true,
            delay: 30000
        });
    });
</script>