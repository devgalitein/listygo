<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

/*-------------------------------------
	Typography Variable
-------------------------------------*/
$listygo = LISTYGO_THEME_PREFIX_VAR;

/* = Body Typo Area
=======================================================*/
$typo_body = json_decode( RDTListygo::$options['typo_body'], true );
if ($typo_body['font'] == 'Inherit') {
	$typo_body['font'] = 'Outfit';
} else {
	$typo_body['font'] = $typo_body['font'];
}

/* = Menu Typo Area
=======================================================*/
$typo_menu = json_decode( RDTListygo::$options['typo_menu'], true );
if ($typo_menu['font'] == 'Inherit') {
	$typo_menu['font'] = 'Outfit';
} else {
	$typo_menu['font'] = $typo_menu['font'];
}

/* = Heading Typo Area
=======================================================*/
$typo_heading = json_decode( RDTListygo::$options['typo_heading'], true );
if ($typo_heading['font'] == 'Inherit') {
	$typo_heading['font'] = 'Outfit';
} else {
	$typo_heading['font'] = $typo_heading['font'];
}
$typo_h1 = json_decode( RDTListygo::$options['typo_h1'], true );
if ($typo_h1['font'] == 'Inherit') {
	$typo_h1['font'] = 'Outfit';
} else {
	$typo_h1['font'] = $typo_h1['font'];
}
$typo_h2 = json_decode( RDTListygo::$options['typo_h2'], true );
if ($typo_h2['font'] == 'Inherit') {
	$typo_h2['font'] = 'Outfit';
} else {
	$typo_h2['font'] = $typo_h2['font'];
}
$typo_h3 = json_decode( RDTListygo::$options['typo_h3'], true );
if ($typo_h3['font'] == 'Inherit') {
	$typo_h3['font'] = 'Outfit';
} else {
	$typo_h3['font'] = $typo_h3['font'];
}
$typo_h4 = json_decode( RDTListygo::$options['typo_h4'], true );
if ($typo_h4['font'] == 'Inherit') {
	$typo_h4['font'] = 'Outfit';
} else {
	$typo_h4['font'] = $typo_h4['font'];
}
$typo_h5 = json_decode( RDTListygo::$options['typo_h5'], true );
if ($typo_h5['font'] == 'Inherit') {
	$typo_h5['font'] = 'Outfit';
} else {
	$typo_h5['font'] = $typo_h5['font'];
}
$typo_h6 = json_decode( RDTListygo::$options['typo_h6'], true );
if ($typo_h6['font'] == 'Inherit') {
	$typo_h6['font'] = 'Outfit';
} else {
	$typo_h6['font'] = $typo_h6['font'];
}

/*-------------------------------------
#. Listygo Primary Color Settings
---------------------------------------*/
$colorPrimary = RDTListygo::$options['listygo_primary_color'];
$colorSecondary = RDTListygo::$options['listygo_secondary_color'];
$colorBodyText = RDTListygo::$options['listygo_body_color'];
$colorHeading = RDTListygo::$options['listygo_heading_color'];

?>

:root {
	--body-font: <?php echo esc_html( $typo_body['font'] ? $typo_body['font'] : 'Outfit' ); ?>;
	--heading-font: <?php echo esc_html( $typo_heading['font'] ? $typo_heading['font'] : 'Outfit' ); ?>;
    --color-primary: <?php echo esc_html( $colorPrimary ? $colorPrimary : '#ff3c48' ); ?>;
    --color-secondary: <?php echo esc_html( $colorSecondary ? $colorSecondary : '#CC1119' ); ?>;
    --color-body: <?php echo esc_html( $colorBodyText ? $colorBodyText : '#646464' ); ?>;
    --color-heading: <?php echo esc_html( $colorHeading ? $colorHeading : '#111111' ); ?>;
}

