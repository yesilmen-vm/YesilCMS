<section class="uk-section uk-section-xsmall" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
            <div class="uk-width-expand uk-heading-line">
                <h3 class="uk-h3"><i class="fas fa-timeline"></i> <?= $this->lang->line('admin_nav_timeline'); ?></h3>
            </div>
            <div class="uk-width-auto">
                <a href="<?= base_url('admin/timeline/create'); ?>" class="uk-icon-button"><i class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="uk-card uk-card-default uk-card-body">
            <div class="uk-overflow-auto">
                <table class="uk-table uk-table-middle uk-table-divider uk-table-small">
                    <thead>
                    <tr>
                        <th class="uk-table-shrink"><?= $this->lang->line('table_header_id'); ?></th>
                        <th class="uk-width-small"><?= $this->lang->line('table_header_patch'); ?></th>
                        <th class="uk-width-small"><?= $this->lang->line('table_header_name'); ?></th>
                        <th class="uk-width-small"><?= $this->lang->line('table_header_date'); ?></th>
                        <th class="uk-width-small"><?= $this->lang->line('table_header_order'); ?></th>
                        <th class="uk-width-small"><?= $this->lang->line('table_header_image'); ?></th>
                        <th class="uk-width-small uk-text-center"><?= $this->lang->line('table_header_actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $timeline = $this->admin_model->getTimeline();
                    if ($timeline):
                        foreach ($this->admin_model->getTimeline() as $timeline_items): ?>
                            <tr>
                                <td><?= $timeline_items['id'] ?></td>
                                <td><?= $timeline_items['patch'] ?></td>
                                <td><?= strip_tags(truncateString(json_decode($timeline_items['description'], true)['description'])) ?>
                                <td><?= $timeline_items['date'] ?></td>
                                <td><?= $timeline_items['order'] ?></td>
                                <td uk-lightbox="animation: fade">
                                    <a href="/assets/images/timeline/<?= $timeline_items['image'] ?>" data-caption="Timeline Background"><img src="/assets/images/timeline/<?= $timeline_items['image'] ?>" alt="Timeline background" width="100px"></img></a>
                                </td>
                                <td>
                                    <div class="uk-flex uk-flex-left uk-flex-center@m uk-margin-small">
                                        <a href="<?= base_url('admin/timeline/edit/' . $timeline_items['id']); ?>" class="uk-button uk-button-primary uk-margin-small-right"><i class="fas fa-edit"></i></a>
                                        <button class="uk-button uk-button-danger" value="<?= $timeline_items['id'] ?>" id="button_delete<?= $timeline_items['id'] ?>" onclick="DeleteDownload(event, this.value)"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                    else:?>
                        <tr>
                            <td class="uk-text-center" colspan="7">No Timeline Event Available..</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    var csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash() ?>";

    function DeleteDownload(e, value) {
        e.preventDefault();

        $.ajax({
            url: "<?= base_url($lang . '/admin/timeline/delete'); ?>",
            method: "POST",
            data: {value, [globalThis.csrfName]: globalThis.csrfHash},
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
                    'delay': 500,
                    'position': 'top right',
                    'inEffect': 'slideRight',
                    'outEffect': 'slideRight'
                });
            },
            success: function (response) {
                if (response == true) {
                    $.amaran({
                        'theme': 'awesome ok',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_success'); ?>',
                            message: '<?= $this->lang->line('notification_timeline_deleted'); ?>',
                            info: '',
                            icon: 'fas fa-check-circle'
                        },
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                } else {
                    $.amaran({
                        'theme': 'awesome error',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_error'); ?>',
                            message: '<?= $this->lang->line('notification_general_error'); ?>',
                            info: '',
                            icon: 'fas fa-times-circle'
                        },
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        });
    }
</script>