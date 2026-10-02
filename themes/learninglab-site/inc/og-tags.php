<?php 



function add_custom_og_tags() {
    if (is_front_page() || is_home()) {
		echo '<meta property="og:title" content="LearningLab - Projeto de extensão da UFC Campus Russas" />' . "\n";
		echo '<meta property="og:description" content="Laboratório de Ensino, Pesquisa, Extensão e Desenvolvimento de Tecnologias Alinhadas à Gestão do Conhecimento e Inovação em Processos de Software." />' . "\n";
        echo '<meta property="og:image" content="' . esc_url(get_theme_mod('og_default_image', get_template_directory_uri() . '/assets/images/og-default.png')) . '" />' . "\n";
        echo '<meta property="og:url" content="https://learninglab.com.br/" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr(wp_strip_all_tags(get_the_title())) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(wp_strip_all_tags(get_the_excerpt())) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url(get_the_post_thumbnail_url()) . '" />' . "\n";
}

add_action('wp_head', 'add_custom_og_tags');