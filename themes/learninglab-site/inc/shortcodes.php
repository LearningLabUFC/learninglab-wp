<?php

function learninglab_render_membros_grid($membros)
{
    if (!is_admin()) {
        wp_enqueue_style('learninglab_membro_style');
    }

    $membros_count = count($membros);
    $classe_limite = '';

    if ($membros_count >= 2 && $membros_count <= 5) {
        $classe_limite = ' limite-' . $membros_count;
    } elseif ($membros_count > 5) {
        $classe_limite = ' limite-mais-de-5';
    }

    $output = '<div class="membros-grid' . $classe_limite . '">';

    foreach ($membros as $membro) {
        $nome_completo = get_the_title($membro->ID);
        $partes = explode(' ', trim($nome_completo));
        if (count($partes) <= 1) {
            $nome_html = '<span class="membro-primeiro-nome">' . esc_html($nome_completo) . '</span><span class="membro-sobrenome">&nbsp;</span>';
        } else {
            $sobrenome = array_pop($partes);
            $primeiro_nomes = implode(' ', $partes);
            $nome_html = '<span class="membro-primeiro-nome">' . esc_html($primeiro_nomes) . '</span><span class="membro-sobrenome">' . esc_html($sobrenome) . '</span>';
        }

        $imagem = get_the_post_thumbnail($membro->ID, 'thumbnail', array('class' => 'attachment-thumbnail size-thumbnail wp-post-image'));

        $output .= '<div class="membro-item">';
        if ($imagem) {
            $output .= '<div class="membro-avatar">' . $imagem . '</div>';
        }
        $output .= '<h4 class="membro-nome">' . $nome_html . '</h4>';
        $output .= '</div>';
    }

    return $output . '</div>';
}

function membros_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'slugs' => '',
        ),
        $atts,
        'membros'
    );

    $slugs = array_map('trim', explode(',', $atts['slugs']));

    if (empty($slugs)) {
        return '';
    }

    $membros = array();

    foreach ($slugs as $slug) {
        if (empty($slug)) continue;

        $args = array(
            'name'        => $slug,
            'post_type'   => 'membro',
            'post_status' => 'publish',
            'numberposts' => 1,
        );
        $resultado = get_posts($args);

        if ($resultado) {
            $membros[] = $resultado[0];
        }
    }

    return learninglab_render_membros_grid($membros);
}
add_shortcode('membros', 'membros_shortcode');

function membros_categoria_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'categoria' => '',
            'tipo' => 'atuais',
            'coordenadora' => 'false',
        ),
        $atts,
        'membros_categoria'
    );

    $categoria = sanitize_title($atts['categoria']);
    $slug_lider_categoria = 'lider-' . $categoria;
    $incluir_coordenadora = filter_var($atts['coordenadora'], FILTER_VALIDATE_BOOLEAN);
    $tipos_permitidos = array('atuais', 'lideres');

    if ($categoria === '' || ($atts['tipo'] !== 'todos' && !in_array($atts['tipo'], $tipos_permitidos, true))) {
        return '';
    }

    $tax_query_categoria = array(
        array(
            'taxonomy' => 'tipo_de_membro',
            'field' => 'slug',
            'terms' => $categoria,
            'include_children' => true,
        ),
    );

    $tax_query_atuais = array(
        'relation' => 'AND',
        $tax_query_categoria[0],
        array(
            'taxonomy' => 'tipo_de_membro',
            'field' => 'slug',
            'terms' => 'membro-atual',
            'include_children' => true,
        ),
    );

    // Compatível tanto com a associação por categoria + "lider" quanto com
    // a convenção de termo "lider-{categoria}".
    $tax_query_lideres_por_categoria = array(
        'relation' => 'AND',
        $tax_query_categoria[0],
        array(
            'taxonomy' => 'tipo_de_membro',
            'field' => 'slug',
            'terms' => 'lider',
            'include_children' => true,
        ),
    );

    $tax_query_lideres_por_slug = array(
        array(
            'taxonomy' => 'tipo_de_membro',
            'field' => 'slug',
            'terms' => $slug_lider_categoria,
            'include_children' => false,
        ),
    );

    $args_membros = array(
        'post_type' => 'membro',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $normalizar_membros = function ($membros) {
        $membros_por_id = array();
        foreach ($membros as $membro) {
            $membros_por_id[$membro->ID] = $membro;
        }
        $membros_normalizados = array_values($membros_por_id);

        usort($membros_normalizados, function ($primeiro_membro, $segundo_membro) {
            return strnatcasecmp($primeiro_membro->post_title, $segundo_membro->post_title);
        });

        return $membros_normalizados;
    };

    $membros_atuais = get_posts(array_merge($args_membros, array('tax_query' => $tax_query_atuais)));
    $membros_lideres = $normalizar_membros(array_merge(
        get_posts(array_merge($args_membros, array('tax_query' => $tax_query_lideres_por_categoria))),
        get_posts(array_merge($args_membros, array('tax_query' => $tax_query_lideres_por_slug)))
    ));

    $ids_lideres = wp_list_pluck($membros_lideres, 'ID');
    $membros_atuais = array_values(array_filter($membros_atuais, function ($membro) use ($ids_lideres) {
        return !in_array($membro->ID, $ids_lideres, true);
    }));

    if ($atts['tipo'] === 'todos') {
        $membros = $normalizar_membros(array_merge($membros_atuais, $membros_lideres));
    } elseif ($atts['tipo'] === 'lideres') {
        $membros = $membros_lideres;
    } else {
        $membros = $membros_atuais;
    }

    if ($incluir_coordenadora) {
        $coordenadoras = get_posts(array(
            'post_type' => 'membro',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'title',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'tipo_de_membro',
                    'field' => 'slug',
                    'terms' => 'coordenador',
                ),
            ),
        ));

        if ($coordenadoras) {
            $coordenadora = $coordenadoras[0];

            // A coordenadora sempre aparece primeiro e não é repetida no grid.
            $membros = array_values(array_filter($membros, function ($membro) use ($coordenadora) {
                return $membro->ID !== $coordenadora->ID;
            }));
            array_unshift($membros, $coordenadora);
        }
    }

    return $membros ? learninglab_render_membros_grid($membros) : '';
}
add_shortcode('membros_categoria', 'membros_categoria_shortcode');



