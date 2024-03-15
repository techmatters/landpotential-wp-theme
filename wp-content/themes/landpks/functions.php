<?php
/**
 * LandPKS functions and definitions.
 *
 * @package LandPKS
 * @since   1.0.0
 */

define( 'THEME_VERSION', '1.0.0' );

require_once __DIR__ . '/class-landpks.php';
require_once __DIR__ . '/includes/class-newsletter.php';
require_once __DIR__ . '/includes/settings/class-settings.php';
require_once __DIR__ . '/includes/settings/class-newsletter-settings.php';

// Google integration.
require_once __DIR__ . '/includes/class-google-recaptcha.php'; // Must be after settings page.
