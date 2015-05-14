<?php

/**
 * Provide a dashboard view for the plugin
 *
 *
 * @link       https://github.com/pehaa/pht-simple-widget-areas
 * @since      1.0.0
 *
 * @package    PHT_Simple_Widget_Areas
 * @subpackage PHT_Simple_Widget_Areas/admin/partials
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="phtswa-container" class="phtswa-container">

	<h3><?php _e( 'PHT Simple Widget Areas', $this->name ); ?></h3>

	<?php do_action( $this->name . '_add_simple_widget_areas_start' ); ?>
	
	<?php $this->get_notification(); ?>

	<?php if ( is_array( self::$pht_simple_widget_areas ) ) : ?>

		<div class="phtswa-table">
			<h3><?php _e( 'New custom widgetized area:', $this->name ); ?></h3>
			<table>
		
				<tr>
					<th><?php _e( 'Name', $this->name ); ?></th>
					<th><?php _e( 'Id', $this->name ); ?></th>
					<th></th>
				</tr>
			<?php foreach ( self::$pht_simple_widget_areas as $sidebar ) : 
				$delete_path = wp_nonce_url( admin_url( 'widgets.php?deleteid=' . $sidebar['id'] ), $this->name .'_delete_nonce' ); ?>
				<tr>
					<td><?php echo $sidebar['name']; ?></td>
					<td><?php echo $sidebar['id']; ?></td>
					<td><a id="remove-<?php echo $sidebar[ 'id' ]?>" data-remove="<?php echo $sidebar[ 'id' ]?>" class="js-phtswa-confirm phtswa-remove-button" href="<?php echo $delete_path; ?>"><i class="dashicons dashicons-trash"></i></a></td>
				</tr>
			
			<?php endforeach; ?>
		
			</table>
		</div>	
	
	<?php endif; ?>
	
	<form id="phtswa-form" class="phtswa-form" data-actiontype="add" method="POST">

		<?php wp_nonce_field( $this->nonce ); ?>
		
			<label for="<?php echo $this->post_field; ?>"><?php _e( 'New custom widgetized area:' , $this->name); ?></label>
			<input id="<?php echo $this->post_field; ?>" name="<?php echo $this->post_field; ?>" type="text" placeholder="<?php _e( 'Put a unique name here', $this->name );?>" value="<?php echo isset( $_POST['phtswa-sidebar-name']) ? $_POST['phtswa-sidebar-name'] : ''; ?>"/>
			<input id="<?php echo $this->submit;?>" type="submit" class="button-primary" name="<?php echo $this->submit;?>" value="<?php _e( 'Add', $this->name ); ?>" />
			
	</form>

</div>
