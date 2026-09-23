<?php
/**
 * Slider Gallery — Renderização Server-Side (Frontend)
 *
 * Renderiza o Swiper.js com setas externas (esquerda e direita) e pontos indicadores abaixo.
 *
 * @param array    $attributes  Atributos do bloco salvos no Gutenberg.
 * @param string   $content     Conteúdo interno (vazio).
 * @param WP_Block $block       Instância do bloco.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$images           = isset( $attributes['images'] ) && is_array( $attributes['images'] ) ? $attributes['images'] : [];
$image_size       = isset( $attributes['imageSize'] ) ? sanitize_text_field( $attributes['imageSize'] ) : 'large';
$link_to          = isset( $attributes['linkTo'] ) ? sanitize_text_field( $attributes['linkTo'] ) : 'none';
$display_nav      = isset( $attributes['displayNav'] ) ? (bool) $attributes['displayNav'] : true;
$display_bullets  = isset( $attributes['displayBullets'] ) ? (bool) $attributes['displayBullets'] : true;
$display_captions = isset( $attributes['displayCaptions'] ) ? (bool) $attributes['displayCaptions'] : false;
$autoplay         = isset( $attributes['autoplay'] ) ? (bool) $attributes['autoplay'] : true;
$loop             = isset( $attributes['loop'] ) ? (bool) $attributes['loop'] : true;
$autoplay_delay   = isset( $attributes['autoplayDelay'] ) ? (int) $attributes['autoplayDelay'] : 4000;
$align            = isset( $attributes['align'] ) ? sanitize_html_class( $attributes['align'] ) : '';

if ( empty( $images ) ) {
    return;
}

// Enfileira Swiper e estilos específicos do carrossel
$theme_version = wp_get_theme()->get( 'Version' );
wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0', 'all' );
wp_enqueue_style( 'learninglab-slider-gallery-style', get_template_directory_uri() . '/assets/css/slider-gallery.css', array( 'swiper-css' ), $theme_version, 'all' );
wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0', true );
wp_enqueue_script( 'learninglab-slider-gallery-init', get_template_directory_uri() . '/assets/js/slider-gallery-init.js', array( 'swiper-js' ), $theme_version, true );

$unique_id = 'll-sg-' . wp_unique_id();

$swiper_config = wp_json_encode([
    'loop'       => $loop,
    'autoplay'   => $autoplay ? [ 'delay' => $autoplay_delay, 'disableOnInteraction' => false ] : false,
    'hasNav'     => $display_nav,
    'hasBullets' => $display_bullets,
]);

$wrapper_classes = [ 'wp-block-learninglab-slider-gallery', 'll-slider-gallery-block' ];
if ( $align ) {
    $wrapper_classes[] = 'align' . $align;
}
?>
<figure class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" aria-label="<?php esc_attr_e( 'Carrossel de imagens', 'learninglab-core' ); ?>">
    <!-- Linha com Seta Esquerda + Imagem Central + Seta Direita -->
    <div class="ll-sg-carousel-row">
        <?php if ( $display_nav && count( $images ) > 1 ) : ?>
            <div class="swiper-button-prev" role="button" aria-label="<?php esc_attr_e( 'Slide anterior', 'learninglab-core' ); ?>" tabindex="0"></div>
        <?php endif; ?>

        <div class="swiper" id="<?php echo esc_attr( $unique_id ); ?>" data-swiper="<?php echo esc_attr( $swiper_config ); ?>" tabindex="0">
            <div class="swiper-wrapper">
                <?php foreach ( $images as $index => $image ) :
                    $img_id  = isset( $image['id'] ) ? (int) $image['id'] : 0;
                    $img_url = '';
                    $srcset  = false;
                    $sizes   = false;

                    if ( $img_id ) {
                        $img_url = wp_get_attachment_image_url( $img_id, $image_size );
                        $srcset  = wp_get_attachment_image_srcset( $img_id, $image_size );
                        $sizes   = wp_get_attachment_image_sizes( $img_id, $image_size );
                    }
                    if ( ! $img_url && ! empty( $image['url'] ) ) {
                        $img_url = $image['url'];
                    }
                    if ( ! $img_url ) {
                        continue;
                    }

                    $img_alt = isset( $image['alt'] ) ? esc_attr( $image['alt'] ) : '';
                    $caption = isset( $image['caption'] ) ? wp_kses_post( $image['caption'] ) : '';

                    // Determina link da imagem
                    $link_url = '';
                    if ( 'file' === $link_to && $img_id ) {
                        $link_url = wp_get_attachment_url( $img_id );
                    } elseif ( 'attachment' === $link_to && $img_id ) {
                        $link_url = get_attachment_link( $img_id );
                    }
                ?>
                    <div class="swiper-slide">
                        <?php if ( $link_url ) : ?>
                            <a href="<?php echo esc_url( $link_url ); ?>" class="ll-sg-slide-link" <?php echo 'file' === $link_to ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                        <?php endif; ?>

                        <img
                            src="<?php echo esc_url( $img_url ); ?>"
                            alt="<?php echo $img_alt; ?>"
                            <?php if ( $srcset ) : ?>
                                srcset="<?php echo esc_attr( $srcset ); ?>"
                                sizes="<?php echo esc_attr( $sizes ); ?>"
                            <?php endif; ?>
                            loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                            decoding="async"
                            class="ll-sg-slide-img"
                        />

                        <?php if ( $link_url ) : ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( $display_captions && ! empty( $caption ) ) : ?>
                            <figcaption class="ll-sg-caption">
                                <?php echo $caption; ?>
                            </figcaption>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ( $display_nav && count( $images ) > 1 ) : ?>
            <div class="swiper-button-next" role="button" aria-label="<?php esc_attr_e( 'Próximo slide', 'learninglab-core' ); ?>" tabindex="0"></div>
        <?php endif; ?>
    </div>

    <!-- Pontos indicadores posicionados FORA e ABAIXO da imagem -->
    <?php if ( $display_bullets && count( $images ) > 1 ) : ?>
        <div class="swiper-pagination" role="tablist" aria-label="<?php esc_attr_e( 'Pontos indicadores dos slides', 'learninglab-core' ); ?>"></div>
    <?php endif; ?>
</figure>
