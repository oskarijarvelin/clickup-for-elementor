<?php

// You shall not pass (directly)
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

class ClickUp_For_Elementor_Action extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'Create ClickUp task';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'Create a new ClickUp task from submission', 'clickup_for_elementor' );
	}

	/**
	 * Get fields
	 *
	 * Returns one or more fields combined with a separator
	 *
	 * @access public
	 * @param string $field_id
	 * @param string $separator
	 * @param array $settings
	 * @param array $fields
	 * @return string
	 */
	public function get_fields( $field_id, $separator, $settings, $fields ) {
		if ( !empty( $settings[ $field_id ] ) ) {
			$value = "";
			$ids = explode(',', str_replace( ' ', '', $settings[ $field_id ]) );
			foreach ( $ids as $id ) {
				$value .= $fields[ $id ] . $separator;
			}
			
			return $value;
		} else {
			return '';
		}
	}

	/**
	 * Get tags
	 *
	 * Returns array of tags from selected fields
	 *
	 * @access public
	 * @param string $field_id
	 * @param array $settings
	 * @param array $fields
	 * @return array
	 */
	public function get_tags( $field_id, $settings, $fields ) {
		if ( !empty( $settings[ $field_id ] ) ) {
			$tags = array();
			$ids = explode(',', str_replace( ' ', '', $settings[ $field_id ]) );
			foreach ( $ids as $id ) {
				array_push( $tags, $fields[ $id ] );
			}
			
			return $tags;
		} else {
			return array();
		}
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_cfe',
			[
				'label' => __( 'Create ClickUp task', 'clickup_for_elementor' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		// Add option for personal ClickUp API token
		$widget->add_control(
			'cfe_api_token',
			[
				'label' => __( 'Personal ClickUp API token', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => ' pk_...',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter your personal API token from <a href="https://app.clickup.com/settings/apps" target="_blank">ClickUp</a>.', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Add option for list id
		$widget->add_control(
			'cfe_list_id',
			[
				'label' => __( 'ClickUp List ID', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '000000000',
				'description' => __( 'Enter the list id. You can find this at the end of the url when your in the list in clickup for example: https://app.clickup.com/123456/v/l/li/<b>000000000</b>.', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Add option to assign task to user
		$widget->add_control(
			'cfe_assignee_id',
			[
				'label' => __( 'ClickUp Assignee ID', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '0000000',
				'description' => __( 'Enter the assignee id. You can find this <a href="https://app.clickup.com/settings/team/users" target="_blank">here</a> when logged in Clickup. Click on the three dots on the user and click on copy member ID.', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Add option to notify all users
		$widget->add_control(
			'cfe_notify_all',
			[
				'label' => __( 'Notify all ClickUp users?', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		// Select fields for name
		$widget->add_control(
			'cfe_task_name_fields',
			[
				'label' => __( 'Task name field ID(s)', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'taskname',
				'separator' => 'before',
				'description' => __( 'Enter the elementor form task name field id or multiple ids (separated by commas).', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Select fields for description
		$widget->add_control(
			'cfe_task_description_fields',
			[
				'label' => __( 'Task description field ID(s) (Optional)', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'taskdescription',
				'description' => __( 'Enter the elementor form task description field id or multiple ids (separated by commas).', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Select fields for tags
		$widget->add_control(
			'cfe_task_tags_fields',
			[
				'label' => __( 'Task tags field ID(s) (Optional)', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'tasktags',
				'description' => __( 'Enter the elementor form task tags field id or multiple ids (separated by commas).', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Select due date
		$widget->add_control(
			'cfe_due_date',
			[
				'label' => __( 'Due date', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '14',
				'description' => __( 'Enter the amount of days for the task due date. 0 or empty will be set to today', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		// Select fields for customer custom field
		$widget->add_control(
			'cfe_task_customer_fields',
			[
				'label' => __( 'Task customer field ID(s)', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'customer',
				'description' => __( 'Enter the elementor form task customer field id or multiple ids (separate by commas).', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		// Select fields for device custom field
		$widget->add_control(
			'cfe_task_device_fields',
			[
				'label' => __( 'Task device field ID(s)', 'clickup_for_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'device',
				'description' => __( 'Enter the elementor form task device field id or multiple ids (separate by commas).', 'clickup_for_elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @access Public
	 * @param array $element
	 */
	public function on_export( $element ) {
		unset(
			$element['cfe_api_token'],
			$element['cfe_list_id'],
			$element['cfe_assignee_id'],
			$element['cfe_task_name_fields'],
			$element['cfe_task_description_fields'],
			$element['cfe_task_tags_fields'],
			$element['cfe_due_date'],
			$element['cfe_notify_all'],
			$element['cfe_task_customer_fields'],
			$element['cfe_task_device_fields']
		);

		return $element;
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );

		//  Make sure that there is an clickup API key set
		if ( empty( $settings['clickup_api'] ) ) {
			return;
		}

		//  Make sure that there is an workspace set
		if ( empty( $settings['cfe_list_id'] ) ) {
			return;
		}

		//  Make sure that there is an assignee set
		if ( empty( $settings['cfe_assignee_id'] ) ) {
			return;
		}

		//  Make sure that there is a task name set
		if ( empty( $settings['cfe_task_name_fields'] ) ) {
			return;
		}

		// Get submitted Form data
		$raw_fields = $record->get( 'fields' );

		// Normalize the Form Data
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		// Set notifications
		$notifications = $settings['cfe_notify_all'];
		if ($notifications == "yes") {
			$notification = true;
		}
		else {
			$notification = false;
		}

		// Calculate due date 
		if ( empty( $settings['cfe_due_date'] ) || $settings['cfe_due_date'] == "0" ){
			$date = date("Y-m-d");
			$duedate = 1000 * strtotime($date);
		} else {
			$days = $settings['cfe_due_date'];
			$date = date('Y-m-d', strtotime("+$days days"));
			$duedate = 1000 * strtotime($date);
		}

		// Create payload with form data
		$payload = [
			"name" => $this->get_fields('cfe_task_name_fields', ' ', $settings, $fields),
			"description" => $this->get_fields('cfe_task_description_fields', PHP_EOL . PHP_EOL, $settings, $fields),
			"tags" => $this->get_tags('cfe_task_tags_fields', $settings, $fields),
			"assignees" => [ $settings['cfe_assignee_id'] ], 
			"due_date" => $duedate, 
			"due_date_time" => false, 
			"notify_all" => $notification,
			"custom_fields" => array(
				array( // Customer
					"id" => "23fb2eaa-3a2d-4965-a496-767df00d5a7b",
					"value" => $this->get_fields('cfe_task_customer_fields', PHP_EOL, $settings, $fields)
				),
				array( // Device
					"id" => "7511d185-8348-44f7-bbf5-5f88bd52bcea",
					"value" => $this->get_fields('cfe_task_device_fields', ' ', $settings, $fields)
				)
			)
		];

		// Send payload
		$response = wp_remote_post( 
			'https://api.clickup.com/api/v2/list/'. $settings['cfe_list_id'] .'/task', 
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'httpversion' => '1.0',
				'blocking'    => false,
				'headers'     => [
					'Content-Type' 	=> 'application/json',
					'Authorization' => $settings['cfe_api_token'],
				],
				'body'        => json_encode( $payload )
			)
		);	
		
	}
}