<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php wp_title(); ?></title>
		<?php wp_head(); ?>

		<link rel="stylesheet" type="text/css" href="<?php echo happyforms_get_plugin_url() . '/inc/core/assets/css/notice.css'; ?>">
	</head>
	<body class="happyforms-preview">
		<?php global $post; $form = happyforms_get_form_controller()->get( $post->ID ); ?>
		<?php happyforms_the_form_styles( $form ); ?>
		<?php include( happyforms_get_include_folder() . '/core/templates/single-form.php' ); ?>

		<?php wp_footer(); ?>
	</body>
</html>
