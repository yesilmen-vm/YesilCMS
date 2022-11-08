<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section"
         style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <h4 class="uk-h4 uk-text-uppercase uk-text-bold"><i class="fas fa-fist-raised"></i> <?= $this->lang->line('tab_pvp_statistics'); ?></h4>
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="animation: uk-animation-fade; connect: .pvp-statistics">
            <?php foreach ($realms as $plistMultiRealm): ?>
                <li><a href="#"><i class="fas fa-server"></i> <?= $plistMultiRealm; ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul class="uk-switcher pvp-statistics uk-margin uk-animation-fade">
            <?php foreach ($realms as $rid => $table): ?>
                <li>
                    <div uk-grid>
                        <div class="uk-width-auto uk-text-left uk-transform-origin-bottom-right uk-animation-fade">
                            <button id="both-<?= $rid ?>" data-fact="-1" class="fact-filter fact-both uk-button uk-button-pvp uk-margin-small-right uk-active">
                                <i class="fa-solid fa-layer-group"></i> Both
                            </button>
                            <button id="alliance-<?= $rid ?>" data-fact="0" class="fact-filter fact-alliance uk-button uk-button-pvp ">
                                <img src="<?= base_url('application/modules/pvp/assets/images/misc/Alliance.png') ?>" width="18px" title="Alliance">
                                <span class="uk-text-middle pad-3"> Alliance</span>
                            </button>
                            <button id="horde-<?= $rid ?>" data-fact="1" class="fact-filter fact-horde uk-button uk-button-pvp uk-margin-small-left">
                                <img src="<?= base_url('application/modules/pvp/assets/images/misc/Horde.png') ?>" width="18px" title="Horde">
                                <span class="uk-text-middle pad-5"> Horde</span>
                            </button>
                        </div>
                        <div class="uk-width-1-1 uk-width-expand@s uk-text-right@s uk-transform-origin-bottom-right uk-animation-fade">
                            <button id="int-a-<?= $rid ?>" data-interval="0" class="interval-filter interval-all-times uk-button uk-button-pvp uk-margin-small-right uk-active">
                                <i class="fa-solid fa-diagram-successor"></i> All Times
                            </button>
                            <button id="int-lw-<?= $rid ?>" data-interval="1" class="interval-filter interval-last-week uk-button uk-button-pvp uk-margin-small-right">
                                <i class="fa-solid fa-diagram-predecessor"></i> Last Week
                            </button>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-overflow-auto uk-margin-small">
                            <table id="realm-<?= $rid ?>" class="realm-<?= $rid ?> uk-table uk-table-hover uk-table-divider uk-table-striped uk-table-small">
                                <thead>
                                <tr>
                                    <th class="uk-preserve-width">#</th>
                                    <th class="uk-preserve-width"><?= $this->lang->line('table_header_cname'); ?></th>
                                    <th class="uk-preserve-width"><?= $this->lang->line('table_header_gname'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_level'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_race'); ?> / <?= $this->lang->line('table_header_class'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_faction'); ?></th>
                                    <th id="kills" class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_total_kills'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_total_honor'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_today_kills'); ?></th>
                                    <th class="uk-preserve-width uk-text-center"><?= $this->lang->line('table_header_yersterday_kills'); ?></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/jquery.dataTables.min.js'; ?>"></script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/dataTables.uikit.min.js'; ?>"></script>
<script>
    $(function () {
        let fact = "-1";
        let interval = "0";
        let csrf_token = "<?= $this->security->get_csrf_hash() ?>";
        const logged = <?= $logged ? 'true' : 'false' ?>;
        const a_id = <?= $a_id ? $a_id : '-1' ?>;
        $('table').each(function () {
            const realmID = $(this).attr("id").split("-")[1];
            $(this).DataTable({
                autoWidth: false,
                ordering: false,
                lengthMenu: [10, 25, 50, 100, 250],
                processing: true,
                language: {
                    loadingRecords: '&nbsp;',
                    processing: 'Loading...'
                },
                serverSide: true,
                serverMethod: 'post',
                ajax: {
                    url: '<?= base_url($lang) ?>/pvp/stats',
                    data: function (d) {
                        d.<?= $this->security->get_csrf_token_name() ?> = csrf_token;
                        d.realm = realmID;
                        d.faction = fact;
                        d.interval = interval;
                    },
                    dataFilter: function (data) { //dynamic csrf token
                        var obj = JSON.parse(data);
                        csrf_token = obj.token;
                        return data;
                    }
                },
                drawCallback: function () {
                    var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                    var info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                    pagination.toggle(this.api().page.info().pages > 1);
                    //info.toggle(this.api().page.info().pages > 1);

                    $('.ysl').click(function () {
                        window.location = $(this).find('a').attr('href');
                    }).hover(function () {
                        $(this).toggleClass('hover');
                    });
                },
                columnDefs: [
                    {className: "uk-text-middle", targets: [1, 2]},
                    {className: "uk-text-center uk-text-middle", targets: [0, 3, 4, 5, 6, 7, 8, 9]},
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).addClass('ysl');
                    if (logged && a_id === parseInt(data[10])) {
                        $(row).addClass('pvp-ladder-logged-' + (data[11] === 'Alliance' ? 'a' : 'h'));
                    }
                }
            });
        });
        $(".fact-filter").on("click", function () {
            $(this).siblings().removeClass("uk-active");
            $(this).addClass("uk-active");
            const tabID = $(this).attr("id").split("-")[1];
            fact = $(this).data("fact");
            $(`table.realm-${tabID}`).DataTable().ajax.reload();
        });
        $(".interval-filter").on("click", function () {
            $(this).siblings().removeClass("uk-active");
            $(this).addClass("uk-active");
            const intID = $(this).attr("id").split("-")[2];
            interval = $(this).data("interval");
            $("#kills").html(!interval ? "<?= $this->lang->line('table_header_total_kills') ?>" : "<?= $this->lang->line('table_header_kills') ?>");
            $(`table.realm-${intID}`).DataTable().ajax.reload();
        });
    });
    // This is just to prevent using invalid CSRF token when csrf_regenerate is set to TRUE if user visits other pages and returns here through back button
    $.fn.dataTable.ext.errMode = 'none';
    $(document.body).on('xhr.dt', function (e, settings, json, xhr) {
        if (json === null && xhr.status === 403) {
            location.reload();
        }
    });
</script>
