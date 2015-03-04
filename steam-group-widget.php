<?php
/*
Plugin Name: Steam Community Group Widget
Plugin URI: http://panotlet.tk/steam-group-widget/
Description: Show your group like a widget!
Version: 1.00
Author: Paweł Otlewski
Author URI: http://panotlet.tk/
*/

class otletsg_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'otletsg_widget',
			__('Steam Group', 'otletsg_widget_domain'),
			array( 'description' => __( 'Steam Group Widget to show a group from Steam', 'otletsg_widget_domain' ), ) 
		);
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Steam Group', 'otletsg_widget_domain' );
		}
		if ( isset( $instance[ 'adress' ] ) ) {
			$adress = $instance[ 'adress' ];
		}
		else {
			$adress = __( 'http://example.com', 'otletsg_widget_domain' );
		}
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'adress' ); ?>">Adress</label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'adress' ); ?>" name="<?php echo $this->get_field_name( 'adress' ); ?>" type="text" value="<?php echo esc_attr( $adress ); ?>" />
	</p>
	<?php 
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$groupURL = apply_filters( 'widget_text', $instance['adress'] );
		$xml = simplexml_load_file($groupURL . '/memberslistxml/');
		echo '
		<table>
			<tr>
				<td rowspan="5" width="30%"><img src="'.$xml->groupDetails->avatarFull.'" width="100%">   </td>
				<td><strong>Group Name:</strong> '.$xml->groupDetails->groupName.'</td>
			</tr>
			<tr>
				<td><strong>Member count:</strong> '.$xml->groupDetails->memberCount.'</td>
			</tr>
			<tr>
				<td><strong>In Game:</strong> '.$xml->groupDetails->membersInGame.'</td>
			</tr>
			<tr>
				<td><strong>Online:</strong> '.$xml->groupDetails->membersOnline.'</td>
			</tr>
			<tr>
				<td><a href="' . $groupURL . '">Show this group</a></td>
			</tr>
		</table>';
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['adress'] = ( ! empty( $new_instance['adress'] ) ) ? strip_tags( $new_instance['adress'] ) : '';
		return $instance;
		}
	}

	function otletsg_load_widget() {
		register_widget( 'otletsg_widget' );
	}
	add_action( 'widgets_init', 'otletsg_load_widget' );
