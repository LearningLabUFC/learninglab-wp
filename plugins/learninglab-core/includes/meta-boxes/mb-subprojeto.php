<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function adicionar_meta_box_subprojeto() {
    add_meta_box(
        'subprojeto_info',
        'Informações do Subprojeto',
        'render_meta_box_subprojeto',
        'subprojetos',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'adicionar_meta_box_subprojeto' );


function render_meta_box_subprojeto( $post ) {
    wp_nonce_field( 'salvar_meta_subprojeto', 'subprojeto_nonce' );

    $link    = get_post_meta( $post->ID, '_subprojeto_link', true );
    $periodo = get_post_meta( $post->ID, '_subprojeto_periodo', true );
    ?>

    <p>
        <label for="subprojeto_link">
            <strong>Link externo:</strong>
        </label>
        <br>
        <input
            type="url"
            id="subprojeto_link"
            name="subprojeto_link"
            value="<?php echo esc_attr( $link ); ?>"
            style="width: 100%;"
        >
    </p>

    <p>
        <label for="subprojeto_periodo">
            <strong>Período:</strong>
        </label>
        <br>
        <input
            type="text"
            id="subprojeto_periodo"
            name="subprojeto_periodo"
            value="<?php echo esc_attr( $periodo ); ?>"
            placeholder="Ex.: 2025–2026"
            style="width: 100%;"
        >
    </p>

    <?php
}


function salvar_meta_subprojeto( $post_id ) {
    if (
        ! isset( $_POST['subprojeto_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['subprojeto_nonce'] ) ),
            'salvar_meta_subprojeto'
        )
    ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( get_post_type( $post_id ) !== 'subprojetos' ) {
        return;
    }

    if ( isset( $_POST['subprojeto_link'] ) ) {
        $link = esc_url_raw(
            wp_unslash( $_POST['subprojeto_link'] )
        );

        if ( $link ) {
            update_post_meta( $post_id, '_subprojeto_link', $link );
        } else {
            delete_post_meta( $post_id, '_subprojeto_link' );
        }
    }

    if ( isset( $_POST['subprojeto_periodo'] ) ) {
        $periodo = sanitize_text_field(
            wp_unslash( $_POST['subprojeto_periodo'] )
        );

        if ( $periodo !== '' ) {
            update_post_meta( $post_id, '_subprojeto_periodo', $periodo );
        } else {
            delete_post_meta( $post_id, '_subprojeto_periodo' );
        }
    }
}
add_action( 'save_post_subprojetos', 'salvar_meta_subprojeto' );
