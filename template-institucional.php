<?php
// Template Name: Página Institucional
get_header();
$fields = get_fields();
?>

<main class="container my-5">
  <?php if (have_posts()) { ?>
    <?php while (have_posts()) { ?>
      <?php the_post(); ?>
      <h1 class="my-3">
        <?php echo $fields['titulo']; ?>
      </h1>
      <img src="<?php echo $fields['imagen']; ?>" alt="Imagen de la página institucional">
      <hr>
      <?php the_content(); ?>
    <?php } ?>
  <?php } ?>
</main>

<?php get_footer(); ?>