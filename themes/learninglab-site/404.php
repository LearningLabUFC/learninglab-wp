<?php
    get_header();
?>

<div class="container-header">
    <div class="content-header">
        <div class="title">
            <h1>Página não encontrada :(</h1>
            <p>
                A página que você procura não foi encontrada.
                Tente fazer uma busca ou acesse um dos links abaixo.
            </p>

            <?php get_search_form(); ?>

            <nav class="links-404" aria-label="Links úteis">
                <ul>
                    <li>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            ← Página inicial
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('artigos'))); ?>">
                            Artigos
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('nossos-cursos'))); ?>">
                            Cursos
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('membros'))); ?>">
                            Membros
                        </a>
                    </li>
                </ul>
            </nav>

            <?php
                $recent = new WP_Query([
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                    'post_type'      => 'post',
                    'no_found_rows'  => true,
                ]);

                if ($recent->have_posts()) :
            ?>
                <div class="posts-recentes-404">
                    <h2>Posts recentes</h2>
                    <ul>
                        <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                    <?php echo esc_html(get_the_title()); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php
                endif;
                wp_reset_postdata();
            ?>
        </div>
    </div>
</div>

<?php
    get_footer();
?>
