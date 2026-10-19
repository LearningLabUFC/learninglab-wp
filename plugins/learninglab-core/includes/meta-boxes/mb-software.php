<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function registrar_meta_box_software() {
    add_meta_box(
        'mb_software_detalhes',
        'Informações do Software',
        'render_meta_box_software',
        'software',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'registrar_meta_box_software' );


function carregar_recursos_meta_software( $hook ) {
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }

    $screen = get_current_screen();

    if ( ! $screen || 'software' !== $screen->post_type ) {
        return;
    }

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_style( 'dashicons' );
}
add_action( 'admin_enqueue_scripts', 'carregar_recursos_meta_software' );


function render_meta_box_software( $post ) {
    wp_nonce_field( 'salvar_meta_software', 'software_nonce' );

    $em_dev      = get_post_meta( $post->ID, '_software_em_desenvolvimento', true );
    $versao      = get_post_meta( $post->ID, '_software_versao', true );
    $link_repo   = get_post_meta( $post->ID, '_software_link_repo', true );
    $link_demo   = get_post_meta( $post->ID, '_software_link_demo', true );
    $tecnologias = get_post_meta( $post->ID, '_software_tecnologias', true );
    $membros     = get_post_meta( $post->ID, '_software_membros', true );

    if ( ! is_array( $membros ) ) {
        $membros = array();
    }

    $todos_membros = get_posts( array(
        'post_type'      => 'membro',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );

    ?>
    <style>
        .software-mb-field {
            margin-bottom: 15px;
        }

        .software-mb-field label {
            font-weight: 600;
            display: inline-block;
            margin-bottom: 4px;
        }

        .software-membros-wrapper {
            margin-top: 15px;
        }

        #lista-membros-software {
            list-style: none;
            margin: 10px 0;
            padding: 0;
            max-width: 600px;
        }

        #lista-membros-software li {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f6f7f7;
            border: 1px solid #c3c4c7;
            padding: 8px 12px;
            margin-bottom: 6px;
            border-radius: 4px;
        }

        #lista-membros-software .autor-handle {
            color: #8c8f94;
            cursor: move;
        }

        #lista-membros-software .autor-nome {
            min-width: 100px;
            overflow-wrap: anywhere;
        }

        #lista-membros-software .remove-autor {
            color: #d63638;
            cursor: pointer;
        }

        #lista-membros-software input[type="text"] {
            flex: 1;
            min-width: 100px;
            margin: 0;
            font-size: 12px;
        }
    </style>

    <div class="software-mb-field">
        <label>
            <input
                type="checkbox"
                name="software_em_desenvolvimento"
                value="1"
                <?php checked( $em_dev, '1' ); ?>
            >
            <strong>Em desenvolvimento</strong>
            (exibir badge em vez da versão)
        </label>
    </div>

    <div class="software-mb-field">
        <label for="software_versao"><strong>Versão:</strong></label><br>
        <input
            type="text"
            id="software_versao"
            name="software_versao"
            value="<?php echo esc_attr( $versao ); ?>"
            placeholder="ex: 1.2.0"
            style="width:200px;"
        >
        <p class="description">
            Ignorado enquanto "Em desenvolvimento" estiver marcado.
        </p>
    </div>

    <hr>

    <div class="software-mb-field">
        <label for="software_link_repo"><strong>Repositório:</strong></label><br>
        <input
            type="url"
            id="software_link_repo"
            name="software_link_repo"
            value="<?php echo esc_attr( $link_repo ); ?>"
            style="width:100%;"
        >
    </div>

    <div class="software-mb-field">
        <label for="software_link_demo"><strong>Link de demo / site:</strong></label><br>
        <input
            type="url"
            id="software_link_demo"
            name="software_link_demo"
            value="<?php echo esc_attr( $link_demo ); ?>"
            style="width:100%;"
        >
    </div>

    <div class="software-mb-field">
        <label for="software_tecnologias"><strong>Tecnologias:</strong></label><br>
        <input
            type="text"
            id="software_tecnologias"
            name="software_tecnologias"
            value="<?php echo esc_attr( $tecnologias ); ?>"
            placeholder="ex: PHP, WordPress, Vue.js"
            style="width:100%;"
        >
        <p class="description">Separe por vírgula.</p>
    </div>

    <hr>

    <div class="software-membros-wrapper">
        <label><strong>Membros envolvidos:</strong></label>

        <div style="margin:8px 0; display:flex; gap:8px; flex-wrap:wrap;">
            <select id="select-membros-software" style="max-width:300px;">
                <option value="">Selecione um membro...</option>

                <?php foreach ( $todos_membros as $m ) : ?>
                    <option value="<?php echo esc_attr( $m->ID ); ?>">
                        <?php echo esc_html( $m->post_title ); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button
                type="button"
                class="button"
                id="btn-add-membro-software"
            >
                Adicionar Membro
            </button>
        </div>

        <ul id="lista-membros-software">
            <?php foreach ( $membros as $item ) : ?>
                <?php
                $m_id = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
                $m_cargo = isset( $item['cargo'] )
                    ? $item['cargo']
                    : '';

                if (
                    ! $m_id ||
                    get_post_type( $m_id ) !== 'membro' ||
                    get_post_status( $m_id ) !== 'publish'
                ) {
                    continue;
                }

                $m_nome = get_the_title( $m_id );
                ?>

                <li>
                    <span
                        class="dashicons dashicons-menu autor-handle"
                        aria-label="Arrastar para reordenar"
                    ></span>

                    <span class="autor-nome">
                        <?php echo esc_html( $m_nome ); ?>
                    </span>

                    <input
                        type="text"
                        name="software_membros_cargos[]"
                        placeholder="Cargo (ex: PO, Dev)"
                        value="<?php echo esc_attr( $m_cargo ); ?>"
                        aria-label="<?php echo esc_attr( 'Cargo de ' . $m_nome ); ?>"
                    >

                    <input
                        type="hidden"
                        name="software_membros_ids[]"
                        value="<?php echo esc_attr( $m_id ); ?>"
                    >

                    <button
                        type="button"
                        class="button-link-delete remove-autor"
                        aria-label="<?php echo esc_attr( 'Remover ' . $m_nome ); ?>"
                    >
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
    jQuery(function($) {
        var lista = $('#lista-membros-software');
        var select = $('#select-membros-software');

        if ($.fn.sortable) {
            lista.sortable({
                handle: '.autor-handle',
                axis: 'y',
                tolerance: 'pointer'
            });
        }

        $('#btn-add-membro-software').on('click', function(e) {
            e.preventDefault();

            var id = select.val();
            var nome = select.find('option:selected').text();

            if (!id) {
                return;
            }

            var existe = false;

            lista.find('input[name="software_membros_ids[]"]').each(function() {
                if ($(this).val() === id) {
                    existe = true;
                    return false;
                }
            });

            if (existe) {
                alert('Este membro já foi adicionado.');
                return;
            }

            
            var item = $('<li>');
            var handle = $('<span>')
                .addClass('dashicons dashicons-menu autor-handle')
                .attr('aria-label', 'Arrastar para reordenar');

            var nomeElemento = $('<span>')
                .addClass('autor-nome')
                .text(nome);

            var cargo = $('<input>')
                .attr({
                    type: 'text',
                    name: 'software_membros_cargos[]',
                    placeholder: 'Cargo (ex: PO)',
                    'aria-label': 'Cargo de ' + nome
                });

            var idInput = $('<input>').attr({
                type: 'hidden',
                name: 'software_membros_ids[]'
            }).val(id);

            var remover = $('<button>')
                .attr({
                    type: 'button',
                    'aria-label': 'Remover ' + nome
                })
                .addClass('button-link-delete remove-autor')
                .append(
                    $('<span>').addClass('dashicons dashicons-trash')
                );

            item.append(
                handle,
                nomeElemento,
                cargo,
                idInput,
                remover
            );

            lista.append(item);
            select.val('');
        });

        lista.on('click', '.remove-autor', function(e) {
            e.preventDefault();
            $(this).closest('li').remove();
        });
    });
    </script>
    <?php
}


