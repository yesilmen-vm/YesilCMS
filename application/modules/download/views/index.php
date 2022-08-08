<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section"
         style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
<section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
	<div class="uk-container">
		<div class="uk-grid uk-grid-medium" data-uk-grid>
          <div class="uk-width-1-4@m">
            <ul class="uk-nav uk-nav-default myaccount-nav">
              <?php if ($this->wowmodule->getUCPStatus() == '1'): ?>
              <li><a href="<?= base_url('panel'); ?>"><i class="fas fa-user-circle"></i> <?= $this->lang->line('tab_account'); ?></a></li>
              <?php endif; ?>
              <li class="uk-nav-divider"></li>
              <?php if ($this->wowmodule->getDonationStatus() == '1'): ?>
              <li><a href="<?= base_url('donate'); ?>"><i class="fas fa-hand-holding-usd"></i> <?=$this->lang->line('navbar_donate_panel'); ?></a></li>
              <?php endif; ?>
              <?php if ($this->wowmodule->getVoteStatus() == '1'): ?>
              <li><a href="<?= base_url('vote'); ?>"><i class="fas fa-vote-yea"></i> <?=$this->lang->line('navbar_vote_panel'); ?></a></li>
              <?php endif; ?>
              <?php if ($this->wowmodule->getStoreStatus() == '1'): ?>
              <li><a href="<?= base_url('store'); ?>"><i class="fas fa-store"></i> <?=$this->lang->line('tab_store'); ?></a></li>
              <?php endif; ?>
              <li class="uk-nav-divider"></li>
              <?php if ($this->wowmodule->getBugtrackerStatus() == '1'): ?>
              <li><a href="<?= base_url('bugtracker'); ?>"><i class="fas fa-bug"></i> <?=$this->lang->line('tab_bugtracker'); ?></a></li>
              <?php endif; ?>
              <?php if ($this->wowmodule->getChangelogsStatus() == '1'): ?>
              <li><a href="<?= base_url('changelogs'); ?>"><i class="fas fa-scroll"></i> <?=$this->lang->line('tab_changelogs'); ?></a></li>
              <?php endif; ?>
              <?php if ($this->wowmodule->getDownloadStatus() == '1'): ?>
              <li class="uk-active"><a href="<?= base_url('download'); ?>"><i class="fas fa-download"></i> <?=$this->lang->line('tab_download'); ?></a></li>
              <?php endif; ?>
            </ul>
          </div>
			<div class="uk-width-3-4@m">
				<div class="uk-width-auto">
					<h4 class="uk-h4 uk-text-uppercase uk-text-bold"><i class="fas fa-download"></i> Download</h4>
				</div>
				<div class="uk-width-expand@s">
					<div class="uk-child-width-1-1" uk-grid>
						<div>
							<div uk-grid>
								<div class="uk-width-auto@m">
									<ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium">
										<li><a href="#">Client</a></li>
										<li><a href="#">Addons</a></li>
									</ul>
								</div>
								<div class="uk-width-expand@m">
									<ul id="component-tab-left" class="uk-switcher">
										<li>
											<table class="uk-table uk-table-middle uk-table-divider">
												<thead>
													<tr>
														<th class="uk-width-small">Version</th>
														<th class="uk-width-medium">Name</th>
														<th>Size</th>
														<th>Type</th>
														<th>Download</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($this->download_model->getGame()->result() as $files): ?>
													<tr class="pg-td">
														<td><div style="background:url(<?=base_url('assets/images/forums/wow-icons/' . $files->image);?>); width: 50px; height: 50px;)"></div></td>
														<td><?=$files->fileName?></td>
														<td><?=$files->weight?></td>
														<td><?=$files->type?></td>
														<td><a class="uk-button uk-label-success uk-button-small" href="<?=$files->url?>" target="_blank"><i class="fas fa-download"></i> Download</a></td>
													</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</li>
										<li>
											<table class="uk-table uk-table-middle uk-table-divider">
												<thead>
													<tr>
														<th class="uk-width-small">Version</th>
														<th class="uk-width-medium">Name</th>
														<th>Size</th>
														<th>Download</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($this->download_model->getAddons()->result() as $files): ?>
													<tr class="pg-td">
														<td><div style="background:url(<?=base_url('assets/images/forums/wow-icons/' . $files->image);?>); width: 50px; height: 50px;)"></div></td>
														<td><?=$files->fileName?></td>
														<td><?=$files->weight?></td>
														<td><a class="uk-button uk-label-success uk-button-small" href="<?=$files->url?>" target="_blank"><i class="fas fa-download"></i> Download</a></td>
													</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>