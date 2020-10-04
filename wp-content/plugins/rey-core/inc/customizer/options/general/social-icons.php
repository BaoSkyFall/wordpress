<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$section = 'social_icons';

ReyCoreKirki::add_section($section, [
	'title'          => esc_html__('Site Social Icons', 'rey-core'),
	'priority'       => 130,
	'panel'			 => 'general_options',
]);

// style - minimal, boxed (cu bg color)
// add icons - repeater, image, shortcut (ig.) title, link, color
// position - left/right
// distance left + top
// title (follow us)
// color
// difference
