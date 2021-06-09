<?php get_header(); ?>

<main class="container my-3">
  <?php
  if (have_posts()) {
    while (have_posts()) {
      the_post();
  ?>
      <h1 class="my-3">
        <?php the_title(); ?>
      </h1>
      <div class="row">
        <div class="col-6">
          <?php the_post_thumbnail('large'); ?>
        </div>
        <div class="col-6">
          <?php the_content(); ?>
        </div>
      </div>
      <?php
      $args = array(
        'post_type' => 'product',
        'post_per_page' => 6,
        'order' => 'ASC',
        'orderby' => 'title'
      );
      $products = new WP_Query($args);
      ?>
      <?php if ($products->have_posts()) { ?>
        <h3 class="text-center">Productos relacionados</h3>
        <div class="row justify-content-center productos-relacionados">
          <?php while ($products->have_posts()) { ?>
            <?php $products->the_post(); ?>
            <div class="col-2 my-3 text-center">
              <?php the_post_thumbnail('thumbnail'); ?>
              <h4>
                <a href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
              </h4>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
  <?php
    }
  }
  ?>
</main>

<?php get_footer(); ?>