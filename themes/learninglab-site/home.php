<?php
get_header();
?>

<div class="container-blog">
    <section class="container-inicial-blog">
        <h1>Blog</h1>
    </section>

    <?php
    if (have_posts()) :
    ?>
        <section class="conteiner-conteudos cards-grid">
            <?php
            while (have_posts()) : the_post();
                get_template_part('template-parts/content', 'card');
            endwhile;
            ?>
        </section>

    <?php
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('&laquo; Anterior', 'textdomain'),
            'next_text' => __('Próximo &raquo;', 'textdomain'),
        ));
    else :
        get_template_part(
            'template-parts/content',
            'empty',
            array(
                'title'   => 'Nenhum post encontrado',
                'message' => 'Ainda não há publicações disponíveis no blog.',
            )
        );
    endif;
?>
</div>

<?php
get_footer();
?>
