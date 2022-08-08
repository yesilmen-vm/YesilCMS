<div class="uk-container uk-margin">
    <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
        <div class="uk-width-expand uk-heading-line">
            <h3 class="uk-h3"><i class="fas fa-circle-dollar-to-slot"></i> <?= $this->lang->line('admin_nav_donate_logs') ?></h3>
        </div>
        <div class="uk-width-auto">
            <a href="#" class="uk-icon-button"><i class="fas fa-info"></i></a>
        </div>
    </div>
    <div class="uk-card uk-card-default uk-card-body">
        <table class="uk-table uk-table-middle uk-table-divider uk-table-small">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Payment ID</th>
                    <th>Hash</th>
                    <th>Total</th>
                    <th>Points</th>
                    <th>Create Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?= $donation->id; ?></td>
                        <td><?= $donation->user_id; ?></td>
                        <td><?= $donation->payment_id; ?></td>
                        <td><?= $donation->hash; ?></td>
                        <td><?= $donation->total; ?></td>
                        <td><?= $donation->points; ?></td>
                        <td><?= $donation->create_time; ?></td>
                        <td><?= $donation->status; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
