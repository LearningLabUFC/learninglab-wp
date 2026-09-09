<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_cpt_membros() {
    $labels = array(
        'name'               => 'Membros',
        'singular_name'      => 'Membro',
        'menu_name'          => 'Membros',
        'name_admin_bar'     => 'Membro',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Membro',
        'new_item'           => 'Novo Membro',
        'edit_item'          => 'Editar Membro',
        'view_item'          => 'Ver Membro',
        'all_items'          => 'Todos os Membros',
        'search_items'       => 'Procurar Membros',
        'not_found'          => 'Nenhum membro encontrado.',
        'not_found_in_trash' => 'Nenhum membro encontrado na lixeira.',
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_position' => 20,
        'menu_icon'     => 'dashicons-groups',
    );

    register_post_type( 'membro', $args );
}

add_action( 'init', 'registrar_cpt_membros' );
