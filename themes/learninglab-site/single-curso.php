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

            <div class="content entry-content">

                <?php the_content(); ?>

                <!-- Dados do Curso -->
                <?php if ($carga_horaria || $data_inicio || $link_inscricao) : ?>
                    <div class="curso-detalhes">

                        <?php if ($carga_horaria) : ?>
                            <p>
                                <strong>Carga horária:</strong>
                                <?php echo esc_html($carga_horaria); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($data_inicio) : ?>
                            <p>
                                <strong>Início:</strong>
                                <?php
                                echo esc_html(
                                    date_i18n('d/m/Y', strtotime($data_inicio))
                                );
                                ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($link_inscricao) : ?>
                            <a
                                href="<?php echo esc_url($link_inscricao); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-inscricao"
                            >
                                Fazer inscrição
                            </a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

                <!-- Instrutores -->
                <?php if (!empty($instrutores)) : ?>
                    <div class="curso-instrutores">

                        <h3>Instrutores</h3>

                        <?php foreach ($instrutores as $item) :

                            $membro_id = isset($item['id'])
                                ? (int) $item['id']
                                : 0;

                            $descricao = isset($item['descricao'])
                                ? $item['descricao']
                                : '';

                            if (!$membro_id) {
                                continue;
                            }

                            $nome = get_the_title($membro_id);

                            if (!$nome) {
                                continue;
                            }

                            $foto_url = get_the_post_thumbnail_url(
                                $membro_id,
                                'full'
                            );
                        ?>

                            <div class="instrutor-bloco">

                                <?php if ($foto_url) : ?>
                                    <div class="instrutor-imagem">
                                        <img
                                            src="<?php echo esc_url($foto_url); ?>"
                                            alt="<?php echo esc_attr($nome); ?>"
                                        >
                                    </div>
                                <?php endif; ?>

                                <div class="instrutor-info">

                                    <h2>
                                        <?php echo esc_html($nome); ?>
                                    </h2>

                                    <?php if ($descricao) : ?>
                                        <p>
                                            <?php echo esc_html($descricao); ?>
                                        </p>
                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>

                <!-- Monitores -->
                <?php if (!empty($monitores)) : ?>
                    <div class="curso-monitores">

                        <h3>Monitores</h3>

                        <div class="membros-grid">

                            <?php foreach ($monitores as $membro_id) :

                                $membro_id = (int) $membro_id;

                                if (!$membro_id) {
                                    continue;
                                }

                                $nome = get_the_title($membro_id);

                                if (!$nome) {
                                    continue;
                                }

                                $foto_img = get_the_post_thumbnail(
                                    $membro_id,
                                    'thumbnail',
                                    array(
                                        'class' => 'attachment-thumbnail size-thumbnail wp-post-image',
                                    )
                                );
                            ?>

                                <div class="membro-item">

                                    <div class="membro-avatar">
                                        <?php echo $foto_img; ?>
                                    </div>

                                    <h4 class="membro-nome">
                                        <?php echo esc_html($nome); ?>
                                    </h4>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>
                <?php endif; ?>

            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