function instrutor_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(array(
        'slug' => '',
        'nome' => '',
        'imagem' => '',
        'descricao' => ''
    ), $atts, 'instrutor');

    if (!empty($atts['slug'])) {
        $args = array(
            'name'        => $atts['slug'],
            'post_type'   => 'membro',
            'post_status' => 'publish',
            'numberposts' => 1,
        );
        $membros = get_posts($args);

        if ($membros) {
            $membro = $membros[0];
            if (empty($atts['nome'])) {
                $atts['nome'] = get_the_title($membro->ID);
            }
            if (empty($atts['imagem'])) {
                $atts['imagem'] = get_the_post_thumbnail_url($membro->ID, 'full');
            }
            if (empty($atts['descricao'])) {
                $atts['descricao'] = get_the_excerpt($membro->ID);
            }
        }
    }

    // Valores padrão se ainda estiverem vazios
    if (empty($atts['nome'])) {
        $atts['nome'] = 'Nome do Instrutor';
    }

    if (!empty($atts['imagem']) && !filter_var($atts['imagem'], FILTER_VALIDATE_URL)) {
        $img = get_page_by_title($atts['imagem'], OBJECT, 'attachment');
        if ($img) {
            $atts['imagem'] = wp_get_attachment_url($img->ID);
        }
    }

    ob_start();
?>
    <div class="instrutor-bloco">
        <?php if ($atts['imagem']) : ?>
            <div class="instrutor-imagem">
                <img src="<?php echo esc_url($atts['imagem']); ?>" alt="<?php echo esc_attr($atts['nome']); ?>">
            </div>
        <?php endif; ?>
        <div class="instrutor-info">
            <h2><?php echo esc_html($atts['nome']); ?></h2>
            <p><?php echo esc_html($atts['descricao']); ?></p>
        </div>
    </div>
<?php
    return ob_get_clean();
}

add_shortcode('instrutor', 'instrutor_shortcode');

function color_box_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'text'     => '28 artigos publicados',
            'bg_color' => '#f2637e',
            'color'    => 'white',
        ),
        $atts,
        'caixa_destaque'
    );

    $palette = array(
        'rosa'  => '#f2637e',
        'f2637e' => '#f2637e',
        '#f2637e' => '#f2637e',
        'roxo'  => '#9747ff',
        '9747ff' => '#9747ff',
        '#9747ff' => '#9747ff',
        'verde' => '#04BFBF',
        '04bfbf' => '#04BFBF',
        '#04bfbf' => '#04BFBF',
        '#04BFBF' => '#04BFBF',
        'azul'  => '#0b67c6',
        '0b67c6' => '#0b67c6',
        '#0b67c6' => '#0b67c6',
        '#0B67C6' => '#0b67c6',
    );

    $raw_bg = strtolower(trim((string) $atts['bg_color']));
    $raw_color = strtolower(trim((string) $atts['color']));

    $used_color_as_bg = false;
    if (isset($palette[$raw_bg])) {
        $bg_color = $palette[$raw_bg];
    } elseif (isset($palette[$raw_color]) && ($raw_bg === '' || $raw_bg === '#f2637e' || $raw_bg === 'f2637e')) {
        $bg_color = $palette[$raw_color];
        $used_color_as_bg = true;
    } else {
        $bg_color = '#f2637e';
    }

    $text_color = $used_color_as_bg ? 'white' : esc_attr($atts['color']);

    $style_container = "background-color: {$bg_color}; min-height: 13.5rem; display: flex; align-items: center; justify-content: center; border-radius: 15px;";
    $style_text = "margin-bottom: 0; font-weight: bold; padding: 2rem; color: {$text_color} !important; text-align: center;";
    $output = '<div class="custom-box" style="' . $style_container . '">';
    $output .= '<p style="' . $style_text . '">' . esc_html($atts['text']) . '</p>';
    $output .= '</div>';

    return $output;
}
add_shortcode('caixa_destaque', 'color_box_shortcode');
