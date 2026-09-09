<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_taxonomia_evento_artigo() {
    $labels = array(
        'name'              => 'Eventos',
        'singular_name'     => 'Evento',
        'search_items'      => 'Buscar Eventos',
        'all_items'         => 'Todos os Eventos',
        'parent_item'       => 'Evento Pai',
        'parent_item_colon' => 'Evento Pai:',
        'edit_item'         => 'Editar Evento',
        'update_item'       => 'Atualizar Evento',
        'add_new_item'      => 'Adicionar Novo Evento',
        'new_item_name'     => 'Nome do Novo Evento',
        'menu_name'         => 'Eventos',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'evento-artigo' ),
    );

    register_taxonomy( 'evento_artigo', array( 'artigo' ), $args );
}

add_action( 'init', 'registrar_taxonomia_evento_artigo' );
