<section class="uk-section uk-section-xsmall" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
            <div class="uk-width-expand uk-heading-line">
                <h3 class="uk-h3"><i class="fas fa-plus-circle"></i> <?= $this->lang->line('placeholder_create_timeline'); ?></h3>
            </div>
            <div class="uk-width-auto">
                <a href="<?= base_url('admin/timeline'); ?>" class="uk-icon-button"><i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>
        <div class="uk-card uk-card-default">
            <div class="uk-card-body">
                <?= form_open_multipart('', 'id="timelineForm" onsubmit="timelineForm(event)"'); ?>
                <div class="uk-margin-small">
                    <div class="uk-grid uk-grid-small" data-uk-grid>
                        <div class="uk-inline uk-width-1-2@s">
                            <label class="uk-form-label"><?= $this->lang->line('placeholder_patch'); ?></label>
                            <div class="uk-form-controls">
                                <select class="uk-select" id="patch" name="patch">
                                    <option value="0.3.4">0.3.4</option>
                                    <option value="0.4.0">0.4.0</option>
                                    <option value="0.5.3">0.5.3</option>
                                    <option value="0.5.4">0.5.4</option>
                                    <option value="0.5.5">0.5.5</option>
                                    <option value="0.6">0.6</option>
                                    <option value="0.7">0.7</option>
                                    <option value="0.7.1">0.7.1</option>
                                    <option value="0.7.2">0.7.2</option>
                                    <option value="0.8">0.8</option>
                                    <option value="0.9">0.9</option>
                                    <option value="0.9.1">0.9.1</option>
                                    <option value="0.10">0.10</option>
                                    <option value="0.11">0.11</option>
                                    <option value="0.12">0.12</option>
                                    <option value="1.1.0">1.1.0</option>
                                    <option value="1.1.1">1.1.1</option>
                                    <option value="1.1.2">1.1.2</option>
                                    <option value="1.2.0">1.2.0</option>
                                    <option value="1.2.1">1.2.1</option>
                                    <option value="1.2.2">1.2.2</option>
                                    <option value="1.2.3">1.2.3</option>
                                    <option value="1.2.4">1.2.4</option>
                                    <option value="1.3.0">1.3.0</option>
                                    <option value="1.3.1">1.3.1</option>
                                    <option value="1.4.0">1.4.0</option>
                                    <option value="1.4.1">1.4.1</option>
                                    <option value="1.4.2">1.4.2</option>
                                    <option value="1.5.0">1.5.0</option>
                                    <option value="1.5.1">1.5.1</option>
                                    <option value="1.6.0">1.6.0</option>
                                    <option value="1.6.1">1.6.1</option>
                                    <option value="1.7.0">1.7.0</option>
                                    <option value="1.7.1">1.7.1</option>
                                    <option value="1.8.0">1.8.0</option>
                                    <option value="1.8.1">1.8.1</option>
                                    <option value="1.8.2">1.8.2</option>
                                    <option value="1.8.3">1.8.3</option>
                                    <option value="1.8.4">1.8.4</option>
                                    <option value="1.9.0">1.9.0</option>
                                    <option value="1.9.1">1.9.1</option>
                                    <option value="1.9.2">1.9.2</option>
                                    <option value="1.9.3">1.9.3</option>
                                    <option value="1.9.4">1.9.4</option>
                                    <option value="1.10.0">1.10.0</option>
                                    <option value="1.10.1">1.10.1</option>
                                    <option value="1.10.2">1.10.2</option>
                                    <option value="1.11.0">1.11.0</option>
                                    <option value="1.11.1">1.11.1</option>
                                    <option value="1.11.2">1.11.2</option>
                                    <option value="1.12.0">1.12.0</option>
                                    <option value="1.12.1">1.12.1</option>
                                    <option value="1.12.2">1.12.2</option>
                                    <option value="1.12.3">1.12.3</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-inline uk-width-1-2@s">
                            <label class="uk-form-label"><?= $this->lang->line('placeholder_date'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="date" id="date" name="date" onfocus="this.showPicker()" required pattern="\d{4}-\d{2}-\d{2}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-small">
                    <label class="uk-form-label"><?= $this->lang->line('placeholder_description'); ?></label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea tinyeditor" id="description" name="description" rows="3" maxlength="10000"></textarea>
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
                                    <td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="general" name="general" placeholder="General Changelog"></td>
                                    <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                        <button class="uk-button uk-button-default" type="button" name="add_general" id="add_general"><i class="fa-solid fa-plus"></i></button>
                                    </td>
                                </tr>
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
                                    <td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="pve" name="pve" placeholder="PvE Based Changelog"></td>
                                    <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                        <button class="uk-button uk-button-default" type="button" name="add_pve" id="add_pve"><i class="fa-solid fa-plus"></i></button>
                                    </td>
                                </tr>
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
                                    <td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="pvp" name="pvp" placeholder="PvP Based Changelog"></td>
                                    <td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove">
                                        <button class="uk-button uk-button-default" type="button" name="add_pvp" id="add_pvp"><i class="fa-solid fa-plus"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-small">
                    <div class="uk-grid uk-grid-small" data-uk-grid>
                        <div class="uk-inline uk-width-3-5@s">
                            <label class="uk-form-label">Image</label>
                            <div class="uk-form-controls">
                                <div uk-form-custom="target: true">
                                    <input type="file" id="image_file" name="image_file">
                                    <input class="uk-input uk-form-width-large" type="text" placeholder="Upload Image (allowed: jpg,jpeg,png)" disabled>
                                    <button class="uk-button uk-button-primary" type="button" tabindex="-1"><i class="fas fa-file-upload"></i> <?= $this->lang->line('button_select'); ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="uk-inline uk-width-2-5@s">
                            <label class="uk-form-label">Order</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="number" min="0" step="1" id="order" name="order" placeholder="Timeline Order" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-margin-small">
                    <button class="uk-button uk-button-primary uk-width-1-1" type="submit" id="button_slide"><i class="fas fa-check-circle"></i> <?= $this->lang->line('button_create'); ?></button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</section>
<script>
    let i = 1;

    $('#add_general').click(function () {
        i++;
        $('#dyn_general').append('<tr id="row' + i + '" class="dynamic-added uk-animation-slide-left-small"><td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="general' + i + '" name="general" placeholder="General Changelog" required></td><td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove"><button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="' + i + '"><i class="fa-solid fa-minus"></i></i></button></td></tr>');
    });
    $('#add_pve').click(function () {
        i++;
        $('#dyn_pve').append('<tr id="row' + i + '" class="dynamic-added uk-animation-scale-up"><td class="uk-padding-remove-horizontal"><input class="uk-input" type="text" id="pve" name="pve" placeholder="PvE Based Changelog" required></td><td class="uk-padding-remove-horizontal uk-align-right uk-margin-remove"><button class="uk-button uk-button-default btn_remove" type="button" name="remove" id="' + i + '"><i class="fa-solid fa-minus"></i></i></button></td></tr>');
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

        if ($('#image_file').get(0).files.length === 0) {
            $.amaran({
                'theme': 'awesome error',
                'content': {
                    title: '<?= $this->lang->line('notification_title_error'); ?>',
                    message: 'Please upload file.',
                    info: '',
                    icon: 'fas fa-times-circle'
                },
                'delay': 2500,
                'position': 'top right',
                'inEffect': 'slideRight',
                'outEffect': 'slideRight'
            });
            return false;
        }

        $.ajax({
            url: '<?= base_url($lang . '/admin/timeline/add'); ?>',
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data == 'true') {
                    $.amaran({
                        'theme': 'awesome ok',
                        'content': {
                            title: '<?= $this->lang->line('notification_title_success'); ?>',
                            message: 'Timeline item created successfully.',
                            info: '',
                            icon: 'fas fa-check-circle'
                        },
                        'delay': 2000,
                        'position': 'top right',
                        'inEffect': 'slideRight',
                        'outEffect': 'slideRight'
                    });
                    setTimeout(function () {
                        window.location.replace("<?= base_url('admin/timeline'); ?>");
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
                    }, 2000);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
</script>
<?= $tiny ?>