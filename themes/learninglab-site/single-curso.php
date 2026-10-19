<?php get_header(); ?>

<main>
    <?php
    while (have_posts()) :
        the_post();

        $curso_id = get_the_ID();

        $link_inscricao = get_post_meta($curso_id, '_curso_link_inscricao', true);
        $carga_horaria  = get_post_meta($curso_id, '_curso_carga_horaria', true);
        $data_inicio    = get_post_meta($curso_id, '_curso_data_inicio', true);
        $instrutores    = get_post_meta($curso_id, '_curso_instrutores', true);
        $monitores      = get_post_meta($curso_id, '_curso_monitores', true);

        if (!is_array($instrutores)) {
            $instrutores = array();
        }

        if (!is_array($monitores)) {
            $monitores = array();
        }
    ?>

        <article>

            <div class="container-header">

                <div class="content-header">
                    <div class="title">
                        <h1><?php the_title(); ?></h1>
                    </div>
                </div>

            </div>

            <?php
            // Status de inscrição, definido pela taxonomia "status_curso":
            //  - inscricoes-abertas    => botão de inscrição (se houver link)
            //  - inscricoes-encerradas => aviso "Inscrições encerradas"
            //  - curso-em-andamento    => aviso "Em andamento"
            //  - demais (passado etc.) => nada
            $status_slugs = wp_get_post_terms($curso_id, 'status_curso', array('fields' => 'slugs'));
            if (is_wp_error($status_slugs)) {
                $status_slugs = array();
            }

            $mostrar_botao = in_array('inscricoes-abertas', $status_slugs, true) && $link_inscricao;
            $status_aviso  = '';

            if (in_array('inscricoes-abertas', $status_slugs, true)) {
                $status_aviso = '';
            } elseif (in_array('inscricoes-encerradas', $status_slugs, true)) {
                $status_aviso = 'encerradas';
            } elseif (in_array('curso-em-andamento', $status_slugs, true)) {
                $status_aviso = 'andamento';
            }

            $tem_info = $carga_horaria || $data_inicio || $mostrar_botao || $status_aviso;
            ?>

            <div class="curso-layout<?php echo $tem_info ? ' tem-sidebar' : ''; ?>">

                <?php if ($tem_info) : ?>
                    <aside class="curso-sidebar" aria-label="Informações do curso">
                        <div class="curso-info-card">
                            <h2 class="curso-info-titulo">Informações do curso</h2>

                            <ul class="curso-info-lista">
                                <?php if ($carga_horaria) : ?>
                                    <li>
                                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                                        <div>
                                            <span class="curso-info-rotulo">Carga horária</span>
                                            <span class="curso-info-valor"><?php echo esc_html($carga_horaria); ?></span>
                                        </div>
                                    </li>
                                <?php endif; ?>

                                <?php if ($data_inicio && strtotime($data_inicio)) : ?>
                                    <li>
                                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                                        <div>
                                            <span class="curso-info-rotulo">Início</span>
                                            <span class="curso-info-valor"><?php echo esc_html(date_i18n('d/m/Y', strtotime($data_inicio))); ?></span>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <?php if ($mostrar_botao) : ?>
                                <a
                                    href="<?php echo esc_url($link_inscricao); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-inscricao"
                                >
                                    Fazer inscrição
                                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                </a>
                            <?php elseif ($status_aviso === 'encerradas') : ?>
                                <span class="curso-status-inscricao encerradas">
                                    <i class="fa-solid fa-lock" aria-hidden="true"></i>
                                    Inscrições encerradas
                                </span>
                            <?php elseif ($status_aviso === 'andamento') : ?>
                                <span class="curso-status-inscricao andamento">
                                    <i class="fa-solid fa-play" aria-hidden="true"></i>
                                    Em andamento
                                </span>
                            <?php endif; ?>
                        </div>
                    </aside>
                <?php endif; ?>

                <div class="curso-main content entry-content">

                    <?php the_content(); ?>

                    <!-- Instrutores -->
                    <?php if (!empty($instrutores)) : ?>
                        <section class="curso-secao curso-instrutores">
                            <h3 class="curso-secao-titulo">Instrutores</h3>

                            <?php foreach ($instrutores as $item) :
                                $membro_id = isset($item['id']) ? (int) $item['id'] : 0;
                                $descricao = isset($item['descricao']) ? $item['descricao'] : '';

                                if (!$membro_id) {
                                    continue;
                                }

                                $nome = get_the_title($membro_id);

                                if (!$nome) {
                                    continue;
                                }

                                $foto_url = get_the_post_thumbnail_url($membro_id, 'medium');
                            ?>
                                <div class="instrutor-card">
                                    <?php if ($foto_url) : ?>
                                        <div class="instrutor-foto">
                                            <img
                                                src="<?php echo esc_url($foto_url); ?>"
                                                alt="<?php echo esc_attr($nome); ?>"
                                                loading="lazy"
                                            >
                                        </div>
                                    <?php endif; ?>

                                    <div class="instrutor-info">
                                        <h4 class="instrutor-nome"><?php echo esc_html($nome); ?></h4>

                                        <?php if ($descricao) : ?>
                                            <p class="instrutor-descricao"><?php echo esc_html($descricao); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Monitores -->
                    <?php if (!empty($monitores)) : ?>
                        <section class="curso-secao curso-monitores">
                            <h3 class="curso-secao-titulo">Monitores</h3>

                            <?php
                            $monitores_ids = array_values(array_filter(array_map('intval', $monitores)));

                            $monitores_query = new WP_Query(array(
                                'post_type'      => 'membro',
                                'post_status'    => 'publish',
                                'post__in'       => $monitores_ids ? $monitores_ids : array(0),
                                'orderby'        => 'post__in',
                                'posts_per_page' => -1,
                                'no_found_rows'  => true,
                            ));
                            ?>

                            <?php if ($monitores_query->have_posts()) : ?>
                                <div class="membros-grid">
                                    <?php while ($monitores_query->have_posts()) : $monitores_query->the_post(); ?>
                                        <?php
                                        get_template_part('template-parts/content', 'membro', array(
                                            'show_socials' => false,
                                        ));
                                        ?>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
                        </section>
                    <?php endif; ?>

                </div>
            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
