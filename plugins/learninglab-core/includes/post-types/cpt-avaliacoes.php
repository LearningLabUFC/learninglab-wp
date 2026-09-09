<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function criar_cpt_avaliacoes() {
    $labels = array(
        'name'               => 'Avaliações',
        'singular_name'      => 'Avaliação',
        'menu_name'          => 'Avaliações',
        'name_admin_bar'     => 'Avaliação',
        'add_new'            => 'Adicionar Nova',
        'add_new_item'       => 'Adicionar Nova Avaliação',
        'new_item'           => 'Nova Avaliação',
        'edit_item'          => 'Editar Avaliação',
        'view_item'          => 'Ver Avaliação',
        'all_items'          => 'Todas as Avaliações',
        'search_items'       => 'Buscar Avaliações',
        'not_found'          => 'Nenhuma avaliação encontrada.',
        'not_found_in_trash' => 'Nenhuma avaliação encontrada na lixeira.',
    );

    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => false,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-format-quote',
        'supports'     => array( 'title', 'editor', 'thumbnail' ),
    );

    register_post_type( 'avaliacoes', $args );
}

add_action( 'init', 'criar_cpt_avaliacoes' );
