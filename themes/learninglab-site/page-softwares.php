<?php get_header(); ?>

<div class="container-header">
    <div class="content-header">
        <div class="title">
            <h1>Softwares</h1>
        </div>
    </div>
</div>

<div class="container page-softwares">

    <?php
    $args = array(
        'post_type'      => 'software',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

    $query = new WP_Query( $args );

    // Apenas termos da taxonomia que possuem softwares publicados.
    $tipos_software = get_terms( array(
        'taxonomy'   => 'tipo_software',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    $tem_tipos = ! is_wp_error( $tipos_software ) && ! empty( $tipos_software );
    ?>

    <?php if ( $query->have_posts() && $tem_tipos ) : ?>
        <div class="softwares-filtros" role="tablist" aria-label="<?php esc_attr_e( 'Filtrar softwares por tipo', 'site-learninglab' ); ?>">

            <button
                type="button"
                class="filtro-pill is-active"
                role="tab"
                data-filtro="*"
                aria-selected="true"
                aria-controls="softwares-grid"
            >
                <span class="filtro-nome">Todos</span>
                <span class="filtro-contador"><?php echo (int) $query->found_posts; ?></span>
            </button>

            <?php foreach ( $tipos_software as $tipo ) : ?>
                <button
                    type="button"
                    class="filtro-pill"
                    role="tab"
                    data-filtro="<?php echo esc_attr( $tipo->slug ); ?>"
                    aria-selected="false"
                    aria-controls="softwares-grid"
                    tabindex="-1"
                >
                    <span class="filtro-nome"><?php echo esc_html( $tipo->name ); ?></span>
                    <span class="filtro-contador"><?php echo (int) $tipo->count; ?></span>
                </button>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

    <div class="cards-grid" id="softwares-grid">
        <?php
        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();

                $tipos = get_the_terms( get_the_ID(), 'tipo_software' );
                $tipos = ( $tipos && ! is_wp_error( $tipos ) ) ? $tipos : array();

                // Badge exibido no card (primeiro tipo cadastrado).
                $badge_text  = $tipos ? $tipos[0]->name : '';
                $badge_class = $tipos ? 'tipo-' . $tipos[0]->slug : '';

                // Slugs usados pelo filtro client-side (um software pode ter mais de um tipo).
                $slugs = wp_list_pluck( $tipos, 'slug' );

                get_template_part( 'template-parts/content', 'card', array(
                    'show_badge'  => ! empty( $badge_text ),
                    'badge_text'  => $badge_text,
                    'badge_class' => $badge_class,
                    'item_tipos'  => implode( ' ', $slugs ),
                ) );

            endwhile;
            wp_reset_postdata();
        else : ?>
            <p>Nenhum software encontrado no momento.</p>
        <?php endif; ?>
    </div>

    <p class="softwares-empty" role="status" hidden>
        <?php esc_html_e( 'Nenhum software encontrado nesta categoria.', 'site-learninglab' ); ?>
    </p>

</div>

<?php get_footer(); ?>