body {
	font-family: '<?php echo esc_html( $typo_body['font'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( RDTListygo::$options['typo_body_size'] ) ?>;
	line-height: <?php echo esc_html( RDTListygo::$options['typo_body_height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_body['regularweight'] ) ?>;
	font-style: normal;
}

nav.site-nav > ul li a {
	font-family: '<?php echo esc_html( $typo_menu['font'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( RDTListygo::$options['typo_menu_size'] ) ?>;
	line-height: <?php echo esc_html( RDTListygo::$options['typo_menu_height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_menu['regularweight'] ) ?>;
	font-style: normal;
}

nav.site-nav>ul>li ul.children li a,
nav.site-nav>ul>li ul.sub-menu li a {
	font-family: '<?php echo esc_html( $typo_menu['font'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( RDTListygo::$options['typo_submenu_size'] ) ?>;
	line-height: <?php echo esc_html( RDTListygo::$options['typo_submenu_height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_menu['regularweight'] ) ?>;
	font-style: normal;
}

h1,h2,h3,h4,h5,h6 {
	font-family: '<?php echo esc_html( $typo_heading['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_heading['regularweight'] ); ?>;
}
<?php if (!empty($typo_h1['font'])) { ?>
h1 {
	font-family: '<?php echo esc_html( $typo_h1['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_h1['regularweight'] ); ?>;
}
<?php } ?>
h1 {
	font-size: <?php echo esc_html( RDTListygo::$options['typo_h1_size'] ); ?>;
	line-height: <?php echo esc_html(  RDTListygo::$options['typo_h1_height'] ); ?>;
	font-style: normal;
}
@media (max-width: 767px) {
	h1 {
		font-size: 40px;
		line-height: 1.2;
	}
}
<?php if (!empty($typo_h2['font'])) { ?>
h2 {
	font-family: '<?php echo esc_html( $typo_h2['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_h2['regularweight'] ); ?>;
}
<?php } ?>
h2 {
	font-size: <?php echo esc_html( RDTListygo::$options['typo_h2_size'] ); ?>;
	line-height: <?php echo esc_html( RDTListygo::$options['typo_h2_height'] ); ?>;
	font-style: normal;
}
@media (max-width: 767px) {
	h2 {
		font-size: 32px;
		line-height: 1.2;
	}
}
<?php if (!empty($typo_h3['font'])) { ?>
h3 {
	font-family: '<?php echo esc_html( $typo_h3['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_h3['regularweight'] ); ?>;
}
<?php } ?>
.rtcl .rtcl-grid-view .product-box .item-content h3,
.product-box .item-content h3,
h3 {
	font-size: <?php echo esc_html( RDTListygo::$options['typo_h3_size'] ); ?>;
	line-height: <?php echo esc_html(  RDTListygo::$options['typo_h3_height'] ); ?>;
	font-style: normal;
}
@media (max-width: 767px) {
	h3 {
		font-size: 24px;
		line-height: 1.2;
	}
}
<?php if (!empty($typo_h4['font'])) { ?>
h4 {
	font-family: '<?php echo esc_html( $typo_h4['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_h4['regularweight'] ); ?>;
}
<?php } ?>
h4 {
	font-size: <?php echo esc_html( RDTListygo::$options['typo_h4_size'] ); ?>;
	line-height: <?php echo esc_html(  RDTListygo::$options['typo_h4_height'] ); ?>;
	font-style: normal;
}
@media (max-width: 767px) {
	h4 {
		font-size: 22px;
		line-height: 1.2;
	}
}
<?php if (!empty($typo_h5['font'])) { ?>
h5 {
	font-family: '<?php echo esc_html( $typo_h5['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_h5['regularweight'] ); ?>;
}
<?php } ?>
h5 {
	font-size: <?php echo esc_html( RDTListygo::$options['typo_h5_size'] ); ?>;
	line-height: <?php echo esc_html(  RDTListygo::$options['typo_h5_height'] ); ?>;
	font-style: normal;
}
<?php if (!empty($typo_h6['font'])) { ?>
h6 {
	font-family: '<?php echo esc_html( $typo_h6['font'] ); ?>', sans-serif !important;
	font-weight : <?php echo esc_html( $typo_h6['regularweight'] ); ?>;
}
@media (max-width: 575px) {
	h5 {
		font-size: 1.2;
	}
}
<?php } ?>
h6 {
	font-size: <?php echo esc_html( RDTListygo::$options['typo_h6_size'] ); ?>;
	line-height: <?php echo esc_html(  RDTListygo::$options['typo_h6_height'] ); ?>;
	font-style: normal;
}

<?php 
	/*-------------------------------------
	#. Listygo Color Settings
	---------------------------------------*/
	// Normal Menu Color
	$menu_bg_color = RDTListygo::$options['menu_bg_color'];
	$menu_text_color = RDTListygo::$options['menu_text_color'];
	$menu_text_hover_color = RDTListygo::$options['menu_text_hover_color'];
	// Sub Menu Color
	$submenu_bg_color = RDTListygo::$options['submenu_bg_color'];
	$submenu_text_color = RDTListygo::$options['submenu_text_color'];
	$submenu_htext_color = RDTListygo::$options['submenu_htext_color'];

	// Tranparent menu color
	$menu2_text_color = RDTListygo::$options['menu2_text_color'];
	$menu2_text_hover_color = RDTListygo::$options['menu2_text_hover_color'];
	// Sub Menu Color
	$submenu2_bg_color = RDTListygo::$options['submenu2_bg_color'];
	$submenu2_text_color = RDTListygo::$options['submenu2_text_color'];
	$submenu2_htext_color = RDTListygo::$options['submenu2_htext_color'];

	// Preloader
	$preloader_bg_color = RDTListygo::$options['preloader_bg_color'];
	$preloader_circle_color = RDTListygo::$options['preloader_circle_color'];
	//ScrollUp color
	$scroll_color = RDTListygo::$options['scroll_color'];

