<script type="text/javascript" src="<?= base_url() . 'application/modules/timeline/assets/js/jquery.lazy.min.js'; ?>"></script>
<link rel="stylesheet" href="<?= base_url() . 'application/modules/timeline/assets/css/timeline.css'; ?>"/>
<div class="timeline-container" id="yesilcms-timeline">
    <div class="timeline-header">
        <h2 class="timeline-header-title"><?= $title ?></h2>
        <h3 class="timeline-header-subtitle"><?= strtoupper($this->lang->line('timeline_subtitle')) ?></h3>
    </div>
    <div class="timeline">
        <?php $i = 1;
        foreach ($timeline as $timeline_items): ?>
            <div class="timeline-item" data-text="Patch <?= $timeline_items['patch'] ?>">
                <div class="timeline-content">
                    <?php if ($i === 1) : ?>
                        <img class="timeline-img" src="/assets/images/timeline/<?= $timeline_items['image'] ?>"/>
                        <?php $i++;
                    else: ?>
                        <img class="timeline-img" data-src="/assets/images/timeline/<?= $timeline_items['image'] ?>"/>
                    <?php endif; ?>
                    <h2 class="timeline-content-title"><?= date('M jS, Y', strtotime($timeline_items['date'])); ?></h2>
                    <?php $description = json_decode($timeline_items['description'], true); ?>
                    <div class="timeline-content-desc">
                        <p><?= $description['description'] ?></p>
                        <?php if (array_filter($description['general'])) : ?>
                            <table class="uk-table uk-table-xsmall uk-table-divider uk-table-hover">
                                <thead>
                                <tr>
                                    <th class="uk-text-center"><?= $this->lang->line('timeline_general') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($description['general'] as $general) : ?>
                                    <tr>
                                        <td><i class="fa-solid fa-code-commit"></i> <?= $general ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        <?php if (array_filter($description['pve'])) : ?>
                            <table class="uk-table uk-table-xsmall uk-table-divider uk-table-hover">
                                <thead>
                                <tr>
                                    <th class="uk-text-center"><?= $this->lang->line('timeline_pve') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($description['pve'] as $pve) : ?>
                                    <tr>
                                        <td><i class="fa-solid fa-code-commit"></i> <?= $pve ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        <?php if (array_filter($description['pvp'])) : ?>
                            <table class="uk-table uk-table-xsmall uk-table-divider uk-table-hover">
                                <thead>
                                <tr>
                                    <th class="uk-text-center"><?= $this->lang->line('timeline_pvp') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($description['pvp'] as $pvp) : ?>
                                    <tr>
                                        <td><i class="fa-solid fa-code-commit"></i> <?= $pvp ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<script type="text/javascript" src="<?= base_url() . 'application/modules/timeline/assets/js/timeline.js'; ?>"></script>