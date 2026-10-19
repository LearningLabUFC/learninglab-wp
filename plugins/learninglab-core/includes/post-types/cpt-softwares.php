<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_cpt_softwares() {
    $labels = array(
        'name'               => 'Softwares',
        'singular_name'      => 'Software',
        'menu_name'          => 'Softwares',
        'name_admin_bar'     => 'Software',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Software',
        'new_item'           => 'Novo Software',
        'edit_item'          => 'Editar Software',
        'view_item'          => 'Ver Software',
        'all_items'          => 'Todos os Softwares',
        'search_items'       => 'Buscar Softwares',
        'not_found'          => 'Nenhum software encontrado.',
        'not_found_in_trash' => 'Nenhum software encontrado na lixeira.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'softwares' ),
        'capability_type'    => 'post',
        'has_archive'        => false,  //page template
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-laptop',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'software', $args );
}

add_action( 'init', 'registrar_cpt_softwares' );