<?php get_header(); ?>

<main class="subprojeto-single-main">
    <?php while ( have_posts() ) : the_post();
        $subprojeto_id = get_the_ID();

        $periodo   = get_post_meta( $subprojeto_id, '_subprojeto_periodo', true );
        $status    = get_post_meta( $subprojeto_id, '_subprojeto_status', true );
        $link      = get_post_meta( $subprojeto_id, '_subprojeto_link', true );
        $instagram = get_post_meta( $subprojeto_id, '_subprojeto_instagram', true );
        $github    = get_post_meta( $subprojeto_id, '_subprojeto_github', true );
        $lideres   = get_post_meta( $subprojeto_id, '_subprojeto_lideres', true );
        $membros   = get_post_meta( $subprojeto_id, '_subprojeto_membros', true );

        $status_labels = array(
            'em_andamento' => array( 'label' => 'Em andamento', 'icon' => 'fa-solid fa-spinner' ),
            'concluido'    => array( 'label' => 'Concluído',    'icon' => 'fa-solid fa-circle-check' ),
            'planejamento' => array( 'label' => 'Em planejamento', 'icon' => 'fa-regular fa-lightbulb' ),
        );

        $status_info = isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : null;

        $tem_acoes   = ! empty( $link ) || ! empty( $instagram ) || ! empty( $github );
        $tem_sidebar = has_post_thumbnail() || ! empty( $periodo ) || ! empty( $status_info ) || $tem_acoes;
    ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div class="container-header subprojeto-single-header">
                <div class="content-header">
                    <div class="title">
                        <h1><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>

            <div class="subprojeto-layout<?php echo $tem_sidebar ? ' tem-sidebar' : ''; ?>">

                <?php if ( $tem_sidebar ) : ?>
                    <aside class="subprojeto-sidebar" aria-label="Informações do subprojeto">
                        <div class="subprojeto-info-card">

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="subprojeto-logo">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $status_info ) : ?>
                                <div class="subprojeto-meta-badges">
                                    <span class="subprojeto-badge status-<?php echo esc_attr( $status ); ?>">
                                        <i class="<?php echo esc_attr( $status_info['icon'] ); ?>" aria-hidden="true"></i>
                                        <?php echo esc_html( $status_info['label'] ); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $periodo ) : ?>
                                <ul class="subprojeto-info-lista">
                                    <li>
                                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                                        <div>
                                            <span class="subprojeto-info-rotulo">Período</span>
                                            <span class="subprojeto-info-valor"><?php echo esc_html( $periodo ); ?></span>
                                        </div>
                                    </li>
                                </ul>
                            <?php endif; ?>

                            <?php if ( $tem_acoes ) : ?>
                                <div class="subprojeto-actions">
                                    <?php if ( $link ) : ?>
                                        <a
                                            href="<?php echo esc_url( $link ); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn-subprojeto btn-externo"
                                        >
                                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                            Acessar subprojeto
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $instagram ) : ?>
                                        <a
                                            href="<?php echo esc_url( $instagram ); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn-subprojeto btn-instagram"
                                        >
                                            <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                                            Instagram
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $github ) : ?>
                                        <a
                                            href="<?php echo esc_url( $github ); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn-subprojeto btn-github"
                                        >
                                            <i class="fa-brands fa-github" aria-hidden="true"></i>
                                            Repositório
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </aside>
                <?php endif; ?>

                <div class="subprojeto-main content entry-content">

                    <div class="subprojeto-descricao">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    // Seção 1: Liderança
                    if ( ! empty( $lideres ) && is_array( $lideres ) ) :
                        $cargos_lideres = array();
                        foreach ( $lideres as $item ) {
                            if ( ! empty( $item['id'] ) ) {
                                $cargos_lideres[ (int) $item['id'] ] = $item['cargo'] ?? '';
                            }
                        }
                        $lideres_ids = array_keys( $cargos_lideres );

                        $lideres_query = new WP_Query( array(
                            'post_type'      => 'membro',
                            'post_status'    => 'publish',
                            'post__in'       => ! empty( $lideres_ids ) ? $lideres_ids : array( 0 ),
                            'orderby'        => 'post__in',
                            'posts_per_page' => -1,
                            'no_found_rows'  => true,
                        ) );

                        if ( $lideres_query->have_posts() ) :
                    ?>
                        <section class="subprojeto-secao subprojeto-membros subprojeto-lideranca">
                            <h3 class="subprojeto-secao-titulo">Liderança</h3>
                            <div class="membros-grid">
                                <?php while ( $lideres_query->have_posts() ) : $lideres_query->the_post();
                                    $cargo = $cargos_lideres[ get_the_ID() ] ?? '';
                                    $setor_html = $cargo ? '<div class="membro-setores"><span class="membro-cargo">' . esc_html( $cargo ) . '</span></div>' : '';

                                    get_template_part( 'template-parts/content', 'membro', array(
                                        'show_socials' => false,
                                        'setor_html'   => $setor_html,
                                    ) );
                                endwhile; ?>
                            </div>
                        </section>
                    <?php
                        endif;
                        wp_reset_postdata();
                    endif;
                    ?>

                    <?php
                    // Seção 2: Demais membros
                    if ( ! empty( $membros ) && is_array( $membros ) ) :
                        $cargos_membros = array();
                        foreach ( $membros as $item ) {
                            if ( ! empty( $item['id'] ) ) {
                                $cargos_membros[ (int) $item['id'] ] = $item['cargo'] ?? '';
                            }
                        }
                        $membros_ids = array_keys( $cargos_membros );

                        $membros_query = new WP_Query( array(
                            'post_type'      => 'membro',
                            'post_status'    => 'publish',
                            'post__in'       => ! empty( $membros_ids ) ? $membros_ids : array( 0 ),
                            'orderby'        => 'post__in',
                            'posts_per_page' => -1,
                            'no_found_rows'  => true,
                        ) );

                        if ( $membros_query->have_posts() ) :
                    ?>
                        <section class="subprojeto-secao subprojeto-membros subprojeto-demais-membros">
                            <h3 class="subprojeto-secao-titulo">Demais membros</h3>
                            <div class="membros-grid">
                                <?php while ( $membros_query->have_posts() ) : $membros_query->the_post();
                                    $cargo = $cargos_membros[ get_the_ID() ] ?? '';
                                    $setor_html = $cargo ? '<div class="membro-setores"><span class="membro-cargo">' . esc_html( $cargo ) . '</span></div>' : '';

                                    get_template_part( 'template-parts/content', 'membro', array(
                                        'show_socials' => false,
                                        'setor_html'   => $setor_html,
                                    ) );
                                endwhile; ?>
                            </div>
                        </section>
                    <?php
                        endif;
                        wp_reset_postdata();
                    endif;
                    ?>

                </div>

            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
