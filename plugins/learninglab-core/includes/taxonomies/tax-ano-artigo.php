<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_taxonomia_ano_artigo() {
    $labels = array(
        'name'              => 'Anos de Publicação',
        'singular_name'     => 'Ano',
        'search_items'      => 'Buscar Anos',
        'all_items'         => 'Todos os Anos',
        'parent_item'       => 'Ano Pai',
        'parent_item_colon' => 'Ano Pai:',
        'edit_item'         => 'Editar Ano',
        'update_item'       => 'Atualizar Ano',
        'add_new_item'      => 'Adicionar Novo Ano',
        'new_item_name'     => 'Novo Ano',
        'menu_name'         => 'Anos',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'ano-artigo' ),
    );

    register_taxonomy( 'ano_artigo', array( 'artigo' ), $args );
}

add_action( 'init', 'registrar_taxonomia_ano_artigo' );
