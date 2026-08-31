<?php
/*
Plugin Name: LearningLab Core
Description: Plugin principal para registro de CPTs, Taxonomias e Meta Boxes do LearningLab.
Version: 1.0.0
Author: Equipe LearningLab
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/cpts.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';