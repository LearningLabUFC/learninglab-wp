<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ---------------------------------------------------------------
 * Meta Box: Informações do Curso
 * --------------------------------------------------------------- */

function adicionar_meta_box_curso() {
    add_meta_box(
        'curso_info',
        'Informações do Curso',
        'render_meta_box_curso',
        'curso',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'adicionar_meta_box_curso' );

function render_meta_box_curso( $post ) {
    wp_nonce_field( 'salvar_meta_curso', 'curso_nonce' );

    $link_inscricao = get_post_meta(
        $post->ID,
        '_curso_link_inscricao',
        true
    );

    $carga_horaria = get_post_meta(
        $post->ID,
        '_curso_carga_horaria',
        true
    );

    $data_inicio = get_post_meta(
        $post->ID,
        '_curso_data_inicio',
        true
    );

    $instrutores = get_post_meta(
        $post->ID,
        '_curso_instrutores',
        true
    );

    if ( ! is_array( $instrutores ) ) {
        $instrutores = array();
    }

    $monitores = get_post_meta(
        $post->ID,
        '_curso_monitores',
        true
    );

    if ( ! is_array( $monitores ) ) {
        $monitores = array();
    }

    $todos_membros = get_posts(
        array(
            'post_type'      => 'membro',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );
    ?>

    <style>
        .curso-meta-section {
            margin-top: 20px;
        }

        .curso-meta-section:first-child {
            margin-top: 0;
        }

        .curso-meta-list {
            list-style: none;
            margin: 10px 0 15px;
            padding: 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
            min-height: 45px;
        }

        .curso-meta-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px;
            margin: 0;
            border-bottom: 1px solid #eee;
            background: #fff;
        }

        .curso-meta-list li:last-child {
            border-bottom: none;
        }

        .curso-meta-handle {
            padding-top: 5px;
            color: #999;
            cursor: grab;
        }

        .curso-meta-content {
            flex: 1;
        }

        .curso-meta-name {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .curso-meta-description {
            width: 100%;
            min-height: 60px;
            box-sizing: border-box;
        }

        .curso-meta-remove {
            color: #d63638;
            cursor: pointer;
            padding-top: 5px;
        }

        .curso-meta-controls {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .curso-meta-controls select {
            flex: 1;
        }

        .curso-meta-empty {
            padding: 12px;
            color: #777;
            text-align: center;
            font-style: italic;
        }
    </style>

    <div class="curso-meta-section">

        <p>
            <label for="curso_link_inscricao">
                <strong>Link de Inscrição:</strong>
            </label>
            <br>

            <input
                type="url"
                id="curso_link_inscricao"
                name="curso_link_inscricao"
                value="<?php echo esc_attr( $link_inscricao ); ?>"
                placeholder="https://exemplo.com/inscricao"
                style="width: 100%;"
            >
        </p>

        <p>
            <label for="curso_carga_horaria">
                <strong>Carga Horária:</strong>
            </label>
            <br>

            <input
                type="text"
                id="curso_carga_horaria"
                name="curso_carga_horaria"
                value="<?php echo esc_attr( $carga_horaria ); ?>"
                placeholder="Ex.: 40 horas"
                style="width: 250px;"
            >
        </p>

        <p>
            <label for="curso_data_inicio">
                <strong>Data de Início:</strong>
            </label>
            <br>

            <input
                type="date"
                id="curso_data_inicio"
                name="curso_data_inicio"
                value="<?php echo esc_attr( $data_inicio ); ?>"
            >
        </p>

    </div>

    <hr>

    <div class="curso-meta-section">

        <h3>Instrutores do Curso</h3>

        <p class="description">
            Selecione os membros que serão instrutores e adicione uma
            descrição específica sobre a atuação de cada um neste curso.
        </p>

        <ul id="lista-instrutores-curso" class="curso-meta-list">

            <?php foreach ( $instrutores as $index => $instrutor ) : ?>

                <?php
                $membro_id = isset( $instrutor['id'] )
                    ? (int) $instrutor['id']
                    : 0;

                $descricao = isset( $instrutor['descricao'] )
                    ? $instrutor['descricao']
                    : '';

                if (
                    ! $membro_id ||
                    get_post_type( $membro_id ) !== 'membro'
                ) {
                    continue;
                }

                $nome = get_the_title( $membro_id );

                if ( ! $nome ) {
                    continue;
                }
                ?>

                <li>

                    <span class="dashicons dashicons-menu curso-meta-handle"></span>

                    <div class="curso-meta-content">

                        <span class="curso-meta-name">
                            <?php echo esc_html( $nome ); ?>
                        </span>

                        <textarea
                            name="curso_instrutores_descricoes[]"
                            class="curso-meta-description"
                            placeholder="Descrição/minibio deste instrutor no curso..."
                        ><?php echo esc_textarea( $descricao ); ?></textarea>

                    </div>

                    <input
                        type="hidden"
                        name="curso_instrutores_ids[]"
                        value="<?php echo esc_attr( $membro_id ); ?>"
                    >

                    <span
                        class="dashicons dashicons-trash curso-meta-remove"
                        title="Remover"
                    ></span>

                </li>

            <?php endforeach; ?>

        </ul>

        <div class="curso-meta-controls">

            <select id="select-novo-instrutor">
                <option value="">Selecione um membro...</option>

                <?php foreach ( $todos_membros as $membro ) : ?>

                    <option value="<?php echo esc_attr( $membro->ID ); ?>">
                        <?php echo esc_html( $membro->post_title ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <button
                type="button"
                class="button button-primary"
                id="btn-add-instrutor"
            >
                + Adicionar Instrutor
            </button>

        </div>

    </div>

    <hr>

    <div class="curso-meta-section">

        <h3>Monitores do Curso</h3>

        <p class="description">
            Selecione os membros que serão exibidos como monitores deste curso.
        </p>

        <ul id="lista-monitores-curso" class="curso-meta-list">

            <?php foreach ( $monitores as $monitor_id ) : ?>

                <?php
                $monitor_id = (int) $monitor_id;

                if (
                    ! $monitor_id ||
                    get_post_type( $monitor_id ) !== 'membro'
                ) {
                    continue;
                }

                $nome = get_the_title( $monitor_id );

                if ( ! $nome ) {
                    continue;
                }
                ?>

                <li>

                    <span class="dashicons dashicons-menu curso-meta-handle"></span>

                    <div class="curso-meta-content">
                        <span class="curso-meta-name">
                            <?php echo esc_html( $nome ); ?>
                        </span>
                    </div>

                    <input
                        type="hidden"
                        name="curso_monitores_ids[]"
                        value="<?php echo esc_attr( $monitor_id ); ?>"
                    >

                    <span
                        class="dashicons dashicons-trash curso-meta-remove"
                        title="Remover"
                    ></span>

                </li>

            <?php endforeach; ?>

        </ul>

        <div class="curso-meta-controls">

            <select id="select-novo-monitor">
                <option value="">Selecione um membro...</option>

                <?php foreach ( $todos_membros as $membro ) : ?>

                    <option value="<?php echo esc_attr( $membro->ID ); ?>">
                        <?php echo esc_html( $membro->post_title ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <button
                type="button"
                class="button button-primary"
                id="btn-add-monitor"
            >
                + Adicionar Monitor
            </button>

        </div>

    </div>

    <script>
    jQuery( document ).ready( function( $ ) {

        var instrutores = $( '#lista-instrutores-curso' );
        var monitores = $( '#lista-monitores-curso' );

        instrutores.sortable( {
            handle: '.curso-meta-handle',
            placeholder: 'ui-state-highlight'
        } );

        monitores.sortable( {
            handle: '.curso-meta-handle',
            placeholder: 'ui-state-highlight'
        } );

        $( '#btn-add-instrutor' ).on( 'click', function() {

            var select = $( '#select-novo-instrutor' );
            var id = select.val();
            var nome = select.find( 'option:selected' ).text();

            if ( ! id ) {
                alert( 'Selecione um membro.' );
                return;
            }

            if (
                instrutores.find(
                    'input[name="curso_instrutores_ids[]"][value="' + id + '"]'
                ).length
            ) {
                alert( 'Este membro já está cadastrado como instrutor.' );
                return;
            }

            var li = $( '<li>' );

            li.append(
                '<span class="dashicons dashicons-menu curso-meta-handle"></span>'
            );

            var content = $( '<div>', {
                class: 'curso-meta-content'
            } );

            $( '<span>', {
                class: 'curso-meta-name',
                text: nome
            } ).appendTo( content );

            $( '<textarea>', {
                name: 'curso_instrutores_descricoes[]',
                class: 'curso-meta-description',
                placeholder: 'Descrição/minibio deste instrutor no curso...'
            } ).appendTo( content );

            li.append( content );

            $( '<input>', {
                type: 'hidden',
                name: 'curso_instrutores_ids[]',
                value: id
            } ).appendTo( li );

            $( '<span>', {
                class: 'dashicons dashicons-trash curso-meta-remove',
                title: 'Remover'
            } ).appendTo( li );

            instrutores.append( li );

            select.val( '' );
        } );

        $( '#btn-add-monitor' ).on( 'click', function() {

            var select = $( '#select-novo-monitor' );
            var id = select.val();
            var nome = select.find( 'option:selected' ).text();

            if ( ! id ) {
                alert( 'Selecione um membro.' );
                return;
            }

            if (
                monitores.find(
                    'input[name="curso_monitores_ids[]"][value="' + id + '"]'
                ).length
            ) {
                alert( 'Este membro já está cadastrado como monitor.' );
                return;
            }

            var li = $( '<li>' );

            li.append(
                '<span class="dashicons dashicons-menu curso-meta-handle"></span>'
            );

            var content = $( '<div>', {
                class: 'curso-meta-content'
            } );

            $( '<span>', {
                class: 'curso-meta-name',
                text: nome
            } ).appendTo( content );

            li.append( content );

            $( '<input>', {
                type: 'hidden',
                name: 'curso_monitores_ids[]',
                value: id
            } ).appendTo( li );

            $( '<span>', {
                class: 'dashicons dashicons-trash curso-meta-remove',
                title: 'Remover'
            } ).appendTo( li );

            monitores.append( li );

            select.val( '' );
        } );

        $( document ).on(
            'click',
            '.curso-meta-remove',
            function() {
                $( this ).closest( 'li' ).remove();
            }
        );

    } );
    </script>

    <?php
}

/* ---------------------------------------------------------------
 * Salvamento da Meta Box
 * --------------------------------------------------------------- */

function salvar_meta_curso( $post_id ) {

    if (
        ! isset( $_POST['curso_nonce'] ) ||
        ! wp_verify_nonce(
            $_POST['curso_nonce'],
            'salvar_meta_curso'
        )
    ) {
        return;
    }

    if (
        defined( 'DOING_AUTOSAVE' ) &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        ! current_user_can(
            'edit_post',
            $post_id
        )
    ) {
        return;
    }

    /* -----------------------------------------------------------
     * Dados básicos
     * ----------------------------------------------------------- */

    if ( isset( $_POST['curso_link_inscricao'] ) ) {

        $link = esc_url_raw(
            wp_unslash( $_POST['curso_link_inscricao'] )
        );

        if ( $link ) {
            update_post_meta(
                $post_id,
                '_curso_link_inscricao',
                $link
            );
        } else {
            delete_post_meta(
                $post_id,
                '_curso_link_inscricao'
            );
        }
    }

    if ( isset( $_POST['curso_carga_horaria'] ) ) {

        $carga_horaria = sanitize_text_field(
            wp_unslash( $_POST['curso_carga_horaria'] )
        );

        if ( $carga_horaria !== '' ) {
            update_post_meta(
                $post_id,
                '_curso_carga_horaria',
                $carga_horaria
            );
        } else {
            delete_post_meta(
                $post_id,
                '_curso_carga_horaria'
            );
        }
    }

    if ( isset( $_POST['curso_data_inicio'] ) ) {

        $data_inicio = sanitize_text_field(
            wp_unslash( $_POST['curso_data_inicio'] )
        );

        if ( $data_inicio !== '' ) {
            update_post_meta(
                $post_id,
                '_curso_data_inicio',
                $data_inicio
            );
        } else {
            delete_post_meta(
                $post_id,
                '_curso_data_inicio'
            );
        }
    }

    /* -----------------------------------------------------------
     * Instrutores
     * ----------------------------------------------------------- */

    $instrutores_salvos = array();

    $ids = isset( $_POST['curso_instrutores_ids'] )
        ? array_map(
            'intval',
            (array) $_POST['curso_instrutores_ids']
        )
        : array();

    $descricoes = isset( $_POST['curso_instrutores_descricoes'] )
        ? array_map(
            'sanitize_textarea_field',
            (array) $_POST['curso_instrutores_descricoes']
        )
        : array();

    foreach ( $ids as $index => $id ) {

        if (
            ! $id ||
            get_post_type( $id ) !== 'membro' ||
            get_post_status( $id ) !== 'publish'
        ) {
            continue;
        }

        $instrutores_salvos[] = array(
            'id'        => $id,
            'descricao' => isset( $descricoes[ $index ] )
                ? $descricoes[ $index ]
                : '',
        );
    }

    update_post_meta(
        $post_id,
        '_curso_instrutores',
        $instrutores_salvos
    );

    /* -----------------------------------------------------------
     * Monitores
     * ----------------------------------------------------------- */

    $monitores_salvos = array();

    $ids = isset( $_POST['curso_monitores_ids'] )
        ? array_map(
            'intval',
            (array) $_POST['curso_monitores_ids']
        )
        : array();

    foreach ( $ids as $id ) {

        if (
            ! $id ||
            get_post_type( $id ) !== 'membro' ||
            get_post_status( $id ) !== 'publish'
        ) {
            continue;
        }

        $monitores_salvos[] = $id;
    }

    update_post_meta(
        $post_id,
        '_curso_monitores',
        $monitores_salvos
    );
}

add_action(
    'save_post_curso',
    'salvar_meta_curso'
);