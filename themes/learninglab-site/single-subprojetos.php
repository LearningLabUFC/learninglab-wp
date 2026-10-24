<?php get_header(); ?>

<main>
    <?php
    while ( have_posts() ) :
        the_post();

        $link    = get_post_meta( get_the_ID(), '_subprojeto_link', true );
        $periodo = get_post_meta( get_the_ID(), '_subprojeto_periodo', true );
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

                <?php if ( $periodo ) : ?>
                    <p>
                        <strong>Período:</strong>
                        <?php echo esc_html( $periodo ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( $link ) : ?>
                    <p>
                        <a
                            href="<?php echo esc_url( $link ); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Acessar subprojeto
                        </a>
                    </p>
                <?php endif; ?>

            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
