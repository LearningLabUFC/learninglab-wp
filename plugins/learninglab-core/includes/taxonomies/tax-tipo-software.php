<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function registrar_tax_tipo_software() {
    $labels = array(
        'name'          => 'Tipos de Software',
        'singular_name' => 'Tipo de Software',
        'search_items'  => 'Buscar Tipos',
        'all_items'     => 'Todos os Tipos',
        'edit_item'     => 'Editar Tipo',
        'add_new_item'  => 'Adicionar Novo Tipo',
        'menu_name'     => 'Tipos',
    );

    register_taxonomy( 'tipo_software', 'software', array(
        'labels'       => $labels,
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'tipo-software' ),
    ) );
}

add_action( 'init', 'registrar_tax_tipo_software' );