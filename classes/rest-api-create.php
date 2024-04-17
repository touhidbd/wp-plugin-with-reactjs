<?php

class React_Rest_API {
	public function __construct() {
		add_action('rest_api_init', [$this, 'create_rest_routes']);
	}
	public function create_rest_routes() {
		register_rest_route('wpwr/v2','/settings/', [
			'methods'   => 'GET',
			'callback'  => [$this, 'get_settings'],
			'permission_callback'   => [$this, 'get_setting_permission']
		]);
		register_rest_route('wpwr/v2','/last-n-days/(?P<days>\d+)/', [
			'methods'   => 'GET',
			'callback'  => [$this, 'get_last_n_days_data'],
			'permission_callback'   => [$this, 'get_setting_permission']
		]);
		register_rest_route('wpwr/v2','/date-range/(?P<date>[0-9 .\-]+)/', [
			'methods'   => 'GET',
			'callback'  => [$this, 'get_date_data'],
			'permission_callback'   => [$this, 'get_setting_permission']
		]);
	}

	public function get_date_data($request) {
		$date = $request['date'];
		return $this->get_data_for_date($date);
	}

	public function get_data_for_date($date) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'charttable';
		$query = $wpdb->prepare("SELECT * FROM $table_name WHERE dateT <= %s", $date);
		$result = $wpdb->get_results($query);
		return $result;
	}

	public function get_last_n_days_data($request) {
		$days = $request['days'];
		return $this->get_data_for_days($days);
	}

	public function get_data_for_days($days) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'charttable';
		$query = "SELECT * FROM $table_name WHERE dateT >= DATE_SUB(NOW(), INTERVAL $days DAY)";
		$result = $wpdb->get_results($query);

		return $result;
	}

	public function get_settings () {
		global $wpdb;
        $table_name = $wpdb->prefix . 'charttable';
        $all_qu = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table_name} "
            ),ARRAY_A
        );

        //$result = $wpdb->get_results("SELECT * FROM `wp_chartTable`");
        
        //return rest_ensure_response( 'success' );
        return $all_qu;
	}
	public function get_setting_permission(){
		return true;
	}
}

new React_Rest_API();