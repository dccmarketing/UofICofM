<?php

/**
 * Implements hook_preprocess_maintenance_page().
 */
function uoficofm_preprocess_maintenance_page( &$variables ) {

	// By default, site_name is set to Drupal if no db connection is available
	// or during site installation. Setting site_name to an empty string makes
	// the site and update pages look cleaner.
	// @see template_preprocess_maintenance_page
	if ( ! $variables['db_is_active'] ) {

		$variables['site_name'] = '';

	}

	drupal_add_css(drupal_get_path( 'theme', 'uoficofm' ) . '/css/maintenance-page.css' );

} // uoficofm_preprocess_maintenance_page()

/**
 * Override or insert variables into the maintenance page template.
 */
function uoficofm_process_maintenance_page( &$variables ) { 

	// Always print the site name and slogan, but if they are toggled off, we'll
	// just hide them visually.
	$variables['hide_site_name']	 = theme_get_setting( 'toggle_name' ) ? FALSE : TRUE;
	$variables['hide_site_slogan'] = theme_get_setting( 'toggle_slogan' ) ? FALSE : TRUE;

	if ( $variables['hide_site_name'] ) {

		// If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
		$variables['site_name'] = filter_xss_admin( variable_get( 'site_name', 'Drupal' ) );

	}

	if ( $variables['hide_site_slogan'] ) {

		// If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
		$variables['site_slogan'] = filter_xss_admin( variable_get( 'site_slogan', '' ) );

	}

} // uoficofm_process_maintenance_page()

/**
 * Override or insert variables into the node template.
 */
function uoficofm_preprocess_node( &$variables ) {

	if ( $variables['view_mode'] == 'full' && node_is_page( $variables['node'] ) ) {

		$variables['classes_array'][] = 'node-full';

	}

} // uoficofm_preprocess_node()

/**
 * Override or insert variables into the block template.
 */
function uoficofm_preprocess_block( &$variables ) {

	// In the header region visually hide block titles.
	if ( $variables['block']->region == 'header' ) {

		$variables['title_attributes_array']['class'][] = 'element-invisible';

	}

} // uoficofm_preprocess_block()

/**
 * Implements theme_menu_tree().
 */
function uoficofm_menu_tree( &$variables ) {

	//var_dump( $variables );
	
	return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';

} // uoficofm_menu_tree()

function menu_tree__menu_info_for_menu( &$variables ) {

	//echo '<pre>'; print_r( $variables ); echo '</pre>';
	
	return '<span class="infoforlabel">Info For:</span><ul class="nav menu-info-for-menu">' . $variables['tree'] . '</ul>';

} // menu_tree__menu_info_for_menu()


/**
 * Implements hook_block_view_alter().
 */
function uoficofm_block_view_alter( &$data, $block ) {

	// Check we get the right menu block (side bar)
	if ( 'menu-info-for-menu' == $block->delta ) {

		//echo '<pre>'; print_r( $data ); echo '</pre>';
	
		// change the theme wrapper for the first level
		//$data['content']['#content']['#theme_wrappers'] = array( 'menu_tree__menu_block__1__level1' );
	
	}

}




/**
 * Implements theme_field__field_type().
 */
function uoficofm_field__taxonomy_term_reference( $variables ) {

	$output = '';

	// Render the label, if it's not hidden.
	if ( !$variables['label_hidden'] ) {

		$output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';

	}

	// Render the items.
	$output .= ( $variables['element']['#label_display'] == 'inline' ) ? '<ul class="links inline">' : '<ul class="links">';

	foreach ( $variables['items'] as $delta => $item ) {

		$output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render( $item ) . '</li>';

	} // foreach

	$output .= '</ul>';

	// Render the top-level DIV.
	$output = '<div class="' . $variables['classes'] . ( ! in_array( 'clearfix', $variables['classes_array'] ) ? ' clearfix' : '' ) . '"' . $variables['attributes'] .'>' . $output . '</div>';

	return $output;

} // uoficofm_field__taxonomy_term_reference()
