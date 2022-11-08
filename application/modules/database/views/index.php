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
                                                <?= form_open('', array('method' => "post")); ?>
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
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
<div id="tooltip" class="tooltip yesilcms-tooltip"></div>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/bootstrap3-typeahead.min.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/tooltip.js'; ?>"></script>
<script>
    const baseURL = "<?= base_url($lang); ?>";
    const imgURL = "<?= base_url() . 'application/modules/database/assets/images/icons/'; ?>";
    let csrf_token = "<?= $this->security->get_csrf_hash() ?>";
    if (jQuery('input#search').length > 0) {
        jQuery('input#search').typeahead({
            autoSelect: false,
            minLength: 3,
            delay: 333,

            displayText: function (item) {
                type = "Item";
                //let isnum = /^\d+$/.test(jQuery('input#search').val());

                if (typeof item.quality === 'undefined') {
                    item.quality = 11; //spell
                }
                if (Object.keys(item).length == 8) {
                    type = 'spell';
                } else {
                    type = 'item';
                }

                res = '<div class="live-search-icon" style="background-image: url(<?= base_url() . 'application/modules/database/assets/images/icons/'?>' + item.icon + '.png)">';
                res += '<span class="bg">';
                res += '<a href="' + type + '/' + item.entry + '/' + <?= $patch ?> + '" class="q' + item.quality + '" data-' + type + '="' + type + '=' + item.entry + '" data-patch= <?= $patch ?>><span>' + item.name + '</span><i>' + type + '</i></a>';
                res += '</span></div>';
                return res;
            },
            afterSelect: function (item) {
                this.$element[0].value = item.name;
                patch = <?= $patch ?>;
                window.location.href = baseURL + '/' + type + '/' + item.entry + '/' + patch
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
</script>