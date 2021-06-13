<?php get_header(); ?>

<main class="container my-3">
  <?php if (have_posts()) { ?>
    <?php while (have_posts()) { ?>
      <?php the_post(); ?>
      <h1 class="my-3">
        <?php the_title(); ?>
      </h1>
      <div class="row">
        <div class="col-6">
          <?php the_post_thumbnail('large'); ?>
        </div>
        <div class="col-16">
          <?php the_content(); ?>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
</main>

<?php get_footer(); ?>