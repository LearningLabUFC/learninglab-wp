<?php

function learninglab_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');

    // Suporte aos estilos do editor Gutenberg (aplica a fonte Montserrat)
    add_theme_support('editor-styles');
    add_editor_style('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'learninglab_theme_support');

function learninglab_block_author_enumeration()
{
    if (is_author() && isset($_GET['author'])) {
        wp_redirect(home_url('/'), 301);
        exit;
    }
}
add_action('template_redirect', 'learninglab_block_author_enumeration', 1);