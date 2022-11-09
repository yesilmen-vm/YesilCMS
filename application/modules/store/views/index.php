<link rel="stylesheet" href="<?= base_url() . 'application/modules/database/assets/css/database.css'; ?>"/>
<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div id="tooltip" class="tooltip"></div>
    <div class="uk-container">
        <div class="uk-grid uk-grid-medium" data-uk-grid>
            <div class="uk-width-1-5@m">
                <ul class="uk-nav uk-nav-default myaccount-nav">
                    <?php if ($this->wowmodule->getUCPStatus() == '1'): ?>
                        <li><a href="<?= base_url('panel'); ?>"><i class="fas fa-user-circle"></i> <?= $this->lang->line('tab_account'); ?></a></li>
                    <?php endif; ?>
                    <li class="uk-nav-divider"></li>
                    <?php if ($this->wowmodule->getDonationStatus() == '1'): ?>
                        <li><a href="<?= base_url('donate'); ?>"><i class="fas fa-hand-holding-usd"></i> <?= $this->lang->line('navbar_donate_panel'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getVoteStatus() == '1'): ?>
                        <li><a href="<?= base_url('vote'); ?>"><i class="fas fa-vote-yea"></i> <?= $this->lang->line('navbar_vote_panel'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getStoreStatus() == '1'): ?>
                        <li class="uk-active"><a href="<?= base_url('store'); ?>"><i class="fas fa-store"></i> <?= $this->lang->line('tab_store'); ?></a></li>
                    <?php endif; ?>
                    <li class="uk-nav-divider"></li>
                    <?php if ($this->wowmodule->getBugtrackerStatus() == '1'): ?>
                        <li><a href="<?= base_url('bugtracker'); ?>"><i class="fas fa-bug"></i> <?= $this->lang->line('tab_bugtracker'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getChangelogsStatus() == '1'): ?>
                        <li><a href="<?= base_url('changelogs'); ?>"><i class="fas fa-scroll"></i> <?= $this->lang->line('tab_changelogs'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getDownloadStatus() == '1'): ?>
                        <li><a href="<?= base_url('download'); ?>"><i class="fas fa-download"></i> <?= $this->lang->line('tab_download'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="uk-width-1-5@m">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        <h5 class="uk-h5 uk-text-bold"><i class="far fa-list-alt"></i> <?= $this->lang->line('store_categories'); ?></h5>
                    </div>
                    <ul class="uk-nav-default nav-store uk-nav-parent-icon" uk-nav>
                        <li class="uk-active"><a href="<?= base_url('store'); ?>"><i class="fas fa-star"></i> <?= $this->lang->line('store_top_items'); ?></a></li>
                        <?php foreach ($this->wowrealm->getRealms()->result() as $MultiRealm) : ?>
                            <li class="uk-parent">
                                <a href="javascript:void(0);"><i class="fas fa-server"></i> <?= $this->wowrealm->getRealmName($MultiRealm->realmID); ?></a>
                                <ul class="uk-nav-sub uk-nav-parent-icon" uk-nav>
                                    <?php foreach ($this->store_model->getCategories($MultiRealm->realmID)->result() as $menulist) : ?>
                                        <?php if ($menulist->main == '2' && $menulist->father == '0') : ?>
                                            <li class="uk-parent">
                                                <a href="#"><?= $menulist->name ?></a>
                                                <ul class="uk-nav-sub">
                                                    <?php foreach ($this->store_model->getChildStoreCategory($menulist->id)->result() as $menuchildlist) : ?>
                                                        <li><a href="<?= base_url('store/' . $menuchildlist->route) ?>"><?= $menuchildlist->name ?></a></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </li>
                                        <?php elseif ($menulist->main == '1' && $menulist->father == '0') : ?>
                                            <li><a href="<?= base_url('store/' . $menulist->route) ?>"><?= $menulist->name ?></a></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="uk-width-3-5@m">
                <div class="uk-card uk-card-default uk-card-body">
                    <h5 class="uk-h5 uk-text-bold uk-margin-remove-bottom"><i class="fas fa-star"></i> <?= $this->lang->line('store_top_items'); ?></h5>
                    <hr class="uk-margin-small-top">
                    <div class="uk-grid uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m" data-uk-grid>
                        <?php foreach ($this->store_model->getStoreTop() as $top) : ?>
                            <div>
                                <div class="blizzcms-item-container">
                                    <div class="blizzcms-item-header uk-text-truncate" uk-tooltip="<?= $this->store_model->getName($top->store_item); ?>" uk-toggle="target: #top-<?= $top->id ?>;animation: uk-animation-slide-top-small">
                                        <div class="item-store-icon">
                                            <img src="<?= base_url() ?>/application/modules/database/assets/images/icons/<?= $this->store_model->getIcon($top->store_item); ?>.png" alt="">
                                        </div>
                                        <span class="uk-text-middle">
                                            <?php if ($this->store_model->getType($top->id) == 1) : ?>
                                                <a href="<?= base_url($lang) . '/item/' . $this->store_model->getCommand($top->store_item); ?>" class="item-store-href" data-item='item=<?= $this->store_model->getCommand($top->store_item) ?>'
                                                   data-patch='10'><?= $this->store_model->getName($top->store_item); ?></a>
                                            <?php else : ?>
                                                <a href="#" class="item-store-href"><?= $this->store_model->getName($top->store_item); ?></a>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div id="top-<?= $top->id ?>" class="blizzcms-item-body" hidden>
                                        <p class="uk-text-break"><?= $this->store_model->getDescription($top->store_item); ?></p>
                                        <hr class="uk-margin-small">
                                        <div class="uk-grid uk-grid-small uk-flex uk-flex-center" data-uk-grid>
                                            <div class="uk-width-auto">
                                                <?php if ($this->store_model->getPriceType($top->store_item) == 1) : ?>
                                                    <span class="blizzcms-item-price"><span uk-tooltip="title: <?= $this->lang->line('panel_dp'); ?>"><i class="dp-icon"></i></span><?= $this->store_model->getPriceDP($top->store_item); ?></span>
                                                <?php elseif ($this->store_model->getPriceType($top->store_item) == 2) : ?>
                                                    <span class="blizzcms-item-price"><span uk-tooltip="title: <?= $this->lang->line('panel_vp'); ?>"><i class="vp-icon"></i></span><?= $this->store_model->getPriceVP($top->store_item); ?></span>
                                                <?php elseif ($this->store_model->getPriceType($top->store_item) == 3) : ?>
                                                    <span class="blizzcms-item-price"><span uk-tooltip="title: <?= $this->lang->line('panel_dp'); ?>"><i class="dp-icon"></i></span><?= $this->store_model->getPriceDP($top->store_item); ?> <span class="uk-badge">&amp;</span> <span
                                                                uk-tooltip="title: <?= $this->lang->line('panel_vp'); ?>"><i class="vp-icon"></i></span><?= $this->store_model->getPriceVP($top->store_item); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="uk-width-auto">
                                                <?php if ($this->wowrealm->RealmStatus($MultiRealm->realmID, $this->wowrealm->realmGetHostname($MultiRealm->realmID))) : ?>
                                                    <button class="uk-button uk-button-default uk-button-small" id="button_item<?= $top->store_item ?>" value="<?= $top->store_item ?>" onclick="AddItem(event, this.value)"><i class="fas fa-cart-plus"></i> <?= $this->lang->line('button_cart'); ?>
                                                    </button>
                                                <?php else : ?>
                                                    <button title="<?= $this->lang->line('panel_server_checkstatus'); ?>" class="uk-button uk-button-default uk-button-small" id="button_item<?= $top->store_item ?>" value="<?= $top->store_item ?> " onclick="AddItem(event, this.value)" disabled><i
                                                                class="fas fa-cart-plus"></i> <?= $this->lang->line('button_cart'); ?></button>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const baseURL = "<?= base_url($lang); ?>";
    const imgURL = "<?= base_url() . 'application/modules/database/assets/images/icons/'; ?>";
    var csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash() ?>";

    function AddItem(e, value) {
        e.preventDefault();

        $.ajax({
            url: "<?= base_url($lang . '/cart/add'); ?>",
            method: "POST",
            data: {value, [globalThis.csrfName]: globalThis.csrfHash},
            dataType: "text",
            success: function (response) {
                if (!response)
                    alert(response);

                if (response) {
                    $.amaran({
                        'theme': 'awesome ok',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_success'); ?>',
                            message: '<?= $this->lang->line('notification_store_item_added'); ?>',
                            info: '',
                            icon: 'fas fa-check-circle'
                        },
                        'delay': 5000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                }
                location.reload();
            }
        });
    }
</script>
<script type="text/javascript" src="<?= base_url() . 'application/modules/database/assets/js/tooltip.js'; ?>"></script>