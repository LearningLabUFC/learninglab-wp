<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_cpt_subprojetos() {
    $labels = array(
        'name'          => 'Subprojetos',
        'singular_name' => 'Subprojeto',
        'menu_name'     => 'Subprojetos',
        'add_new'       => 'Adicionar Novo',
        'add_new_item'  => 'Adicionar Novo Subprojeto',
        'edit_item'     => 'Editar Subprojeto',
        'all_items'     => 'Todos os Subprojetos',
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'subprojetos' ),
        'capability_type'   => 'post',
        'has_archive'       => false,
        'hierarchical'      => false,
        'menu_position'     => 5,
        'menu_icon'         => 'dashicons-portfolio',
        'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'      => true,
    );

    register_post_type( 'subprojetos', $args );
}

add_action( 'init', 'registrar_cpt_subprojetos' );
