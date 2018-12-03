<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       crysto.netronit.com
 * @since      1.0.0
 *
 * @package    Music_chart
 * @subpackage Music_chart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Music_chart
 * @subpackage Music_chart/public
 * @author     Kunle Adekoya <crystoline@gmail.com>
 */
class Music_chart_Public_API extends WP_REST_Server
{

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes()
	{
		$version = '1';
		$namespace = 'music-charts/v' . $version;
		register_rest_route($namespace, '/' . 'test_sms_api', array(
		
			array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array($this, 'test_sms_api'),
					'permission_callback' => array($this, 'allowAll'),
			),

		));

		register_rest_route($namespace, '/' . 'song/rate', array(
				array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array($this, 'rate_song'),
						'permission_callback' => array($this, 'get_rating_permissions_check'),
						'args' => array(
								'song_id' => array(
									'default' => 0,
								),
								'rating' => array(
									'default' => 0
								)
						),
				),

		));

		register_rest_route($namespace, '/' . 'artist/get_albums', array(
				array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array($this, 'artist_albums'),
						'permission_callback' => array($this, 'get_items_permissions_check'),
						'args' => array(
								'artist_id' => array(
										'default' => 0,
								),
						),
				),

		));

        register_rest_route($namespace, '/' . 'album/get_songs', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'album_songs'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'album_id' => array(
                        'default' => 0,
                    ),
                ),
            ),
        ));
        register_rest_route($namespace, '/' . 'music_chart/vote_song', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'vote_song'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'song' => array(
                        'default' => 0,

                    ),
                    'user' => array(
                        'default' => 0,
                    )
                ),
            ),
        ));
		register_rest_route($namespace, '/' . 'music_chart/vote_song_by_sms', array(
				array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array($this, 'vote_song_by_sms'),
						'permission_callback' => array($this, 'allowAll'),
						/*'args' => array(
								'song' => array(
										'default' => 0,

								),
								'user' => array(
										'default' => 0,
								)
						),*/
				),
		));

		register_rest_route($namespace, '/' . 'music_chart/vote_direct', array(
				array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array($this, 'vote_direct'),
						'permission_callback' => array($this, 'allowAll'),
				),
		));
		register_rest_route($namespace, '/' . 'music_chart/vote_song_by_point', array(
				array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array($this, 'vote_song_by_point'),
						'permission_callback' => array($this, 'get_items_permissions_check'),
						'args' => array(
								'song' => array(
										'default' => 0,

								),

						),
				),
		));

        register_rest_route($namespace, '/' . 'music_chart/set_rankings', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'music_chart_set_rankings'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'rankings' => array(
                        'default' => 0,
                    ),
                ),
            ),

        ));

	}

	public function artist_albums($request)
	{
		$params = $request->get_params();
		$artist_id = $params['artist_id'];
		$albums = Music_chart_DB::artist_albums($artist_id);
		return new WP_REST_Response($albums, 200);
	}
    public function album_songs($request)
    {
        $params = $request->get_params();
        $album_id = $params['album_id'];
        $songs = Music_chart_DB::get_album_songs($album_id);
        return new WP_REST_Response($songs, 200);
    }

    public function music_chart_set_rankings($request){
        $params = $request->get_params();
        $rankings = $params['rankings'];
        $i = 0;
       // var_dump($rankings); die();
        if(is_array($rankings)){
            foreach($rankings as $id =>$ranking){
                if(Music_chart_DB::music_chart_song_set_ranking($id, $ranking)){
                    $i++;
                }
            }
        }
        return new WP_REST_Response($i, 200);
    }

	public function rate_song($request)
    {
		$params = $request->get_params();
		$rating = $params['rating'];
		$song_id = $params['song_id'];
		$user_id = get_current_user_id();
		//return $song_id;
		return Music_chart_DB::rate_song($song_id,$user_id,$rating);
	}
	public function vote_song_by_point($request){
		$params = $request->get_params();
		$music_chart_song_id = $params['song'];
		$user_id = get_current_user_id();
		if(!$user_id){
			return new WP_REST_Response('Unauthorized: You may need to sign in or register', 403);
		}

        if(($balance = mycred_get_users_balance($user_id)) > 0 and Music_chart_DB::vote_music_chart_song($music_chart_song_id, $user_id)){
            mycred_subtract('Voting', $user_id, 30,"",$music_chart_song_id );
            return new WP_REST_Response('Voting was successful', 202);
        }
        return new WP_REST_Response('Balance is low. '.$balance .'pts', 404);
	}

	public function test_sms_api(WP_REST_Request $request){
		$url = "https://fortumo.com/test_sms_api?";
        $data['mcc'] = '234';
        $data['mnc'] = '30';
        $data['msisdn'] = '07031171668';
        $data['shortcode'] = '55508';
        $data['service_id'] = '4fd9e57c993137029b46a42fd1c6edd7';
        $data['status'] = 'ok';
        $data['userdata'] = '1:3M';
        $data['url'] = 'http://tombiabras.com/my-wp/wp-json/music-charts/v1/music_chart/vote_song_by_point?song=117';
        $secret = 'f9f1eafa4517885613ea376309acac96';

        $str = '';
		foreach ($data as $k=>$v) {
			$str .= "$k=$v";
		}
		$str .= $secret;
		$data['sig'] = md5($str);

        $qry = array();
        foreach ($data as $key => $value) {
        	$qry[] = urldecode($key).'='.urldecode($value);
        }

        $url .= implode('&', $qry) ;
        return new WP_REST_Response(array('url' => $url));
	}

	private function check_signature($params_array, $secret) {
			ksort($params_array);

			$str = '';
			foreach ($params_array as $k=>$v) {
				if($k != 'sig') {
					$str .= "$k=$v";
				}
			}
			$str .= $secret;
			$signature = md5($str);

			return ($params_array['sig'] == $signature);
		}
	public function vote_song_by_sms(WP_REST_Request $request){
		$GET = $request->get_params();

		//set true if you want to use script for billing reports
		//first you need to enable them in your account
		$billing_reports_enabled = false;

		// check that the request comes from Fortumo server
		if(!in_array($_SERVER['REMOTE_ADDR'],
				array('54.72.6.23'))) {
			header("HTTP/1.0 403 Forbidden");
			die("Error: Unknown IP");
		}

		// check the signature
		$secret = 'b83110cb36b131515cd3f3367ece5be1'; // insert your secret between ''
		if(empty($secret) || !$this->check_signature($GET, $secret)) {
			header("HTTP/1.0 404 Not Found");
			die("Error: Invalid signature");
		}
		

		if(empty($GET['message'])){
			header("HTTP/1.0 404 Not Found");
			die("Message Body missing");
		}

		$sender = $GET['sender'];
		$message = $GET['message'];
		$ids = explode(':',strtolower($message));
		if(count($ids) > 1){
			$user_id				=  base_convert($ids[0], 32, 10);
			$music_chart_song_id	=  base_convert($ids[1], 32, 10);
			$user = new WP_User($user_id);
			//$user_nicename = get_user_meta( 1, 'user_nicename', true );
			$sender .= " ({$user->user_nicename})";
			
		}else{
			$user_id				=  0;
			$music_chart_song_id	=  base_convert($ids[0], 32, 10);
		}
		$message_id = $GET['message_id'];//unique id

		//hint:use message_id to log your messages
		//additional parameters: country, price, currency, operator, keyword, shortcode
		// do something with $sender and $message
		$reply = "Thank you $sender for your vote";

        // Render Service, if payment has been successful.
        if(preg_match("/OK/i", $GET['status'])
            || (preg_match("/MO/i", $GET['billing_type']) && preg_match("/pending/i", $GET['status']))) {
            //add_credits($message);
            Music_chart_DB::vote_music_chart_song($music_chart_song_id, $user_id);
        }

		// print out the reply
		echo($reply); die();



		
	}

	public function vote_direct(WP_REST_Request $request){
		$GET = $request->get_params();

		//set true if you want to use script for billing reports
		//first you need to enable them in your account
		$billing_reports_enabled = false;

		// check that the request comes from Fortumo server
		if(!in_array($_SERVER['REMOTE_ADDR'],
				array('54.72.6.23'))) {
			header("HTTP/1.0 403 Forbidden");
			die("Error: Unknown IP");
		}

		// check the signature
		$secret = '74ac5ebc8df8af92e9424a3433c65aef'; // insert your secret between ''
		if(empty($secret) || !$this->check_signature($GET, $secret)) {
			header("HTTP/1.0 404 Not Found");
			die("Error: Invalid signature");
		}


		if(empty($GET['message'])){
			header("HTTP/1.0 404 Not Found");
			die("Message Body missing");
		}

		$sender = $GET['sender'];//phone num.
		$amount = $_GET['amount'];//credit
		$cuid = $GET['cuid'];//resource i.e. user
		$payment_id = $GET['payment_id'];//unique id
		$test = $GET['test']; // this parameter is present only when the payment is a test payment, it's value is either 'ok' or 'fail'



		if(preg_match("/completed/i", $_GET['status'])) {
			// mark payment as successful
			$ids = explode(':',strtolower($cuid));
			if(count($ids) > 1){
				$user_id				=  base_convert($ids[0], 32, 10);
				$music_chart_song_id	=  base_convert($ids[1], 32, 10);
				$user = new WP_User($user_id);
				//$user_nicename = get_user_meta( 1, 'user_nicename', true );
				$sender .= " ({$user->user_nicename})";

			}else{
				$user_id				=  0;
				$music_chart_song_id	=  base_convert($ids[0], 32, 10);
			}
			Music_chart_DB::vote_music_chart_song($music_chart_song_id, $user_id);
		}

		// print out the reply
		if($test){
			echo("TEST OK: Thank you {$sender} for your vote" );
		}
		else {
			$reply = "Thank you {$sender} for your vote";
			echo($reply); die();
		}

	}
    public function vote_song($request)
    {
        $params = $request->get_params();
        $music_chart_song_id = $params['song'];
        $user_id = get_current_user_id();
        return Music_chart_DB::vote_music_chart_song($music_chart_song_id, $user_id);
    }

	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items($request)
	{
		$items = array(); //do a query, call another class, etc
		$data = array();
		foreach ($items as $item) {
			$itemdata = $this->prepare_item_for_response($item, $request);
			$data[] = $this->prepare_response_for_collection($itemdata);
		}

		if (1 == 1) {
			return new WP_REST_Response($data, 200);
		} else {
			return new WP_Error('code', __('message', 'text-domain'));
		}
		// return new WP_Error( 'cant-update', __( 'message', 'text-domain'), array( 'status' => 500 ) );
	}
	public function allowAll(){
		return true;
	}
	/**
	 * Check if a given request has access to get items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check($request)
	{
		//return true; <--use to make readable by all
		return current_user_can('read');
	}

	public function get_rating_permissions_check($request)
	{
		//return true;
		return (get_current_user_id())? true: false; //current_user_can('read');
	}

	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check($request)
	{
		return $this->get_items_permissions_check($request);
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check($request)
	{
        //return true;
		return current_user_can('read');
	}

	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check($request)
	{
		return $this->create_item_permissions_check($request);
	}

	/**
	 * Check if a given request has access to delete a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function delete_item_permissions_check($request)
	{
		return $this->create_item_permissions_check($request);
	}

	/**
	 * Prepare the item for create or update operation
	 *
	 * @param WP_REST_Request $request Request object
	 * @return WP_Error|object $prepared_item
	 */
	protected function prepare_item_for_database($request)
	{
		return array();
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed $item WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 * @return mixed
	 */
	public function prepare_item_for_response($item, $request)
	{
		return array();
	}

	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params()
	{
		return array(
				'page' => array(
						'description' => 'Current page of the collection.',
						'type' => 'integer',
						'default' => 1,
						'sanitize_callback' => 'absint',
				),
				'per_page' => array(
						'description' => 'Maximum number of items to be returned in result set.',
						'type' => 'integer',
						'default' => 10,
						'sanitize_callback' => 'absint',
				),
				'search' => array(
						'description' => 'Limit results to those matching a string.',
						'type' => 'string',
						'sanitize_callback' => 'sanitize_text_field',
				),
		);
	}
}
