<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_taxonomia_status_curso() {
    $labels = array(
        'name'              => 'Status do Curso',
        'singular_name'     => 'Status do Curso',
        'search_items'      => 'Procurar Status',
        'all_items'         => 'Todos os Status',
        'parent_item'       => 'Status Pai',
        'parent_item_colon' => 'Status Pai:',
        'edit_item'         => 'Editar Status',
        'update_item'       => 'Atualizar Status',
        'add_new_item'      => 'Adicionar Novo Status',
        'new_item_name'     => 'Novo Nome de Status',
        'menu_name'         => 'Status do Curso',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'status-do-curso' ),
    );

    register_taxonomy( 'status_curso', array( 'curso' ), $args );
}

add_action( 'init', 'registrar_taxonomia_status_curso' );
