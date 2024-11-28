<?php
/**
 * The header
 *
 * @package Movies
 */

$id = get_the_ID();
$tel_link = get_field('tel_link', 'options');
$tel_icon = get_field('tel_icon', 'options');
$logo_mobile = get_field('logo_mobile', 'options');
$insert_header_code = get_field('insert_header_code', 'options');
$insert_after_body_code = get_field('insert_after_body_code', 'options');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php
		if ( !empty($insert_header_code) ):
			echo $insert_header_code;
		endif;

		wp_head();
	?>
</head>

<body <?php body_class(); ?>>
	<?php
		wp_body_open();

		if ( !empty($insert_header_code) ):
			echo $insert_after_body_code;
		endif;
	?>
	<!-- Wrapper start -->
	<div class="wrapper">
		<header class="header scrolled-down">
			<div class="header-container container">
				<div class="header-menu-wrapper">
					<div class="header-logo">
						<?php get_template_part( 'template-parts/logo' ); ?>
					</div>

					<div class="header-menu menu menu-body">
						<div class="mobile-menu-wrapper">
							<?php if ( has_nav_menu( 'primary' ) ): ?>
								<div class="nav-mobile-wrapper">
									<?php
										get_template_part( 'template-parts/navigation' );
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if ( !empty($tel_link) ):
					$target = $tel_link['target'] ? $tel_link['target']: '_blank';
					$url = $tel_link['url'] ? $tel_link['url']: '';
					$descr = $tel_link['title'] ? $tel_link['title']: ''; ?>

					<div class="header__link">
						<a
							class="button button__orange-light"
							href="<?php echo esc_url($url); ?>"
							target="<?php echo esc_attr($target); ?>"
							aria-label="<?php echo esc_html($descr); ?>"
						>
						<?php if ( $tel_icon ): ?>
							<span class="button__orange-icon">
								<img src="<?php echo $tel_icon; ?>" alt="Icon">
							</span>
						<?php endif; ?>
						<span><?php echo esc_html($descr); ?></span>
						</a>
					</div>
				<?php endif; ?>

				<div class="header-menu-button">
					<button type="button" title="Icon menu" class="icon-menu"><span></span></button>
				</div>

			</div>
		</header>
		<!-- Main start -->
		<main class="main">
