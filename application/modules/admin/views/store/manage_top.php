    <section class="uk-section uk-section-xsmall" data-uk-height-viewport="expand: true">
      <div class="uk-container">
        <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
          <div class="uk-width-expand uk-heading-line">
            <h3 class="uk-h3"><i class="fas fa-shopping-cart"></i> <?= $this->lang->line('admin_nav_manage_store'); ?></h3>
          </div>
          <div class="uk-width-auto">
            <a href="<?= base_url('admin/store/top/create'); ?>" class="uk-icon-button"><i class="fas fa-pen"></i></a>
          </div>
        </div>
        <div class="uk-grid uk-grid-small" data-uk-grid>
          <div class="uk-width-1-4@s">
            <div class="uk-card uk-card-secondary">
              <ul class="uk-nav uk-nav-default">
                <li><a href="<?= base_url('admin/store'); ?>"><i class="fas fa-tags"></i> <?= $this->lang->line('section_store_categories'); ?></a></li>
                <li><a href="<?= base_url('admin/store/items'); ?>"><i class="fas fa-boxes"></i> <?= $this->lang->line('section_store_items'); ?></a></li>
                <li class="uk-active"><a href="<?= base_url('admin/store/top'); ?>"><i class="fas fa-parachute-box"></i> <?= $this->lang->line('section_store_top'); ?></a></li>
              </ul>
            </div>
          </div>
          <div class="uk-width-3-4@s">
            <div class="uk-card uk-card-default uk-card-body">
              <div class="uk-overflow-auto">
                <table class="uk-table uk-table-middle uk-table-divider uk-table-small">
                  <thead>
                    <tr>
                      <th class="uk-width-medium"><?= $this->lang->line('table_header_name'); ?></th>
                      <th class="uk-width-medium"><?= $this->lang->line('placeholder_category'); ?></th>
                      <th class="uk-width-small uk-text-center"><?= $this->lang->line('table_header_actions'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($storetopList) && !empty($storetopList)): ?>
                    <?php foreach ($storetopList as $top): ?>
                    <tr>
                      <td><?= $this->admin_model->getItemSpecifyName($top->store_item); ?></td>
                      <td><?= $this->admin_model->getStoreCategoryName($this->admin_model->getItemSpecifyCategory($top->store_item)); ?></td>
                      <td>
                        <div class="uk-flex uk-flex-left uk-flex-center@m uk-margin-small">
                          <a href="<?= base_url('admin/store/top/edit/'.$top->id); ?>" class="uk-button uk-button-primary uk-margin-small-right"><i class="fas fa-edit"></i></a>
                          <button class="uk-button uk-button-danger" value="<?= $top->id ?>" id="button_delete<?= $top->id ?>" onclick="DeleteTop(event, this.value)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <div class="uk-card-footer">
                <div class="uk-text-right">
                  <?php if (isset($storetopList) && is_array($storetopList)) {
                      echo $pagination_links;
                  } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
    var csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash() ?>";

      function DeleteTop(e, value) {
        e.preventDefault();

        $.ajax({
          url:"<?= base_url($lang.'/admin/store/top/delete'); ?>",
          method:"POST",
          data:{value, [globalThis.csrfName]: globalThis.csrfHash},
          dataType:"text",
          beforeSend: function(){
            $.amaran({
              'theme': 'awesome info',
              'content': {
                title: '<?= $this->lang->line('notification_title_info'); ?>',
                message: '<?= $this->lang->line('notification_checking'); ?>',
                info: '',
                icon: 'fas fa-sign-in-alt'
              },
              'delay': 1000,
              'position': 'top right',
              'inEffect': 'slideRight',
              'outEffect': 'slideRight'
            });
          },
          success:function(response){
            if(!response)
              alert(response);

            if (response) {
              $.amaran({
                'theme': 'awesome ok',
                  'content': {
                  title: '<?= $this->lang->line('notification_title_success'); ?>',
                  message: '<?= $this->lang->line('notification_top_deleted'); ?>',
                  info: '',
                  icon: 'fas fa-check-circle'
                },
                'delay': 5000,
                'position': 'top right',
                'inEffect': 'slideRight',
                'outEffect': 'slideRight'
              });
            }
            window.location.replace("<?= base_url('admin/store/top'); ?>");
          }
        });
      }
    </script>
