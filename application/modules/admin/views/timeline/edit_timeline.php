<section class="uk-section uk-section-xsmall" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
            <div class="uk-width-expand uk-heading-line">
                <h3 class="uk-h3"><i class="fas fa-pen-to-square"></i> <?= $this->lang->line('placeholder_edit_timeline'); ?></h3>
            </div>
            <div class="uk-width-auto">
                <a href="<?= base_url('admin/timeline'); ?>" class="uk-icon-button"><i class="fas fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="uk-card uk-card-default">
            <div class="uk-card-body">
                <?= form_open_multipart('', 'id="timelineForm" onsubmit="timelineForm(event)"'); ?>
                <div class="uk-margin-small">
                    <div class="uk-grid uk-grid-small" data-uk-grid>
                        <div class="uk-inline uk-width-1-2@s">
                            <label for="patch" class="uk-form-label"><?= $this->lang->line('placeholder_patch'); ?></label>
                            <div class="uk-form-controls">
                                <select class="uk-select" id="patch" name="patch">
                                    <?php
                                    $result       = $this->admin_model->getTimelineEventByID($id);
                                    $patches      = [
                                        '0.3.4',
                                        '0.4.0',
                                        '0.5.3',
                                        '0.5.4',
                                        '0.5.5',
                                        '0.6',
                                        '0.7',
                                        '0.7.1',
                                        '0.7.2',
                                        '0.8',
                                        '0.9',
                                        '0.9.1',
                                        '0.10',
                                        '0.11',
                                        '0.12',
                                        '1.1.0',
                                        '1.1.1',
                                        '1.1.2',
                                        '1.2.0',
                                        '1.2.1',
                                        '1.2.2',
                                        '1.2.3',
                                        '1.2.4',
                                        '1.3.0',
                                        '1.3.1',
                                        '1.4.0',
                                        '1.4.1',
                                        '1.4.2',
                                        '1.5.0',
                                        '1.5.1',
                                        '1.6.0',
                                        '1.6.1',
                                        '1.7.0',
                                        '1.7.1',
                                        '1.8.0',
                                        '1.8.1',
                                        '1.8.2',
                                        '1.8.3',
                                        '1.8.4',
                                        '1.9.0',
                                        '1.9.1',
                                        '1.9.2',
                                        '1.9.3',
                                        '1.9.4',
                                        '1.10.0',
                                        '1.10.1',
                                        '1.10.2',
                                        '1.11.0',
                                        '1.11.1',
                                        '1.11.2',
                                        '1.12.0',
                                        '1.12.1',
                                        '1.12.2',
                                        '1.12.3',
                                    ];
                                    $active_patch = $result['patch'];
                                    foreach ($patches as $patch) :
                                        if ($patch === $active_patch) : ?>
                                            <option selected="selected" value="<?= $patch ?>"><?= $patch ?></option>
                                        <?php else : ?>
                                            <option value="<?= $patch ?>"><?= $patch ?></option>
                                        <?php endif;
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-inline uk-width-1-2@s">
                            <label for="date" class="uk-form-label"><?= $this->lang->line('placeholder_date'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="date" id="date" name="date" value="<?= $result['date'] ?>" onfocus="this.showPicker()" required pattern="\d{4}-\d{2}-\d{2}">
                            </div>
                        </div>
                    </div>
                </div>
                <?php $description = json_decode($result['description'], true); ?>
                <div class="uk-margin-small">
                    <label for="description" class="uk-form-label"><?= $this->lang->line('placeholder_description'); ?></label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea tinyeditor" id="description" name="description" rows="3" maxlength="10000"><?= $description['description'] ?></textarea>
                    </div>
                </div>
                <div class="uk-margin-small">
                    <div class="uk-grid uk-grid-small" data-uk-grid>
                        <div class="uk-inline uk-width-1-3@s">
                            <table class="uk-table" id="dyn_general">
                                <thead>
                                <tr>
                                    <th colspan="2" class="uk-text-center">General</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php $i = 0;
                                    foreach ($description['general'] as $general) :
                                    $i++;
                                    if ($i === 1) : ?>
                                        <td class="uk-padding-remove-horizontal">
                                            <input class="uk-input" type="text" id="general" name="general" value="<?= $general; ?>" placeholder="General Changelog">
                                        </td>
                                        <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                            <button class="uk-button uk-button-default" type="button" name="add_general" id="add_general"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    <?php else : ?>
                                </tr>
                                <tr id="row<?= $i ?>" class="dynamic-added">
                                    <td class="uk-padding-remove-horizontal">
                                        <input class="uk-input" type="text" id="general<?= $i ?>" name="general" value="<?= $general; ?>" placeholder="General Changelog" required>
                                    </td>
                                    <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                        <button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="<?= $i ?>"><i class="fa-solid fa-minus"></i></i></button>
                                    </td>
                                </tr>
                                <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="uk-inline uk-width-1-3@s">
                            <table class="uk-table" id="dyn_pve">
                                <thead>
                                <tr>
                                    <th colspan="2" class="uk-text-center">PvE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php $j = 0;
                                    foreach ($description['pve'] as $pve) :
                                    $j++;
                                    if ($j === 1) : ?>
                                        <td class="uk-padding-remove-horizontal">
                                            <input class="uk-input" type="text" id="pve" name="pve" value="<?= $pve; ?>" placeholder="PvE Changelog">
                                        </td>
                                        <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                            <button class="uk-button uk-button-default" type="button" name="add_pve" id="add_pve"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    <?php else : ?>
                                </tr>
                                <tr id="row<?= $i + $j ?>" class="dynamic-added">
                                    <td class="uk-padding-remove-horizontal">
                                        <input class="uk-input" type="text" id="pve<?= $i + $j ?>" name="pve" value="<?= $pve; ?>" placeholder="PvE Changelog" required>
                                    </td>
                                    <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                        <button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="<?= $i + $j ?>"><i class="fa-solid fa-minus"></i></i></button>
                                    </td>
                                </tr>
                                <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="uk-inline uk-width-1-3@s">
                            <table class="uk-table" id="dyn_pvp">
                                <thead>
                                <tr>
                                    <th colspan="2" class="uk-text-center">PvP</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php $k = 0;
                                    foreach ($description['pvp'] as $pvp) :
                                    $k++;
                                    if ($k === 1) : ?>
                                        <td class="uk-padding-remove-horizontal">
                                            <input class="uk-input" type="text" id="pvp" name="pvp" value="<?= $pvp; ?>" placeholder="PvP Changelog">
                                        </td>
                                        <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                            <button class="uk-button uk-button-default" type="button" name="add_pvp" id="add_pvp"><i class="fa-solid fa-plus"></i></button>
                                        </td>
                                    <?php else : ?>
                                </tr>
                                <tr id="row<?= $i + $j + $k ?>" class="dynamic-added">
                                    <td class="uk-padding-remove-horizontal">
                                        <input class="uk-input" type="text" id="pvp<?= $i + $j + $k ?>" name="pvp" value="<?= $pvp; ?>" placeholder="PvP Changelog" required>
                                    </td>
                                    <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                        <button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="<?= $i + $j + $k ?>"><i class="fa-solid fa-minus"></i></i></button>
                                    </td>
                                </tr>
                                <?php endif;
                                endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-small">
                    <div class="uk-grid uk-grid-small" data-uk-grid>
                        <div class="uk-inline uk-width-3-5@s">
                            <label for="image_file" class="uk-form-label">Image</label>
                            <div class="uk-form-controls">
                                <span uk-lightbox="animation: fade">
                                    <a href="/assets/images/timeline/<?= $result['image'] ?>" data-caption="Timeline Background"><button class="uk-button uk-button-danger">Show Current Image</button></a>
                                </span>
                                <div uk-form-custom="target: true">
                                    <input type="file" id="image_file" name="image_file">
                                    <input class="uk-input uk-form-width-medium" type="text" placeholder="Upload Image (allowed: jpg,jpeg,png)" style="width:300px" disabled>
                                    <button class="uk-button uk-button-primary" type="button" tabindex="-1"><i class="fas fa-file-upload"></i> <?= $this->lang->line('button_select'); ?></button>
                                </div>

                            </div>
                        </div>
                        <div class="uk-inline uk-width-2-5@s">
                            <label for="order" class="uk-form-label">Order</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="number" min="0" step="1" id="order" name="order" value="<?= $result['order'] ?>" placeholder="Timeline Order" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-small">
                    <button class="uk-button uk-button-primary uk-width-1-1" type="submit" id="button_slide"><i class="fas fa-check-circle"></i> <?= $this->lang->line('button_save_changes'); ?></button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</section>
<script>
    let i = <?= $i + $j + $k + 1?>;

    $('#add_general').click(function () {
        i++;
        $('#dyn_general').append('<tr id="row' + i + '" class="dynamic-added uk-animation-slide-left-small"><td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="general' + i + '" name="general" placeholder="General Changelog" required></td><td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove"><button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="' + i + '"><i class="fa-solid fa-minus"></i></i></button></td></tr>');
    });
    $('#add_pve').click(function () {
        i++;
        $('#dyn_pve').append('<tr id="row' + i + '" class="dynamic-added uk-animation-scale-up""><td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="pve" name="pve" placeholder="PvE Based Changelog" required></td><td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove"><button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="' + i + '"><i class="fa-solid fa-minus"></i></i></button></td></tr>');
    });
    $('#add_pvp').click(function () {
        i++;
        $('#dyn_pvp').append('<tr id="row' + i + '" class="dynamic-added uk-animation-slide-right-small"><td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="pvp" name="pvp" placeholder="PvP Based Changelog" required></td><td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove"><button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="' + i + '"><i class="fa-solid fa-minus"></i></i></button></td></tr>');
    });

    $(document).on('click', '.btn_remove', function () {
        let button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });

    function timelineForm(e) {
        e.preventDefault();

        let formData = new FormData($("form#timelineForm")[0]);
        formData.delete('description');

        let description = tinymce.get('description').getContent();
        if (description == '') {
            $.amaran({
                'theme': 'awesome error',
                'content': {
                    title: '<?= $this->lang->line('notification_title_error'); ?>',
                    message: 'Description cannot be empty.',
                    info: '',
                    icon: 'fas fa-times-circle'
                },
                'delay': 5000,
                'position': 'top right',
                'inEffect': 'slideRight',
                'outEffect': 'slideRight'
            });
            return false;
        } else {
            formData.append('description', tinymce.get('description').getContent());
        }

        formData.append('id', <?= $id; ?>);

        $('input[id^="general"]').each(function (input) {
            let value = $(this).val();
            formData.append('general[]', value);
        });

        $('input[id^="pve"]').each(function (input) {
            let value = $(this).val();
            formData.append('pve[]', value);
        });

        $('input[id^="pvp"]').each(function (input) {
            let value = $(this).val();
            formData.append('pvp[]', value);
        });

        formData.delete('general');
        formData.delete('pve');
        formData.delete('pvp');

        $.ajax({
            url: '<?= base_url($lang . '/admin/timeline/update'); ?>',
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data == 'true') {
                    $.amaran({
                        'theme': 'awesome ok',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_success'); ?>',
                            message: 'Timeline item updated successfully.',
                            info: '',
                            icon: 'fas fa-check-circle'
                        },
                        'delay': 2000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    $.amaran({
                        'theme': 'awesome error',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_error'); ?>',
                            message: data,
                            info: '',
                            icon: 'fas fa-times-circle'
                        },
                        'delay': 2500,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
</script>
<?= $tiny ?>