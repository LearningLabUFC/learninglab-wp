<?php
/**
 * Slider Gallery — Renderização Server-Side (Frontend)
 *
 * Chamado automaticamente pelo WordPress para o bloco learninglab/slider-gallery.
 *
 * @param array    $attributes  Atributos salvos pelo editor Gutenberg.
 * @param string   $content     Conteúdo interno do bloco.
 * @param WP_Block $block       Instância do bloco.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$images         = isset( $attributes['images'] ) && is_array( $attributes['images'] ) ? $attributes['images'] : [];
$autoplay       = isset( $attributes['autoplay'] ) ? (bool) $attributes['autoplay'] : true;
$loop           = isset( $attributes['loop'] ) ? (bool) $attributes['loop'] : true;
$show_captions  = isset( $attributes['showCaptions'] ) ? (bool) $attributes['showCaptions'] : false;
$autoplay_delay = isset( $attributes['autoplayDelay'] ) ? (int) $attributes['autoplayDelay'] : 4000;
$align          = isset( $attributes['align'] ) ? sanitize_html_class( $attributes['align'] ) : '';

if ( empty( $images ) ) {
    return;
}

// Enfileira estilos e scripts apenas quando o bloco for renderizado
$theme_version = wp_get_theme()->get( 'Version' );
wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0', 'all' );
wp_enqueue_style( 'learninglab-slider-gallery-style', get_template_directory_uri() . '/assets/css/slider-gallery.css', array( 'swiper-css' ), $theme_version, 'all' );
wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0', true );
wp_enqueue_script( 'learninglab-slider-gallery-init', get_template_directory_uri() . '/assets/js/slider-gallery-init.js', array( 'swiper-js' ), $theme_version, true );

$unique_id = 'll-sg-' . wp_unique_id();

$swiper_config = wp_json_encode([
    'loop'     => $loop,
    'autoplay' => $autoplay ? [ 'delay' => $autoplay_delay, 'disableOnInteraction' => false ] : false,
]);

$wrapper_classes = [ 'wp-block-learninglab-slider-gallery', 'll-slider-gallery-block' ];
if ( $align ) {
    $wrapper_classes[] = 'align' . $align;
}
?>
<figure class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" aria-label="<?php esc_attr_e( 'Carrossel de imagens', 'learninglab-core' ); ?>">
    <div class="swiper" id="<?php echo esc_attr( $unique_id ); ?>" data-swiper="<?php echo esc_attr( $swiper_config ); ?>" tabindex="0">
        <div class="swiper-wrapper">
            <?php foreach ( $images as $index => $image ) :
                $img_url = isset( $image['url'] ) ? esc_url( $image['url'] ) : '';
                $img_alt = isset( $image['alt'] ) ? esc_attr( $image['alt'] ) : '';
                $caption = isset( $image['caption'] ) ? wp_kses_post( $image['caption'] ) : '';
                $img_id  = isset( $image['id'] ) ? (int) $image['id'] : 0;
                $srcset  = $img_id ? wp_get_attachment_image_srcset( $img_id, 'large' ) : false;
                $sizes   = $img_id ? wp_get_attachment_image_sizes( $img_id, 'large' ) : false;

                if ( ! $img_url ) {
                    continue;
                }
            ?>
                <div class="swiper-slide">
                    <img
                        src="<?php echo $img_url; ?>"
                        alt="<?php echo $img_alt; ?>"
                        <?php if ( $srcset ) : ?>
                            srcset="<?php echo esc_attr( $srcset ); ?>"
                            sizes="<?php echo esc_attr( $sizes ); ?>"
                        <?php endif; ?>
                        loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                        decoding="async"
                        class="ll-sg-slide-img"
                    />
                    <?php if ( $show_captions && ! empty( $caption ) ) : ?>
                        <figcaption class="ll-sg-caption">
                            <?php echo $caption; ?>
                        </figcaption>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="swiper-button-prev" aria-label="<?php esc_attr_e( 'Slide anterior', 'learninglab-core' ); ?>"></div>
        <div class="swiper-button-next" aria-label="<?php esc_attr_e( 'Próximo slide', 'learninglab-core' ); ?>"></div>

        <div class="swiper-pagination" role="tablist" aria-label="<?php esc_attr_e( 'Slides', 'learninglab-core' ); ?>"></div>
    </div>
</figure>
