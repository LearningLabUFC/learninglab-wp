<?php

get_header();
?>

<div class="container-header">
    <div class="content-header">
        <div class="title">
            <h1>Conheça nossos membros</h1>
        </div>
    </div>
</div>

<div class="container">

    <section class="gestao-topo">
        <div class="membros-grid">
            <?php
            // 1. Coordenador
            $coord_query = new WP_Query([
                'post_type' => 'membro',
                'tax_query' => [
                    [
                        'taxonomy' => 'tipo_de_membro',
                        'field'    => 'slug',
                        'terms'    => 'coordenador', 
                    ],
                ],
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order'   => 'ASC',
            ]);
            
            if ($coord_query->have_posts()) :
                while ($coord_query->have_posts()) : $coord_query->the_post(); ?>
                    <?php
                    get_template_part('template-parts/content', 'membro', array(
                        'show_socials' => true,
                        'setor_html'   => '<div class="membro-setores"><span class="setor-coordenadora">Coordenadora</span></div>',
                    ));
                    ?>
                <?php endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="membros-grid row-administrativo" style="margin-top: -20px;">
            <?php
            // 2.A Cofundadoras (Primeiras na fila)
            $cofund_query = new WP_Query([
                'post_type' => 'membro',
                'tax_query' => [
                    [
                        'taxonomy' => 'tipo_de_membro',
                        'field'    => 'slug',
                        'terms'    => 'cofundadora',
                    ],
                ],
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order'   => 'ASC',
            ]);
            
            if ($cofund_query->have_posts()) :
                while ($cofund_query->have_posts()) : $cofund_query->the_post(); ?>
                    <?php
                    get_template_part('template-parts/content', 'membro', array(
                        'show_socials' => true,
                        'setor_html'   => '<div class="membro-setores"><span class="setor-cofundadora">Cofundadora</span></div>',
                    ));
                    ?>
                <?php endwhile;
            endif;
            wp_reset_postdata();

            // 2.B Administrativo (Logo em seguida)
            $admin_query = new WP_Query([
                'post_type' => 'membro',
                'tax_query' => [
                    [
                        'taxonomy' => 'tipo_de_membro',
                        'field'    => 'slug',
                        'terms'    => 'administrativo',
                    ],
                ],
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order'   => 'ASC',
            ]);
            
            if ($admin_query->have_posts()) :
                while ($admin_query->have_posts()) : $admin_query->the_post(); ?>
                    <?php
                    get_template_part('template-parts/content', 'membro', array(
                        'show_socials' => true,
                        'setor_html'   => '<div class="membro-setores"><span class="setor-administrativo">Administrativo</span></div>',
                    ));
                    ?>
                <?php endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </section>

    <section class="membros-lideres">
        <h2>Líderes</h2>

        <?php
        $lider_categoria = get_term_by('slug', 'lider', 'tipo_de_membro');

        if ($lider_categoria) {
            $lider_subcategorias = get_term_children($lider_categoria->term_id, 'tipo_de_membro');
            
            $lideres_query = new WP_Query([
                'post_type' => 'membro',
                'tax_query' => [
                    [
                        'taxonomy' => 'tipo_de_membro',
                        'field'    => 'term_id',
                        'terms'    => $lider_subcategorias,
                        'operator' => 'IN',
                    ],
                ],
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order'   => 'ASC',
            ]);

            if ($lideres_query->have_posts()) :
        ?>
                <div class="membros-grid">
                    <?php while ($lideres_query->have_posts()) : $lideres_query->the_post(); ?>
                        <?php
                        $setor_html = '';
                        $setores = get_the_terms(get_the_ID(), 'tipo_de_membro');

                        if ($setores && !is_wp_error($setores)) {
                            $spans = '';

                            foreach ($setores as $setor) {
                                if (in_array($setor->term_id, $lider_subcategorias)) {
                                    $rotulo = str_replace('Líder ', '', $setor->name);
                                    $spans .= '<span class="setor-' . esc_attr($setor->slug) . '">';
                                    $spans .= esc_html($rotulo);
                                    $spans .= '</span>';
                                }
                            }

                            if ($spans) {

                                $setor_html = '<div class="membro-setores">' . $spans . '</div>';

                            }
                        }

                        get_template_part('template-parts/content', 'membro', array(
                            'show_socials' => true,
                            'setor_html'   => $setor_html,
                        ));
                        ?>
                    <?php endwhile; ?>
                </div>
        <?php
            else :
                echo '<p>Nenhum líder encontrado.</p>';
            endif;
            wp_reset_postdata();
        } else {
            echo '<p>A categoria "Líder" não foi encontrada.</p>';
        }
        ?>
    </section>

    <section class="membros-atuais">
        <h2>Membros atuais</h2>

        <?php
        $atuais_query = new WP_Query([
            'post_type' => 'membro',
            'tax_query' => [
                [
                    'taxonomy' => 'tipo_de_membro',
                    'field'    => 'slug',
                    'terms'    => 'membro-atual',
                ],
            ],
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order'   => 'ASC',
        ]);

        if ($atuais_query->have_posts()) :
        ?>
            <div class="membros-grid">
                <?php while ($atuais_query->have_posts()) : $atuais_query->the_post(); ?>
                    <?php
                    $setor_html = '';
                    $setores = get_the_terms(get_the_ID(), 'tipo_de_membro');
                    $categoria_membro = get_term_by('slug', 'membro-atual', 'tipo_de_membro');

                    if ($setores && !is_wp_error($setores) && $categoria_membro) {
                        $spans = '';

                        foreach ($setores as $setor) {
                            $is_setor_de_membro = term_is_ancestor_of(
                                $categoria_membro->term_id,
                                $setor->term_id,
                                'tipo_de_membro'
                            );

                            if ($is_setor_de_membro) {
                                $spans .= '<span class="setor-' . esc_attr($setor->slug) . '">';
                                $spans .= esc_html($setor->name);
                                $spans .= '</span>';
                            }
                        }

                        if ($spans) {

                            $setor_html = '<div class="membro-setores">' . $spans . '</div>';

                        }
                    }

                    get_template_part('template-parts/content', 'membro', array(
                        'show_socials' => true,
                        'setor_html'   => $setor_html,
                    ));
                    ?>
                <?php endwhile; ?>
            </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>
    </section>

    <section class="membros-egressos">
        <h2>Egressos</h2>

        <?php
        $egressos_query = new WP_Query([
            'post_type' => 'membro',
            'tax_query' => [
                [
                    'taxonomy' => 'tipo_de_membro',
                    'field'    => 'slug',
                    'terms'    => 'egresso', 
                ],
            ],
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order'   => 'ASC',
        ]);
        if ($egressos_query->have_posts()) :
        ?>
            <div class="membros-grid">
                <?php while ($egressos_query->have_posts()) : $egressos_query->the_post(); ?>
                    <?php
                    get_template_part('template-parts/content', 'membro', array(
                        'show_socials' => false,
                    ));
                    ?>
                <?php endwhile; ?>
            </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>
    </section>
</div>

<?php
get_footer(); 
?>