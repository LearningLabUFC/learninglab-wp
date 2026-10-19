<?php get_header(); ?>

<main class="software-single-main">
    <?php while ( have_posts() ) : the_post();
        $software_id = get_the_ID();
        $em_dev      = get_post_meta( $software_id, '_software_em_desenvolvimento', true );
        $versao      = get_post_meta( $software_id, '_software_versao', true );
        $link_repo   = get_post_meta( $software_id, '_software_link_repo', true );
        $link_demo   = get_post_meta( $software_id, '_software_link_demo', true );
        $tecnologias = get_post_meta( $software_id, '_software_tecnologias', true );
        $membros     = get_post_meta( $software_id, '_software_membros', true );
        $tipos       = get_the_terms( $software_id, 'tipo_software' );

        $tech_array = array();
        if ( $tecnologias ) {
            $tech_array = array_filter( array_map( 'trim', preg_split( '/[,;]/', $tecnologias ) ) );
        }

        $tem_sidebar = has_post_thumbnail() || $em_dev || $versao || $link_repo || $link_demo || ! empty( $tech_array ) || ( $tipos && ! is_wp_error( $tipos ) );
    ?>

    <div class="container-header software-single-header">
        <div class="content-header">
            <div class="title">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </div>

    <div class="software-layout<?php echo $tem_sidebar ? ' tem-sidebar' : ''; ?>">

        <?php if ( $tem_sidebar ) : ?>
            <aside class="software-sidebar" aria-label="Informações do software">
                <div class="software-info-card">

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="software-logo">
                            <?php the_post_thumbnail( 'medium' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="software-meta-badges">
                        <?php if ( $em_dev ) : ?>
                            <span class="software-badge software-badge-dev">
                                <i class="fa-solid fa-code" aria-hidden="true"></i>
                                Em desenvolvimento
                            </span>
                        <?php elseif ( $versao ) : ?>
                            <span class="software-badge software-badge-versao">
                                <i class="fa-solid fa-tag" aria-hidden="true"></i>
                                v<?php echo esc_html( $versao ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $tipos && ! is_wp_error( $tipos ) ) : ?>
                            <?php foreach ( $tipos as $tipo ) : ?>
                                <span class="software-badge tipo-badge tipo-<?php echo esc_attr( $tipo->slug ); ?>">
                                    <?php echo esc_html( $tipo->name ); ?>
                                </span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ( $link_demo || $link_repo ) : ?>
                        <div class="software-actions">
                            <?php if ( $link_demo ) : ?>
                                <a
                                    href="<?php echo esc_url( $link_demo ); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-software btn-demo"
                                >
                                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                    Acessar
                                </a>
                            <?php endif; ?>

                            <?php if ( $link_repo ) : ?>
                                <a
                                    href="<?php echo esc_url( $link_repo ); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-software btn-repo"
                                >
                                    <i class="fa-brands fa-github" aria-hidden="true"></i>
                                    Repositório
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $tech_array ) ) : ?>
                        <div class="software-sidebar-secao software-tecnologias">
                            <h3 class="software-sidebar-titulo">Tecnologias</h3>
                            <ul class="tech-list">
                                <?php foreach ( $tech_array as $tech ) : ?>
                                    <li><?php echo esc_html( $tech ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </aside>
        <?php endif; ?>

        <div class="software-main content entry-content">

            <div class="software-descricao">
                <?php the_content(); ?>
            </div>

            <?php
            // Membros envolvidos
            if ( ! empty( $membros ) && is_array( $membros ) ) :
                $cargos_por_id = array();
                foreach ( $membros as $membro_data ) {
                    if ( ! empty( $membro_data['id'] ) ) {
                        $cargos_por_id[ (int) $membro_data['id'] ] = $membro_data['cargo'] ?? '';
                    }
                }
                $membros_ids = array_keys( $cargos_por_id );

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
                <section class="software-secao software-membros">
                    <h3 class="software-secao-titulo">Membros envolvidos</h3>
                    <div class="membros-grid">
                        <?php while ( $membros_query->have_posts() ) : $membros_query->the_post();
                            $cargo = $cargos_por_id[ get_the_ID() ] ?? '';
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

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>