function salvar_meta_software( $post_id ) {
   
    if (
        ! isset( $_POST['software_nonce'] ) ||
        ! is_string( $_POST['software_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['software_nonce'] ) ),
            'salvar_meta_software'
        )
    ) {
        return;
    }

    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    
    if ( get_post_type( $post_id ) !== 'software' ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    
    $em_dev = isset( $_POST['software_em_desenvolvimento'] )
        ? '1'
        : '';

    update_post_meta(
        $post_id,
        '_software_em_desenvolvimento',
        $em_dev
    );

    
    if ( isset( $_POST['software_versao'] ) && is_string( $_POST['software_versao'] ) ) {
        update_post_meta(
            $post_id,
            '_software_versao',
            sanitize_text_field(
                wp_unslash( $_POST['software_versao'] )
            )
        );
    }

    
    foreach ( array( 'software_link_repo', 'software_link_demo' ) as $field ) {
        if ( ! isset( $_POST[ $field ] ) || ! is_string( $_POST[ $field ] ) ) {
            continue;
        }

        $url = esc_url_raw(
            trim( wp_unslash( $_POST[ $field ] ) ),
            array( 'http', 'https' )
        );

        if (
            $url === '' ||
            in_array(
                wp_parse_url( $url, PHP_URL_SCHEME ),
                array( 'http', 'https' ),
                true
            )
        ) {
            update_post_meta( $post_id, '_' . $field, $url );
        }
    }

    
    if ( isset( $_POST['software_tecnologias'] ) && is_string( $_POST['software_tecnologias'] ) ) {
        update_post_meta(
            $post_id,
            '_software_tecnologias',
            sanitize_text_field(
                wp_unslash( $_POST['software_tecnologias'] )
            )
        );
    }

    
    $ids = isset( $_POST['software_membros_ids'] )
        ? (array) wp_unslash( $_POST['software_membros_ids'] )
        : array();

    $cargos = isset( $_POST['software_membros_cargos'] )
        ? (array) wp_unslash( $_POST['software_membros_cargos'] )
        : array();

    $membros_salvos = array();
    $ids_processados = array();

    foreach ( $ids as $index => $valor_id ) {
        if ( ! is_scalar( $valor_id ) ) {
            continue;
        }

        $id = absint( $valor_id );

        if (
            ! $id ||
            isset( $ids_processados[ $id ] ) ||
            get_post_type( $id ) !== 'membro' ||
            get_post_status( $id ) !== 'publish'
        ) {
            continue;
        }

        $cargo = isset( $cargos[ $index ] ) && is_scalar( $cargos[ $index ] )
            ? sanitize_text_field( $cargos[ $index ] )
            : '';

        $membros_salvos[] = array(
            'id'    => $id,
            'cargo' => $cargo,
        );

        $ids_processados[ $id ] = true;
    }

    update_post_meta(
        $post_id,
        '_software_membros',
        $membros_salvos
    );
}
add_action( 'save_post_software', 'salvar_meta_software' );
