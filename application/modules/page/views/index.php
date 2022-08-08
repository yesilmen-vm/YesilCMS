<section class="uk-section uk-section-xsmall uk-padding-remove slider-section">
    <div class="uk-background-cover header-height header-section"
         style="background-image: url('<?= base_url() . 'application/themes/yesilcms/assets/images/headers/' . HEADER_IMAGES[array_rand(HEADER_IMAGES)] . '.jpg'; ?>')"></div>
</section>
    <section class="uk-section uk-section-xsmall main-section" data-uk-height-viewport="expand: true">
      <div class="uk-container">
        <div class="uk-margin-remove-top uk-margin-medium-bottom">
          <div class="uk-grid uk-grid-small" data-uk-grid>
            <div class="uk-width-expand">
              <h4 class="uk-h1 uk-text-bold"><i class="far fa-file-alt"></i> <?= $this->page_model->getName($uri); ?></h1>
            </div>
            <div class="uk-width-auto">
              <p class="uk-text-small"><i class="far fa-clock"></i> <strong>Last Update:</strong>  <?= date('F j, Y', $this->page_model->getDate($uri)); ?></p>
            </div>
          </div>
        </div>
        <article class="uk-article">
          <div class="uk-card uk-card-default uk-card-body uk-margin-small">
            <?= $this->page_model->getDesc($uri); ?>
          </div>
        </article>
      </div>
    </section>
