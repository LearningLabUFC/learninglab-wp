<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_cpt_cursos() {
    $labels = array(
        'name'               => 'Cursos',
        'singular_name'      => 'Curso',
        'menu_name'          => 'Cursos',
        'name_admin_bar'     => 'Curso',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Curso',
        'new_item'           => 'Novo Curso',
        'edit_item'          => 'Editar Curso',
        'view_item'          => 'Ver Curso',
        'all_items'          => 'Todos os Cursos',
        'search_items'       => 'Buscar Cursos',
        'not_found'          => 'Nenhum curso encontrado.',
        'not_found_in_trash' => 'Nenhum curso encontrado na lixeira.',
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'show_in_rest'  => true,
        'has_archive'   => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-welcome-learn-more',
        'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'rewrite'       => array( 'slug' => 'cursos' ),
    );

    register_post_type( 'curso', $args );
}

add_action( 'init', 'registrar_cpt_cursos' );
