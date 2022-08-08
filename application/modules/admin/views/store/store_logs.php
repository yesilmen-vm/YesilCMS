<div class="uk-container uk-margin">
    <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
        <div class="uk-width-expand uk-heading-line">
            <h3 class="uk-h3"><i class="fas fa-store"></i> <?= $this->lang->line('admin_nav_store_logs') ?></h3>
        </div>
        <div class="uk-width-auto">
            <a href="#" class="uk-icon-button"><i class="fas fa-info"></i></a>
        </div>
    </div>
    <div class="uk-card uk-card-default uk-card-body">
        <table class="uk-table uk-table-middle uk-table-divider uk-table-small">
            <thead>
                <tr>
                    <th>Purchase ID</th>
                    <th>Account ID</th>
                    <th>Char ID</th>
                    <th>Item</th>
                    <th>Price DP</th>
                    <th>Price VP</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stores as $stores) : ?>
                    <tr>
                        <td><?= $stores->id; ?></td>
                        <td><?= $stores->accountid; ?></td>
                        <td><?= $stores->charid; ?></td>
                        <td><?= $stores->item_name; ?></td>
                        <td><?= $stores->dp; ?></td>
                        <td><?= $stores->vp; ?></td>
                        <td><?= $stores->date; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>