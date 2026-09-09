<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_cpt_artigos() {
    $labels = array(
        'name'               => 'Artigos',
        'singular_name'      => 'Artigo',
        'menu_name'          => 'Artigos',
        'name_admin_bar'     => 'Artigo',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Artigo',
        'new_item'           => 'Novo Artigo',
        'edit_item'          => 'Editar Artigo',
        'view_item'          => 'Ver Artigo',
        'all_items'          => 'Todos os Artigos',
        'search_items'       => 'Procurar Artigos',
        'not_found'          => 'Nenhum artigo encontrado.',
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'menu_position' => 20,
        'menu_icon'     => 'dashicons-media-document',
    );

    register_post_type( 'artigo', $args );
}

add_action( 'init', 'registrar_cpt_artigos' );
