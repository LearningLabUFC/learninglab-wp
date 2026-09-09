<?php
/*
 * Plugin Name: LearningLab Core
 * Description: Plugin principal do LearningLab — registra Custom Post Types, Taxonomias e Meta Boxes.
 * Version:     1.1.0
 * Author:      Equipe LearningLab
 * Author URI:  https://github.com/LearningLabUFC
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Custom Post Types
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types/cpt-membros.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types/cpt-cursos.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types/cpt-artigos.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types/cpt-avaliacoes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types/cpt-subprojetos.php';

// Taxonomias
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies/tax-tipo-membro.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies/tax-status-curso.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies/tax-evento-artigo.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies/tax-ano-artigo.php';

// Meta Boxes
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes/mb-membro.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes/mb-artigo.php';