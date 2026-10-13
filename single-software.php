<?php get_header(); ?>

<main class="software-single-main">
    <?php while ( have_posts() ) : the_post(); ?>

    <div class="container-header">
        <div class="content-header software-header">

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="software-logo">
                    <?php the_post_thumbnail( 'thumbnail' ); ?>
                </div>
            <?php endif; ?>

            <div class="software-header-info">
                <h1><?php the_title(); ?></h1>

                <div class="software-header-meta">
                    <?php
                    // Badges de tipo
                    $tipos = get_the_terms( get_the_ID(), 'tipo_software' );
                    if ( $tipos && ! is_wp_error( $tipos ) ) : ?>
                        <div class="software-tipos">
                            <?php foreach ( $tipos as $tipo ) : ?>
                                <span class="badge tipo-badge tipo-<?php echo esc_attr( $tipo->slug ); ?>">
                                    <?php echo esc_html( $tipo->name ); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Versão ou badge Em desenvolvimento
                    $em_dev = get_post_meta( get_the_ID(), '_software_em_desenvolvimento', true );
                    $versao = get_post_meta( get_the_ID(), '_software_versao', true );
                    if ( $em_dev ) : ?>
                        <span class="software-versao software-em-dev">Em desenvolvimento</span>
                    <?php elseif ( $versao ) : ?>
                        <span class="software-versao">v<?php echo esc_html( $versao ); ?></span>
                    <?php endif; ?>
                </div>

                <?php
                // Links para Repositório e Demonstração
                $link_repo = get_post_meta( get_the_ID(), '_software_link_repo', true );
                $link_demo = get_post_meta( get_the_ID(), '_software_link_demo', true );
                if ( $link_repo || $link_demo ) : ?>
                    <div class="software-links">
                        <?php if ( $link_repo ) : ?>
                            <a href="<?php echo esc_url( $link_repo ); ?>" target="_blank" rel="noopener noreferrer" class="btn-software btn-repo">
                                <i class="fa-brands fa-github"></i> Repositório
                            </a>
                        <?php endif; ?>
                        <?php if ( $link_demo ) : ?>
                            <a href="<?php echo esc_url( $link_demo ); ?>" target="_blank" rel="noopener noreferrer" class="btn-software btn-demo">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Acessar
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div class="container software-body">

        <div class="software-descricao">
            <?php the_content(); ?>
        </div>

        <?php
        // Tecnologias utilizadas
        $tecnologias = get_post_meta( get_the_ID(), '_software_tecnologias', true );
        if ( $tecnologias ) :
            $tech_array = array_map( 'trim', preg_split( '/[,;]/', $tecnologias ) );
        ?>
            <div class="software-tecnologias">
                <h3>Tecnologias</h3>
                <ul class="tech-list">
                    <?php foreach ( $tech_array as $tech ) : ?>
                        <?php if ( ! empty( $tech ) ) : ?>
                            <li><?php echo esc_html( $tech ); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php
        // Membros envolvidos
        $membros = get_post_meta( get_the_ID(), '_software_membros', true );
        if ( ! empty( $membros ) && is_array( $membros ) ) : ?>
            <div class="software-membros">
                <h3>Membros envolvidos</h3>
                <div class="membros-grid">
                    <?php foreach ( $membros as $membro_data ) :
                        $membro_id = isset( $membro_data['id'] ) ? (int) $membro_data['id'] : 0;
                        $cargo     = isset( $membro_data['cargo'] ) ? $membro_data['cargo'] : '';
                        $nome      = get_the_title( $membro_id );
                        $foto_url  = get_the_post_thumbnail_url( $membro_id, 'thumbnail' );
                        if ( ! $membro_id || ! $nome ) continue;
                    ?>
                        <div class="membro-item">
                            <?php if ( $foto_url ) : ?>
                                <div class="membro-avatar">
                                    <img src="<?php echo esc_url( $foto_url ); ?>" alt="<?php echo esc_attr( $nome ); ?>">
                                </div>
                            <?php endif; ?>
                            <h4 class="membro-nome"><?php echo esc_html( $nome ); ?></h4>
                            <?php if ( $cargo ) : ?>
                                <span class="membro-cargo"><?php echo esc_html( $cargo ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>