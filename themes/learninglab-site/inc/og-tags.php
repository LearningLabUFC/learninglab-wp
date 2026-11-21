<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function add_custom_og_tags() {
    // Recupera o conteúdo consultado, independentemente do Loop global.
    $post_id = get_queried_object_id();

    // Imagem padrão: prioriza a configuração do Customizer.
    $default_image = get_theme_mod( 'og_default_image' );

    if ( ! is_string( $default_image ) || '' === trim( $default_image ) ) {
        $default_image = get_template_directory_uri() . '/assets/images/logo-colorida.png';
    }

    $default_image = esc_url( $default_image );

    if ( '' === $default_image ) {
        $default_image = esc_url(
            get_template_directory_uri() . '/assets/images/logo-colorida.png'
        );
    }

    // Valores padrão para páginas sem conteúdo singular.
    $title       = get_bloginfo( 'name' );
    $description = get_bloginfo( 'description' );
    $image       = $default_image;
    $url         = home_url( '/' );
    $type        = 'website';

    $output_open_graph = false;

    // Página inicial e página do blog.
    if ( is_front_page() || is_home() ) {
        $title = 'LearningLab - Projeto de extensão da UFC Campus Russas';

        $description = 'Laboratório de Ensino, Pesquisa, Extensão e Desenvolvimento de Tecnologias Alinhadas à Gestão do Conhecimento e Inovação em Processos de Software.';

        if ( is_home() && ! is_front_page() ) {
            $posts_page_id = (int) get_option( 'page_for_posts' );
            $posts_page_url = $posts_page_id ? get_permalink( $posts_page_id ) : '';

            if ( $posts_page_url ) {
                $url = $posts_page_url;
            }
        }

        $output_open_graph = true;

    // Posts, páginas e todos os CPTs.
    } elseif ( is_singular() && $post_id ) {
        $title = get_the_title( $post_id );

        $description = get_the_excerpt( $post_id );

        // Fallback caso o resumo esteja vazio.
        if ( '' === trim( $description ) ) {
            $content = get_post_field( 'post_content', $post_id );
            $content = strip_shortcodes( $content );

            $description = wp_trim_words(
                wp_strip_all_tags( $content ),
                30,
                '...'
            );
        } else {
            $description = wp_trim_words(
                wp_strip_all_tags( $description ),
                30,
                '...'
            );
        }

        // Fallback final para a descrição do site.
        if ( '' === trim( $description ) ) {
            $description = get_bloginfo( 'description' );
        }

        // Imagem destacada ou imagem padrão.
        $thumbnail_url = get_the_post_thumbnail_url( $post_id, 'large' );

        if ( $thumbnail_url ) {
            $image = $thumbnail_url;
        }

        $permalink = get_permalink( $post_id );

        if ( $permalink ) {
            $url = $permalink;
        }

        // Páginas utilizam website; posts e CPTs utilizam article.
        $type = 'page' === get_post_type( $post_id ) ? 'website' : 'article';

        $output_open_graph = true;
    }

    // Limpeza final dos textos.
    $title       = wp_strip_all_tags( $title );
    $description = wp_strip_all_tags( $description );

    // Open Graph: página inicial e páginas individuais.
    if ( $output_open_graph ) {
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
    }

    // Twitter Cards: mantém as tags disponíveis em todas as páginas.
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
}

add_action( 'wp_head', 'add_custom_og_tags' );