?>

<?php
/* = Normal Menu Color
============================================================= */
	if ( !empty( $menu_bg_color )) {
?>
.header-main {
	background-color: <?php echo esc_html( $menu_bg_color ); ?>;
}
<?php }
	/* = Normal Menu Color
	==============================================*/
	if ( !empty( $menu_text_color )) {
?>
.header-main .site-nav .main-menu>li>a {
	color: <?php echo esc_html( $menu_text_color ); ?>;
}
<?php } if ( !empty( $menu_text_hover_color )) { ?>
.header-main .site-nav .main-menu>li>a:hover {
	color: <?php echo esc_html( $menu_text_hover_color ); ?>;
}
<?php }
	if ( !empty( $submenu_bg_color )) {
	/* = Listygo Dropdown Menu Color
	==============================================*/
?>

.header-main .site-nav .main-menu>li ul.children, 
.header-main .site-nav .main-menu>li ul.sub-menu {
	background-color: <?php echo esc_html( $submenu_bg_color ); ?>;
}
<?php } if ( !empty( $submenu_text_color )) { ?>
.header-main .site-nav .main-menu>li ul.children li a, 
.header-main .site-nav .main-menu>li ul.sub-menu li a {
	color: <?php echo esc_html( $submenu_text_color ); ?>;
}
<?php } if ( !empty( $submenu_htext_color )) { ?>
.header-main .site-nav .main-menu>li ul.children li a:hover, 
.header-main .site-nav .main-menu>li ul.sub-menu li a:hover {
	color: <?php echo esc_html( $submenu_htext_color ); ?>;
}
.header-main .site-nav .main-menu>li ul.children li a:before, 
.header-main .site-nav .main-menu>li ul.sub-menu li a:before {
	background-color: <?php echo esc_html( $submenu_htext_color ); ?>;
}
<?php } ?>



<?php
/* = Transparent Menu Color
============================================================= */
if ( !empty( $menu2_text_color )) {
?>
.transparent-header .header-area .site-nav .main-menu>li>a {
	color: <?php echo esc_html( $menu2_text_color ); ?>;
}
<?php } if ( !empty( $menu2_text_hover_color )) { ?>
.transparent-header .header-area .site-nav .main-menu>li>a:hover {
	color: <?php echo esc_html( $menu2_text_hover_color ); ?>;
}
<?php }
	if ( !empty( $submenu2_bg_color )) {
	/* = Listygo Dropdown Menu Color
	==============================================*/
?>
.transparent-header .header-main .site-nav .main-menu>li ul.children, 
.transparent-header .header-main .site-nav .main-menu>li ul.sub-menu {
	background-color: <?php echo esc_html( $submenu2_bg_color ); ?>;
}
<?php } if ( !empty( $submenu2_text_color )) { ?>
.transparent-header .header-main .site-nav .main-menu>li ul.children li a, 
.transparent-header .header-main .site-nav .main-menu>li ul.sub-menu li a {
	color: <?php echo esc_html( $submenu2_text_color ); ?>;
}
<?php } if ( !empty( $submenu2_htext_color )) { ?>
.transparent-header .header-main .site-nav .main-menu>li ul.children li a:hover, 
.transparent-header .header-main .site-nav .main-menu>li ul.sub-menu li a:hover {
	color: <?php echo esc_html( $submenu2_htext_color ); ?>;
}
.transparent-header .header-main .site-nav .main-menu>li ul.children li a:before, 
.transparent-header .header-main .site-nav .main-menu>li ul.sub-menu li a:before {
	background-color: <?php echo esc_html( $submenu2_htext_color ); ?>;
}
<?php } ?>

<?php
	if ( !empty( $preloader_bg_color )) {
	/* = Listygo Dropdown Menu Color
	==============================================*/
	?>
	.pageoverlay .overlayDoor:after, 
	.pageoverlay .overlayDoor:before {
		background-color: <?php echo esc_html( $preloader_bg_color ); ?>;
	}
	<?php } if ( !empty( $preloader_circle_color )) { ?>
		.pageloader {
			border-color: <?php echo esc_html( $preloader_circle_color ); ?>;
			border-bottom-color: transparent;
		}
		.pageloader .inner {
			border-top-color: <?php echo esc_html( $preloader_circle_color ); ?>;
		}
	<?php }
?>


<?php
	if ( !empty( $scroll_color )) {
	/* = Listygo Dropdown Menu Color
	==============================================*/
	?>
	.scrollup {
		color: <?php echo esc_html( $scroll_color ); ?>;
	}
<?php } ?>

<?php
	if ( !empty( $colorPrimary )) {
	/* = Primary Color
	==============================================*/
	?>
	a:active, 
	a:focus, 
	a:hover,
	a {
		color: <?php echo esc_html( $colorPrimary ); ?>;
	}
<?php } ?>






