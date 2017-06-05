<?php
### Class: WP-Polls Widget
 class WP_Widget_Polls_SVJ_Ownership extends WP_Widget {
    // Constructor
    function __construct() {
        $widget_ops = array('description' => __('Polls SVJ Unit Ownership Widget shows owned units and voting ratio', 'wp-polls'));
        parent::__construct('svj-polls-ownership-widget', __('Polls SVJ Ownership', 'wp-polls'), $widget_ops);
    }

    // Display Widget - this is how the widget is actually displayed
    function widget( $args, $instance ) {
		global $wpdb;
        $title = apply_filters( 'widget_title', esc_attr( $instance['title'] ) );
        $text_before = wp_kses_post(removeslashes( $instance['text-before'] ));
        $code_before = wp_kses_post(removeslashes( $instance['code-before'] ));
        $line_template = wp_kses_post(removeslashes($instance['line-template'])) ;
        $code_after = wp_kses_post(removeslashes( $instance['code-after'] ));
        $text_after = wp_kses_post(removeslashes( $instance['text-after'] ));
        echo $args['before_widget'];
        if( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if( ! empty( $text_before ) ) {
            echo '<div>' . $text_before . '</div><div>&nbsp;</div>';
        }
        if( ! empty( $code_before ) ) {
            echo $code_before;
        }
		// TODO payload here
		$user_id = get_current_user_id();
		$units = $wpdb->get_results( $wpdb->prepare( "SELECT u.code, u.description,u.ratio, uu.multiplier  FROM $wpdb->svj_unit_to_user uu INNER JOIN $wpdb->svj_units u ON uu.unit_id=u.id WHERE uu.user_id=%d", $user_id ) );
		if ($units) {
			foreach($units as $unit) {
		        $result_line = apply_filters('poll_template_svj_ownership_markup', $line_template, array(
					'**CODE**' => intval($unit->code),
					'**DESCRIPTION**' => strip_tags( removeslashes($unit->description) ),
					'**CO_OWNERSHIP**' => 100.0 * doubleval($unit->multiplier),
					'**VOTE_RATIO**' => doubleval($unit->ratio)
				));

				
				echo $result_line;
				
			}
		}
        if( ! empty( $code_after ) ) {
            echo $code_after;
        }		
        if( ! empty( $text_after ) ) {
            echo '<div>&nbsp;</div><div>' . $text_after . '</div>';
        }

        echo $args['after_widget'];
	}

    // When Widget Control Form Is Posted
    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['text-before'] = $new_instance['text-before'];
        $instance['code-before'] = $new_instance['code-before'];
        $instance['line-template'] = $new_instance['line-template'];
        $instance['code-after'] = $new_instance['code-after'];
        $instance['text-after'] = $new_instance['text-after'];
        return $instance;
    }

    // Display Widget Control Form - this is what you see in the Widgets menu
    function form($instance) {
        global $wpdb;
		// default values
        $instance = wp_parse_args((array) $instance, array('title' => __('SVJ Ownership', 'wp-polls'), 'text-before' => '', 'code-before' => '<table><tr><th></th><th></th><th>hlasovací podíl</th><th>spolu vlastnictví</th></tr>', 'line-template' => '<tr><td><b>**CODE**</b></td><td>**DESCRIPTION**</td><td>**VOTE_RATIO**%</td><td>**CO_OWNERSHIP**%</td></tr>', 'code-after' => '</table>', 'text-after' => ''));
        $title = esc_attr($instance['title']);
		$text_before = esc_attr($instance['text-before']);
		$code_before = esc_attr($instance['code-before']);
		$line_template = esc_attr($instance['line-template']);
		$code_after = esc_attr($instance['code-after']);
		$text_after = esc_attr($instance['text-after']);
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-polls'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text-before'); ?>"><?php _e('Text before:', 'wp-polls'); ?> <input class="widefat" id="<?php echo $this->get_field_id('text-before'); ?>" name="<?php echo $this->get_field_name('text-before'); ?>" type="text" value="<?php echo $text_before; ?>" /></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('code-before'); ?>"><?php _e('Code before:', 'wp-polls'); ?> <input class="widefat" id="<?php echo $this->get_field_id('code-before'); ?>" name="<?php echo $this->get_field_name('code-before'); ?>" type="text" value="<?php echo $code_before; ?>" /></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('line-template'); ?>"><?php _e('Result line template:', 'wp-polls'); ?> <input class="widefat" id="<?php echo $this->get_field_id('line-template'); ?>" name="<?php echo $this->get_field_name('line-template'); ?>" type="text" value="<?php echo $line_template; ?>" /></label>
        </p>
		
        <p>
            <label for="<?php echo $this->get_field_id('code-after'); ?>"><?php _e('Code after:', 'wp-polls'); ?> <input class="widefat" id="<?php echo $this->get_field_id('code-after'); ?>" name="<?php echo $this->get_field_name('code-after'); ?>" type="text" value="<?php echo $code_after; ?>" /></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text-after'); ?>"><?php _e('Text after:', 'wp-polls'); ?> <input class="widefat" id="<?php echo $this->get_field_id('text-after'); ?>" name="<?php echo $this->get_field_name('text-after'); ?>" type="text" value="<?php echo $text_after; ?>" /></label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php
    }
}

?>