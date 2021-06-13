<?php

function init_template()
{
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');

  register_nav_menus(array('top_menu' => 'Menú Principal'));
}

add_action('after_setup_theme', 'init_template');

function assets()
{
  wp_register_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css', '', '5.0.1', 'all');
  wp_register_style('montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap', '', '1.0.0', 'all');

  wp_enqueue_style('styles', get_stylesheet_uri(), array('bootstrap', 'montserrat'), '1.0.0', 'all');

  wp_enqueue_script('jquery');
  wp_register_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js', '', '2.9.2', true);
  wp_register_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js', 'popper', '5.0.1', true);

  wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', '', '1.0.0', true);
  wp_localize_script('custom', 'pg', array(
    'ajaxurl' => admin_url('admin-ajax.php')
  ));
}

add_action('wp_enqueue_scripts', 'assets');

function sidebar()
{
  register_sidebar(array(
    'name' => 'Pie de página',
    'id' => 'footer',
    'description' => 'Zona de Widgets para pie de página',
    'before_title' => '<p>',
    'after_title' => '</p>',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>'
  ));
}

add_action('widgets_init', 'sidebar');

function products_type()
{
  $labels = array(
    'name' => 'Productos',
    'singular_name' => 'Producto',
    'menu_name' => 'Productos'
  );
  $args = array(
    'label' => 'Productos',
    'description' => 'Productos de Platzi',
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
    'public' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-cart',
    'can_export' => true,
    'publicly_queryable' => true,
    'rewrite' => true,
    'show_in_rest' => true
  );
  register_post_type('product', $args);
}

add_action('init', 'products_type');

function pgRegisterTax()
{
  $args = array(
    'hierarchical' => true,
    'labels' => array(
      'name' => 'Categorías de Productos',
      'singular_name' => 'Categoría de Productos'
    ),
    'show_in_nav_menu' => true,
    'show_admin_column' => true,
    'rewrite' => array('slug' => 'category-products')
  );
  register_taxonomy('category-products', array('product'), $args);
}

add_action('init', 'pgRegisterTax');

function pgFilterProducts()
{
  $args = array(
    'post_type' => 'product',
    'post_per_page' => -1,
    'order' => 'ASC',
    'orderby' => 'title'
  );

  if ($_POST['category']) {
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'category-products',
        'field' => 'slug',
        'terms' => $_POST['category']
      )
    );
  }

  $products = new WP_Query($args);

  if ($products->have_posts()) {
    $return = array();

    while ($products->have_posts()) {
      $products->the_post();
      $return[] = array(
        'image' => get_the_post_thumbnail(get_the_id(), 'large'),
        'link' => get_the_permalink(),
        'title' => get_the_title(),
      );
    }

    wp_send_json($return);
  }
}

add_action('wp_ajax_pgFilterProducts', 'pgFilterProducts');
add_action('wp_ajax_nopriv_pgFilterProducts', 'pgFilterProducts');

function pedidoNovedades($data)
{
  $args = array(
    'post_type' => 'post',
    'post_per_page' => $data['cantidad'],
    'order' => 'ASC',
    'orderby' => 'title'
  );

  $novedades = new WP_Query($args);

  if ($novedades->have_posts()) {
    $return = array();

    while ($novedades->have_posts()) {
      $novedades->the_post();
      $return[] = array(
        'image' => get_the_post_thumbnail(get_the_id(), 'large'),
        'link' => get_the_permalink(),
        'title' => get_the_title(),
      );
    }

    return $return;
  }
}

function novedadesAPI()
{
  register_rest_route(
    'pg/v1',
    '/novedades/(?P<cantidad>\d+)',
    array(
      'methods' => 'GET',
      'callback' => 'pedidoNovedades'
    )
  );
}

add_action('rest_api_init', 'novedadesAPI');
