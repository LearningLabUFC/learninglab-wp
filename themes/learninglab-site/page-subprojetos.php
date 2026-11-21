<?php


get_header(); ?>

<div class="container-header">
    <div class="content-header">
        <div class="title">
            <h1>Nossos Subprojetos</h1>
        </div>
    </div>
</div>

<div class="container">

    <?php
    $args = array(
        'post_type'      => 'subprojetos',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $query_subprojetos = new WP_Query( $args );

    if ( $query_subprojetos->have_posts() ) :
    ?>
        <div class="cards-grid">
            <?php
            while ( $query_subprojetos->have_posts() ) : $query_subprojetos->the_post();
                get_template_part( 'template-parts/content', 'card' );
            endwhile;
            ?>
        </div>
        <?php
        wp_reset_postdata();
    else :
        get_template_part(
            'template-parts/content',
            'empty',
            array(
                'title'   => 'Nenhum subprojeto encontrado',
                'message' => 'Não há subprojetos disponíveis no momento.',
            )
        );
    endif;
    ?>
</div>

<?php get_footer(); ?>
