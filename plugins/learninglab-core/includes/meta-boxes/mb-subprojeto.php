<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registra a Meta Box de Subprojetos
 */
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

/**
 * Enfileira scripts necessários no painel de administração (jQuery UI Sortable)
 */
function carregar_recursos_meta_subprojeto( $hook ) {
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }

    $screen = get_current_screen();

    if ( ! $screen || 'subprojetos' !== $screen->post_type ) {
        return;
    }

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_style( 'dashicons' );
}
add_action( 'admin_enqueue_scripts', 'carregar_recursos_meta_subprojeto' );

/**
 * Renderiza os campos da Meta Box
 */
function render_meta_box_subprojeto( $post ) {
    wp_nonce_field( 'salvar_meta_subprojeto', 'subprojeto_nonce' );

    $periodo   = get_post_meta( $post->ID, '_subprojeto_periodo', true );
    $status    = get_post_meta( $post->ID, '_subprojeto_status', true );
    $link      = get_post_meta( $post->ID, '_subprojeto_link', true );
    $instagram = get_post_meta( $post->ID, '_subprojeto_instagram', true );
    $github    = get_post_meta( $post->ID, '_subprojeto_github', true );

    $lideres   = get_post_meta( $post->ID, '_subprojeto_lideres', true );
    if ( ! is_array( $lideres ) ) {
        $lideres = array();
    }

    $membros   = get_post_meta( $post->ID, '_subprojeto_membros', true );
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
        .subprojeto-mb-field {
            margin-bottom: 16px;
        }
        .subprojeto-mb-field label {
            font-weight: 600;
            display: inline-block;
            margin-bottom: 5px;
        }
        .subprojeto-mb-section {
            margin-top: 22px;
            padding-top: 16px;
            border-top: 1px solid #ddd;
        }
        .subprojeto-mb-section h3 {
            margin: 0 0 6px 0;
            font-size: 14px;
        }
        .subprojeto-meta-list {
            list-style: none;
            margin: 10px 0;
            padding: 0;
            max-width: 650px;
        }
        .subprojeto-meta-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f6f7f7;
            border: 1px solid #c3c4c7;
            padding: 8px 12px;
            margin-bottom: 6px;
            border-radius: 4px;
        }
        .subprojeto-meta-list .handle {
            color: #8c8f94;
            cursor: move;
        }
        .subprojeto-meta-list .membro-nome {
            min-width: 140px;
            font-weight: 500;
            overflow-wrap: anywhere;
        }
        .subprojeto-meta-list input[type="text"] {
            flex: 1;
            min-width: 110px;
            margin: 0;
            font-size: 13px;
        }
        .subprojeto-meta-list .remove-item {
            color: #d63638;
            cursor: pointer;
            border: none;
            background: transparent;
            padding: 0;
        }
        .subprojeto-meta-list .remove-item:hover {
            color: #b32d2e;
        }
        .subprojeto-meta-controls {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 8px;
        }
    </style>

    <!-- Período -->
    <div class="subprojeto-mb-field">
        <label for="subprojeto_periodo"><strong>Período de Atuação:</strong></label><br>
        <input
            type="text"
            id="subprojeto_periodo"
            name="subprojeto_periodo"
            value="<?php echo esc_attr( $periodo ); ?>"
            placeholder="Ex.: 2024–2025 ou 2025–Presente"
            style="width: 100%; max-width: 400px;"
        >
    </div>

    <!-- Status do Subprojeto -->
    <div class="subprojeto-mb-field">
        <label for="subprojeto_status"><strong>Status:</strong></label><br>
        <select id="subprojeto_status" name="subprojeto_status" style="max-width: 300px; width: 100%;">
            <option value="" <?php selected( $status, '' ); ?>>— Nenhum —</option>
            <option value="em_andamento" <?php selected( $status, 'em_andamento' ); ?>>Em andamento</option>
            <option value="concluido" <?php selected( $status, 'concluido' ); ?>>Concluído</option>
            <option value="planejamento" <?php selected( $status, 'planejamento' ); ?>>Em planejamento</option>
        </select>
    </div>

    <!-- Link Externo / Site Oficial -->
    <div class="subprojeto-mb-field">
        <label for="subprojeto_link"><strong>Link externo (Site Oficial / Página):</strong></label><br>
        <input
            type="url"
            id="subprojeto_link"
            name="subprojeto_link"
            value="<?php echo esc_attr( $link ); ?>"
            placeholder="https://..."
            style="width: 100%;"
        >
    </div>

    <!-- Instagram -->
    <div class="subprojeto-mb-field">
        <label for="subprojeto_instagram"><strong>Instagram:</strong></label><br>
        <input
            type="text"
            id="subprojeto_instagram"
            name="subprojeto_instagram"
            value="<?php echo esc_attr( $instagram ); ?>"
            placeholder="https://instagram.com/usuario ou @usuario"
            style="width: 100%; max-width: 500px;"
        >
        <p class="description">URL completa ou usuário (@perfil).</p>
    </div>

    <!-- Repositório GitHub (opcional) -->
    <div class="subprojeto-mb-field">
        <label for="subprojeto_github"><strong>Repositório GitHub (opcional):</strong></label><br>
        <input
            type="url"
            id="subprojeto_github"
            name="subprojeto_github"
            value="<?php echo esc_attr( $github ); ?>"
            placeholder="https://github.com/..."
            style="width: 100%;"
        >
    </div>

    <!-- Seção Liderança -->
    <div class="subprojeto-mb-section">
        <h3>Liderança do Subprojeto</h3>
        <p class="description">Membros responsáveis pela coordenação/liderança. Arraste para reordenar.</p>

        <ul id="lista-lideres-subprojeto" class="subprojeto-meta-list">
            <?php foreach ( $lideres as $item ) :
                $l_id    = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
                $l_cargo = isset( $item['cargo'] ) ? $item['cargo'] : '';

                if ( ! $l_id || get_post_type( $l_id ) !== 'membro' || get_post_status( $l_id ) !== 'publish' ) {
                    continue;
                }

                $l_nome = get_the_title( $l_id );
            ?>
                <li>
                    <span class="dashicons dashicons-menu handle" aria-label="Arrastar para reordenar"></span>
                    <span class="membro-nome"><?php echo esc_html( $l_nome ); ?></span>
                    <input
                        type="text"
                        name="subprojeto_lideres_cargos[]"
                        placeholder="Função (ex: Coordenador, Líder)"
                        value="<?php echo esc_attr( $l_cargo ); ?>"
                    >
                    <input type="hidden" name="subprojeto_lideres_ids[]" value="<?php echo esc_attr( $l_id ); ?>">
                    <button type="button" class="remove-item button-link-delete" aria-label="Remover">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="subprojeto-meta-controls">
            <select id="select-novo-lider" style="max-width: 300px;">
                <option value="">Selecione um membro...</option>
                <?php foreach ( $todos_membros as $m ) : ?>
                    <option value="<?php echo esc_attr( $m->ID ); ?>"><?php echo esc_html( $m->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="button" id="btn-add-lider-subprojeto">
                + Adicionar Líder
            </button>
        </div>
    </div>

    <!-- Seção Demais Membros -->
    <div class="subprojeto-mb-section">
        <h3>Demais Membros da Equipe</h3>
        <p class="description">Outros membros integrantes do subprojeto. Arraste para reordenar.</p>

        <ul id="lista-membros-subprojeto" class="subprojeto-meta-list">
            <?php foreach ( $membros as $item ) :
                $m_id    = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
                $m_cargo = isset( $item['cargo'] ) ? $item['cargo'] : '';

                if ( ! $m_id || get_post_type( $m_id ) !== 'membro' || get_post_status( $m_id ) !== 'publish' ) {
                    continue;
                }

                $m_nome = get_the_title( $m_id );
            ?>
                <li>
                    <span class="dashicons dashicons-menu handle" aria-label="Arrastar para reordenar"></span>
                    <span class="membro-nome"><?php echo esc_html( $m_nome ); ?></span>
                    <input
                        type="text"
                        name="subprojeto_membros_cargos[]"
                        placeholder="Função (ex: Pesquisador, Desenvolvedor)"
                        value="<?php echo esc_attr( $m_cargo ); ?>"
                    >
                    <input type="hidden" name="subprojeto_membros_ids[]" value="<?php echo esc_attr( $m_id ); ?>">
                    <button type="button" class="remove-item button-link-delete" aria-label="Remover">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="subprojeto-meta-controls">
            <select id="select-novo-membro-subprojeto" style="max-width: 300px;">
                <option value="">Selecione um membro...</option>
                <?php foreach ( $todos_membros as $m ) : ?>
                    <option value="<?php echo esc_attr( $m->ID ); ?>"><?php echo esc_html( $m->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="button" id="btn-add-membro-subprojeto">
                + Adicionar Membro
            </button>
        </div>
    </div>

    <script>
    jQuery( document ).ready( function( $ ) {
        var listaLideres = $( '#lista-lideres-subprojeto' );
        var listaMembros = $( '#lista-membros-subprojeto' );

        listaLideres.sortable( { handle: '.handle' } );
        listaMembros.sortable( { handle: '.handle' } );

        function adicionarItem( selectEl, targetList, idName, cargoName, placeholder ) {
            var id = selectEl.val();
            var nome = selectEl.find( 'option:selected' ).text();

            if ( ! id ) {
                alert( 'Selecione um membro.' );
                return;
            }

            if ( targetList.find( 'input[name="' + idName + '"][value="' + id + '"]' ).length ) {
                alert( 'Este membro já está adicionado nesta lista.' );
                return;
            }

            var li = $( '<li>' );
            li.append( '<span class="dashicons dashicons-menu handle" aria-label="Arrastar para reordenar"></span>' );
            li.append( $( '<span>', { class: 'membro-nome', text: nome } ) );
            li.append( $( '<input>', {
                type: 'text',
                name: cargoName,
                placeholder: placeholder
            } ) );
            li.append( $( '<input>', {
                type: 'hidden',
                name: idName,
                value: id
            } ) );
            li.append( '<button type="button" class="remove-item button-link-delete" aria-label="Remover"><span class="dashicons dashicons-trash"></span></button>' );

            targetList.append( li );
            selectEl.val( '' );
        }

        $( '#btn-add-lider-subprojeto' ).on( 'click', function() {
            adicionarItem(
                $( '#select-novo-lider' ),
                listaLideres,
                'subprojeto_lideres_ids[]',
                'subprojeto_lideres_cargos[]',
                'Função (ex: Coordenador, Líder)'
            );
        } );

        $( '#btn-add-membro-subprojeto' ).on( 'click', function() {
            adicionarItem(
                $( '#select-novo-membro-subprojeto' ),
                listaMembros,
                'subprojeto_membros_ids[]',
                'subprojeto_membros_cargos[]',
                'Função (ex: Pesquisador, Desenvolvedor)'
            );
        } );

        $( document ).on( 'click', '.subprojeto-meta-list .remove-item', function( e ) {
            e.preventDefault();
            $( this ).closest( 'li' ).remove();
        } );
    } );
    </script>
    <?php
}

/**
 * Salva os metadados do subprojeto
 */
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

    // Período
    if ( isset( $_POST['subprojeto_periodo'] ) ) {
        $periodo = sanitize_text_field( wp_unslash( $_POST['subprojeto_periodo'] ) );
        if ( $periodo !== '' ) {
            update_post_meta( $post_id, '_subprojeto_periodo', $periodo );
        } else {
            delete_post_meta( $post_id, '_subprojeto_periodo' );
        }
    }

    // Status
    if ( isset( $_POST['subprojeto_status'] ) ) {
        $status = sanitize_text_field( wp_unslash( $_POST['subprojeto_status'] ) );
        if ( in_array( $status, array( 'em_andamento', 'concluido', 'planejamento' ), true ) ) {
            update_post_meta( $post_id, '_subprojeto_status', $status );
        } else {
            delete_post_meta( $post_id, '_subprojeto_status' );
        }
    }

    // Link externo
    if ( isset( $_POST['subprojeto_link'] ) ) {
        $link = esc_url_raw( wp_unslash( $_POST['subprojeto_link'] ) );
        if ( $link ) {
            update_post_meta( $post_id, '_subprojeto_link', $link );
        } else {
            delete_post_meta( $post_id, '_subprojeto_link' );
        }
    }

    // Instagram
    if ( isset( $_POST['subprojeto_instagram'] ) ) {
        $instagram = sanitize_text_field( wp_unslash( $_POST['subprojeto_instagram'] ) );
        if ( $instagram !== '' ) {
            // Se o usuário digitou só @usuario ou usuario, transformar em URL completa ou manter amigável
            if ( ! preg_match( '#^https?://#i', $instagram ) ) {
                $usuario = ltrim( $instagram, '@' );
                $instagram = 'https://www.instagram.com/' . $usuario;
            }
            update_post_meta( $post_id, '_subprojeto_instagram', esc_url_raw( $instagram ) );
        } else {
            delete_post_meta( $post_id, '_subprojeto_instagram' );
        }
    }

    // GitHub
    if ( isset( $_POST['subprojeto_github'] ) ) {
        $github = esc_url_raw( wp_unslash( $_POST['subprojeto_github'] ) );
        if ( $github ) {
            update_post_meta( $post_id, '_subprojeto_github', $github );
        } else {
            delete_post_meta( $post_id, '_subprojeto_github' );
        }
    }

    // Liderança
    if ( isset( $_POST['subprojeto_lideres_ids'] ) && is_array( $_POST['subprojeto_lideres_ids'] ) ) {
        $lideres_salvos = array();
        $cargos = isset( $_POST['subprojeto_lideres_cargos'] ) && is_array( $_POST['subprojeto_lideres_cargos'] )
            ? array_map( 'sanitize_text_field', wp_unslash( $_POST['subprojeto_lideres_cargos'] ) )
            : array();

        foreach ( $_POST['subprojeto_lideres_ids'] as $index => $id_raw ) {
            $id = absint( $id_raw );
            if ( $id > 0 && get_post_type( $id ) === 'membro' ) {
                $cargo = isset( $cargos[ $index ] ) ? $cargos[ $index ] : '';
                $lideres_salvos[] = array(
                    'id'    => $id,
                    'cargo' => $cargo,
                );
            }
        }

        if ( ! empty( $lideres_salvos ) ) {
            update_post_meta( $post_id, '_subprojeto_lideres', $lideres_salvos );
        } else {
            delete_post_meta( $post_id, '_subprojeto_lideres' );
        }
    } else {
        delete_post_meta( $post_id, '_subprojeto_lideres' );
    }

    // Demais membros
    if ( isset( $_POST['subprojeto_membros_ids'] ) && is_array( $_POST['subprojeto_membros_ids'] ) ) {
        $membros_salvos = array();
        $cargos = isset( $_POST['subprojeto_membros_cargos'] ) && is_array( $_POST['subprojeto_membros_cargos'] )
            ? array_map( 'sanitize_text_field', wp_unslash( $_POST['subprojeto_membros_cargos'] ) )
            : array();

        foreach ( $_POST['subprojeto_membros_ids'] as $index => $id_raw ) {
            $id = absint( $id_raw );
            if ( $id > 0 && get_post_type( $id ) === 'membro' ) {
                $cargo = isset( $cargos[ $index ] ) ? $cargos[ $index ] : '';
                $membros_salvos[] = array(
                    'id'    => $id,
                    'cargo' => $cargo,
                );
            }
        }

        if ( ! empty( $membros_salvos ) ) {
            update_post_meta( $post_id, '_subprojeto_membros', $membros_salvos );
        } else {
            delete_post_meta( $post_id, '_subprojeto_membros' );
        }
    } else {
        delete_post_meta( $post_id, '_subprojeto_membros' );
    }
}
add_action( 'save_post_subprojetos', 'salvar_meta_subprojeto' );
