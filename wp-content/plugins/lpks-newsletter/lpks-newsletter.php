<?php
/**
 * LandPKS WordPress plugin.
 *
 * @package LPKS
 */

/**
 * Plugin Name:       LandPKS
 * Plugin URI:        https://landpotential.org/
 * Description:       LandPKS Newsletter
 * Author:            Tech Matters, paulschreiber
 * Text Domain:       lpks
 * Domain Path:       /languages
 * Version:           1.0.0
 * Requires at least: 6.4
 *
 * @package         LPKS
 */

defined( 'ABSPATH' ) || exit;
define( 'LPKS_PLUGIN_VERSION', '1.0.0' );

require_once __DIR__ . '/includes/class-newsletter.php';
require_once __DIR__ . '/includes/settings/class-settings.php';
require_once __DIR__ . '/includes/settings/class-newsletter-settings.php';

// Google integration.
require_once __DIR__ . '/includes/class-google-recaptcha.php'; // Must be after settings page.
