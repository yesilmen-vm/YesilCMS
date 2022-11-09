<link rel="stylesheet" href="<?= base_url() . 'application/modules/database/assets/css/database.css'; ?>"/>
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
                        <div class="uk-margin">
                            <div class="uk-form-controls uk-light">
                                <div class="uk-inline uk-width-1-1">
                                    <h2 class="uk-text-center">Database Search</h2>
                                    <table class="uk-table uk-table-small uk-table-responsive">
                                        <?= form_open('database/result', array('id' => "searchDatabase", 'method' => "get")); ?>
                                        <tr>
                                            <td>
                                                <input class="uk-input" style="display:inline;" id="search"
                                                       name="search" type="text" minlength="3" autocomplete="off"
                                                       placeholder="Search Item & Spell by Name or ID" required>
                                            </td>
                                            <?= form_close(); ?>
                                            <td class="uk-width-1-4">
                                                <?= form_open('database/result' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''), array('method' => "post")); ?>
                                                <select name="globpatch" id="globpatch" class="uk-select" onchange="this.form.submit()">
                                                    <option value="" selected disabled hidden><?= $patch === 10 ? 'Current Patch: ' . getPatchName($patch) . ' (Default)' : 'Current Patch: ' . getPatchName($patch) ?></option>
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
                                                <?= form_close(); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input class="uk-button uk-button-default uk-width-1-1" type="submit" form="searchDatabase" value="search">
                        <br>
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
                        <?php if ($items || $spells) : ?>
                            <ul class="uk-tab" data-uk-tab="{connect:'#tab-id'}">
                                <?php if ($items) : ?>
                                    <li><a href="">Item (<?= count($items) ?>)</a></li>
                                <?php endif;
                                if ($spells) : ?>
                                    <li><a href="">Spell (<?= count($spells) ?>)</a></li>
                                <?php endif; ?>
                            </ul>
                            <ul id="tab-id" class="uk-switcher uk-margin">
                                <?php if ($items) : ?>
                                    <li>
                                        <div class="uk-overflow-auto uk-margin-small">
                                            <table class="uk-table uk-table-small uk-table-divider uk-table-hover uk-table-middle yesilcms-table">
                                                <thead>
                                                <tr>
                                                    <th class="uk-preserve-width">Name</th>
                                                    <th class="uk-preserve-width uk-text-center">Level</th>
                                                    <th class="uk-preserve-width uk-text-center">Req</th>
                                                    <th class="uk-preserve-width uk-text-center">Slot</th>
                                                    <th class="uk-preserve-width uk-text-center">Type</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($items as $item) : ?>
                                                    <tr>
                                                        <td style="min-width: 250px;">
                                                    <span class="iconmedium">
                                                        <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $item['icon'] ?>.png');"></ins>
                                                        <del></del>
                                                    </span>
                                                            <a href="<?= base_url($lang) ?>/item/<?= $item['entry'] ?>/<?= $patch ?>" data-item="item=<?= $item['entry'] ?>" data-patch='<?= $patch ?>'><span class="q<?= $item['quality'] ?>"><?= $item['name'] ?></span></a>
                                                        </td>
                                                        <td class="uk-text-center"><?= $item['item_level'] ?></td>
                                                        <td class="uk-text-center"><?= $item['required_level'] ?></td>
                                                        <td class="uk-text-center"><?= itemInventory($item['inventory_type']) ?></td>
                                                        <td class="uk-text-center"><?= itemSubClass($item['class'], $item['subclass']) ?? '' ?> </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if ($spells) : ?>
                                    <li>
                                        <div class="uk-overflow-auto uk-margin-small">
                                            <table class="uk-table uk-table-small uk-table-divider uk-table-hover uk-table-middle yesilcms-table">
                                                <thead>
                                                <tr>
                                                    <th class="uk-table-shrink">Name</th>
                                                    <th class="uk-text-center">Level</th>
                                                    <th class="uk-text-center">School</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($spells as $spell) : ?>
                                                    <tr>
                                                        <td style="min-width: 250px;">
                                                    <span class="iconmedium">
                                                        <ins class="yesilcms-lazy" style="background-image: url('<?= base_url() . 'application/modules/database/assets/images/icons/' . $spell['icon'] ?>.png');"></ins>
                                                        <del></del>
                                                    </span>
                                                            <a href="<?= base_url($lang) ?>/spell/<?= $spell['entry'] ?>/<?= $patch ?>" data-spell="spell=<?= $spell['entry'] ?>" data-patch='<?= $patch ?>'><?= $spell['name'] ?></a>
                                                            <?php if ($spell['nameSubtext']) : ?>
                                                                <div class="srank"><?= $spell['nameSubtext'] ?></div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="uk-text-center"><?= $spell['baseLevel'] ?></td>
                                                        <td class="uk-text-center"><?= schoolType($spell['school']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php else : ?>
                            <div class="uk-alert-danger uk-text-center " uk-alert>
                                <p>We searched all over Azeroth, but unfortunately we couldn't find what you are looking for..:(</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
<div id="tooltip" class="tooltip yesilcms-tooltip"></div>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/tooltip.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/jquery.dataTables.min.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/dataTables.uikit.min.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/timeline/assets/js/jquery.lazy.min.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/bootstrap3-typeahead.min.js'; ?>"></script>
<script>
    const baseURL = "<?= base_url($lang); ?>";
    const imgURL = "<?= base_url() . 'application/modules/database/assets/images/icons/'; ?>";
    let csrf_token = "<?= $this->security->get_csrf_hash() ?>";
    $(document).ready(function () {
        $(function () {
            $('.yesilcms-lazy').lazy();
        });
        $('table.yesilcms-table').DataTable({
            order: [[0, 'asc']]
        });

        if (jQuery('input#search').length > 0) {
            jQuery('input#search').typeahead({
                autoSelect: false,
                minLength: 3,
                delay: 333,

                displayText: function (item) {

                    type = "Item";

                    if (typeof item.quality === 'undefined') {
                        item.quality = 11; //spell
                    }

                    if (Object.keys(item).length == 8) {
                        type = 'spell';
                    } else {
                        type = 'item';
                    }

                    let isnum = /^\d+$/.test(jQuery('input#search').val());

                    res = '<div class="live-search-icon" style="background-image: url(<?= base_url() . 'application/modules/database/assets/images/icons/'?>' + item.icon + '.png)">';
                    res += '<span class="bg">';
                    res += '<a href="' + type + '/' + item.entry + '" class="q' + item.quality + '" data-' + type + '="' + type + '=' + item.entry + '" data-patch= 10><span>' + item.name + '</span><i>' + type + '</i></a>';
                    res += '</span></div>';
                    return res;
                },
                afterSelect: function (item) {
                    this.$element[0].value = item.name;
                    window.location.href = baseURL + '/' + type + '/' + item.entry
                },
                source: function (query, process) {
                    jQuery.ajax({
                        url: baseURL + "/api/v1/search/db",
                        data: {q: query, p: <?= $patch ?>, <?= $this->security->get_csrf_token_name() ?> : csrf_token},
                        dataType: "json",
                        type: "POST",
                        success: function (data) {
                            csrf_token = data.token
                            process(data.result)
                            TooltipExtended.initialize()
                        },
                        error: function (result) { //for next request
                            csrf_token = result.responseJSON.token;
                        }
                    })
                }
            });
        }
    });
</script>