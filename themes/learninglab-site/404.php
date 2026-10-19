<?php
    get_header();

    $links_404 = [
        'Página inicial' => ['url' => home_url('/'), 'icon' => 'fa-house'],
    ];

    $paginas_404 = [
        'Artigos' => ['slug' => 'artigos',       'icon' => 'fa-newspaper'],
        'Cursos'  => ['slug' => 'nossos-cursos', 'icon' => 'fa-graduation-cap'],
        'Membros' => ['slug' => 'membros',       'icon' => 'fa-users'],
    ];

    foreach ($paginas_404 as $rotulo => $info) {
        $pagina = get_page_by_path($info['slug']);
        if ($pagina) {
            $links_404[$rotulo] = ['url' => get_permalink($pagina), 'icon' => $info['icon']];
        }
    }
?>

<div class="container-header">
    <div class="content-header">
        <div class="title">
            <h1>Página não encontrada :(</h1>
            <p>
                A página que você procura não foi encontrada.
                Tente fazer uma busca ou acesse um dos links abaixo.
            </p>
        </div>
    </div>
</div>

<main class="container container-404">
    <div class="busca-404">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <label class="screen-reader-text" for="busca-404-input">Pesquisar por:</label>
            <input type="search" id="busca-404-input" name="s" placeholder="Pesquisar no site..." value="<?php echo esc_attr(get_search_query()); ?>">
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                Pesquisar
            </button>
        </form>
    </div>

    <nav class="links-404" aria-label="Links úteis">
        <ul>
            <?php foreach ($links_404 as $rotulo => $item) : ?>
                <li>
                    <a href="<?php echo esc_url($item['url']); ?>">
                        <i class="fa-solid <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></i>
                        <?php echo esc_html($rotulo); ?>
                    </a>
                </li>
            <?php endforeach; ?>
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
        <section class="posts-recentes-404">
            <h2>Posts recentes</h2>
            <ul>
                <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                    <?php
                        $meses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
                        $data_pt = get_the_date('j') . ' de ' . $meses[(int) get_the_date('n') - 1] . ' de ' . get_the_date('Y');
                    ?>
                    <li>
                        <a href="<?php echo esc_url(get_permalink()); ?>">
                            <span class="post-titulo"><?php echo esc_html(get_the_title()); ?></span>
                            <span class="post-data"><?php echo esc_html($data_pt); ?></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php
        endif;
        wp_reset_postdata();
    ?>
</main>

<?php
    get_footer();
?>
