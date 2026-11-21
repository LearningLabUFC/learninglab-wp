<?php
/**
 * Template Part: Estado vazio
 *
 * Argumentos opcionais via get_template_part():
 * - title:    título do estado vazio
 * - message:  mensagem explicativa
 * - cta_text: texto do botão (opcional)
 * - cta_url:  URL do botão (opcional)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$defaults = array(
    'title'    => 'Nenhum conteúdo encontrado',
    'message'  => 'Não há conteúdo disponível no momento.',
    'cta_text' => '',
    'cta_url'  => '',
);

$empty_args = wp_parse_args(
    isset( $args ) && is_array( $args ) ? $args : array(),
    $defaults
);

$title    = $empty_args['title'];
$message  = $empty_args['message'];
$cta_text = $empty_args['cta_text'];
$cta_url  = esc_url( $empty_args['cta_url'] );

$show_cta = ! empty( $cta_text ) && ! empty( $cta_url );
?>

<section class="content-empty">
    <h2 class="content-empty__title">
        <?php echo esc_html( $title ); ?>
    </h2>

    <?php if ( ! empty( $message ) ) : ?>
        <p class="content-empty__message">
            <?php echo esc_html( $message ); ?>
        </p>
    <?php endif; ?>

    <?php if ( $show_cta ) : ?>
        <a class="content-empty__cta" href="<?php echo $cta_url; ?>">
            <?php echo esc_html( $cta_text ); ?>
        </a>
    <?php endif; ?>
</section>
