<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_taxonomia_tipo_de_membro() {
    $labels = array(
        'name'              => 'Tipo de Membro',
        'singular_name'     => 'Tipo de Membro',
        'search_items'      => 'Procurar Tipos de Membro',
        'all_items'         => 'Todos os Tipos',
        'parent_item'       => 'Tipo Pai',
        'parent_item_colon' => 'Tipo Pai:',
        'edit_item'         => 'Editar Tipo',
        'update_item'       => 'Atualizar Tipo',
        'add_new_item'      => 'Adicionar Novo Tipo',
        'new_item_name'     => 'Novo Nome do Tipo',
        'menu_name'         => 'Tipo de Membro',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tipo-de-membro' ),
    );

    register_taxonomy( 'tipo_de_membro', array( 'membro' ), $args );
}

add_action( 'init', 'registrar_taxonomia_tipo_de_membro' );
