<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

$blog_style = RDTListygo::$options['blog_style'];

get_template_part( 'template-parts/archive-blog/content',  $blog_style ); 

?>
