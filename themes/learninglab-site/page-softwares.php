<?php get_header(); ?>

<div class="container-header">
    <div class="content-header">
        <div class="title">
            <h1>Softwares</h1>
        </div>
    </div>
</div>

<div class="container page-softwares">

    <div class="cards-grid">
        <?php
        $args = array(
            'post_type'      => 'software',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();

                // Obter a taxonomia para exibir o badge
                $tipos = get_the_terms( get_the_ID(), 'tipo_software' );
                $badge_text  = ( $tipos && ! is_wp_error( $tipos ) ) ? $tipos[0]->name : '';
                $badge_class = ( $tipos && ! is_wp_error( $tipos ) ) ? 'tipo-' . $tipos[0]->slug : '';

                get_template_part( 'template-parts/content', 'card', array(
                    'show_badge'  => ! empty( $badge_text ),
                    'badge_text'  => $badge_text,
                    'badge_class' => $badge_class,
                ) );

            endwhile;
            wp_reset_postdata();
        else : ?>
            <p>Nenhum software encontrado no momento.</p>
        <?php endif; ?>
    </div>

</div>

<?php get_footer(); ?>