<?php
if (isset($_POST['changePriory'])) :
    $value = $_POST['prioryValue'];
    $this->bugtracker_model->changePriority($idlink, $value);
    $message = "The ticket priority was successfully changed.";
endif;

if (isset($_POST['changeStatus'])) :
    $value = $_POST['StatusValue'];
    $this->bugtracker_model->changeStatus($idlink, $value);
    $message = "The ticket status was successfully changed.";
endif;

if (isset($_POST['changetypes'])) :
    $value = $_POST['typesValue'];
    $this->bugtracker_model->changeType($idlink, $value);
    $message = "The ticket type was successfully changed.";

endif;

if (isset($_POST['btn_closeBugtracker'])) :
    $this->bugtracker_model->closeIssue($idlink);
    $message = "The ticket was successfully closed.";
endif; ?>
<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section" style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
    <div class="uk-container">
        <div class="uk-grid uk-grid-medium" data-uk-grid>
            <div class="uk-width-1-4@m">
                <ul class="uk-nav uk-nav-default myaccount-nav">
                    <?php if ($this->wowmodule->getUCPStatus() == '1') : ?>
                        <li><a href="<?= base_url('panel'); ?>"><i class="fas fa-user-circle"></i> <?= $this->lang->line('tab_account'); ?></a></li>
                    <?php endif; ?>
                    <li class="uk-nav-divider"></li>
                    <?php if ($this->wowmodule->getDonationStatus() == '1') : ?>
                        <li><a href="<?= base_url('donate'); ?>"><i class="fas fa-hand-holding-usd"></i> <?= $this->lang->line('navbar_donate_panel'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getVoteStatus() == '1') : ?>
                        <li><a href="<?= base_url('vote'); ?>"><i class="fas fa-vote-yea"></i> <?= $this->lang->line('navbar_vote_panel'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getStoreStatus() == '1') : ?>
                        <li><a href="<?= base_url('store'); ?>"><i class="fas fa-store"></i> <?= $this->lang->line('tab_store'); ?></a></li>
                    <?php endif; ?>
                    <li class="uk-nav-divider"></li>
                    <?php if ($this->wowmodule->getBugtrackerStatus() == '1') : ?>
                        <li class="uk-active"><a href="<?= base_url('bugtracker'); ?>"><i class="fas fa-bug"></i> <?= $this->lang->line('tab_bugtracker'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->wowmodule->getChangelogsStatus() == '1') : ?>
                        <li><a href="<?= base_url('changelogs'); ?>"><i class="fas fa-scroll"></i> <?= $this->lang->line('tab_changelogs'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="uk-width-3-4@m">
                <?php if (isset($message) && !empty($message)) : ?>
                    <div class="uk-alert-success" id="ffsa" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p><?= $message ?></p>
                    </div>
                    <script>
                        $(".uk-alert-success").delay(3000).slideUp(200, function() {
                            UIkit.alert(".uk-alert-success").close();
                        });
                    </script>
                <?php endif; ?>
                <div class="uk-card uk-card-default uk-margin-small">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small" data-uk-grid>
                            <div class="uk-width-expand@s">
                                <h5 class="uk-h5 uk-text-bold"><i class="fas fa-bug"></i> <?= $this->bugtracker_model->getTitleIssue($idlink); ?></h5>
                            </div>
                            <div class="uk-width-auto@s">
                                <p class="uk-text-small"><i class="far fa-clock"></i> <?= date('F j, Y, h:i a', $this->bugtracker_model->getDate($idlink)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-grid uk-grid-small" data-uk-grid>
                            <div class="uk-width-3-4@s">
                                <?= $this->bugtracker_model->getDescIssue($idlink); ?>
                            </div>
                            <div class="uk-width-1-4@s">
                                <ul class="uk-list uk-text-small">
                                    <li><i class="far fa-user-circle"></i> <?= $this->lang->line('table_header_author'); ?>: <?= $this->wowauth->getUsernameID($this->bugtracker_model->getAuthor($idlink)); ?></li>
                                    <li><i class="fas fa-list"></i> <?= $this->lang->line('placeholder_type'); ?>: <span class="uk-label"><?= $this->bugtracker_model->getType($this->bugtracker_model->getTypeID($idlink)); ?></span></li>
                                    <li><i class="fas fa-exclamation-circle"></i> <?= $this->lang->line('table_header_priority'); ?>: <span class="uk-label uk-label-danger"><?= $this->bugtracker_model->getPriority($this->bugtracker_model->getPriorityID($idlink)); ?></span></li>
                                    <li><i class="fas fa-tags"></i> <?= $this->lang->line('table_header_status'); ?>: <span class="uk-label uk-label-success"><?= $this->bugtracker_model->getStatus($this->bugtracker_model->getStatusID($idlink)); ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <?php if ($this->wowauth->getRank($this->session->userdata('wow_sess_id')) > 0) : ?>
                    <div class="uk-grid uk-grid-small uk-grid-divider uk-child-width-1-1 uk-child-width-1-3@m uk-margin-small" data-uk-grid>
                        <div>
                            <?= form_open(''); ?>
                            <div class="uk-light">
                                <div class="uk-form-controls">
                                    <select class="uk-select uk-width-1-1" id="form-stacked-select" name="prioryValue">
                                        <?php foreach ($this->bugtracker_model->getPriorityGeneral()->result() as $priory) : ?>
                                            <option value="<?= $priory->id ?>"><?= $priory->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="uk-margin-small">
                                <button class="uk-button uk-button-default uk-width-1-1" type="submit" name="changePriory"><i class="fas fa-sync-alt"></i> <?= $this->lang->line('button_save_changes'); ?></button>
                            </div>
                            <?= form_close() ?>
                        </div>
                        <div>
                            <?= form_open(''); ?>
                            <div class="uk-light">
                                <div class="uk-form-controls">
                                    <select class="uk-select uk-width-1-1" id="form-stacked-select" name="StatusValue">
                                        <?php foreach ($this->bugtracker_model->getStatusGeneral()->result() as $priory) : ?>
                                            <option value="<?= $priory->id ?>"><?= $priory->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="uk-margin-small">
                                <button class="uk-button uk-button-default uk-width-1-1" type="submit" name="changeStatus"><i class="fas fa-sync-alt"></i> <?= $this->lang->line('button_save_changes'); ?></button>
                            </div>
                            <?= form_close() ?>
                        </div>
                        <div>
                            <?= form_open(''); ?>
                            <div class="uk-light">
                                <div class="uk-form-controls">
                                    <select class="uk-select uk-width-1-1" id="form-stacked-select" name="typesValue">
                                        <?php foreach ($this->bugtracker_model->getTypesGeneral()->result() as $priory) : ?>
                                            <option value="<?= $priory->id ?>"><?= $priory->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="uk-margin-small">
                                <button class="uk-button uk-button-default uk-width-1-1" type="submit" name="changetypes"><i class="fas fa-sync-alt"></i> <?= $this->lang->line('button_save_changes'); ?></button>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                    <div>
                        <div class="uk-margin-small">
                            <?= form_open('') ?>
                            <button type="submit" name="btn_closeBugtracker" class="uk-button uk-button-danger uk-width-1-1"><i class="fas fa-times-circle"></i> <?= $this->lang->line('button_close'); ?></button>
                            <?= form_close() ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>