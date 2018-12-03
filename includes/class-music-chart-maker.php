<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       crysto.netronit.com
 * @since      1.0.0
 *
 * @package    Music_chart
 * @subpackage Music_chart/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Music_chart
 * @subpackage Music_chart/includes
 * @author     Kunle Adekoya <crystoline@gmail.com>
 */
class Music_chart_Maker {

	private $messages = array();
	private $max_step = 1;

	function __construct($type = 'artist')//song, album, artist
	{
		$steps = array('song' => 3, 'album'=>2, 'artist' => 1);
		if(!in_array($type, $steps))
			$this->max_step = 1;
		else $this->max_step = $steps[$type];
		$this->process();

	}
	private function process(){
		/*get artist */
		if(isset($_POST['artist_name'])){ //create new Artist
			if(Music_chart_DB::artist_exist($_POST['artist_name'])){
				$this->setMessage('Artist record exist. You may select artist from the list or create new using another name');
			}elseif($artist_id = Music_chart_DB::create_artist($_POST['artist_name'], $_POST['artist_url'])){
				$_SESSION['step'] = 2;
				$_SESSION['artist_id'] = $artist_id;
			}
		}
		if(isset($_POST['artist_id'])){
			$_SESSION['step'] = 2;
			$_SESSION['artist_id'] = $_POST['artist_id'];
		}
		/*get album */
		if(isset($_POST['album_name']) and isset($_POST['artist_id'])){ //create new Artist
			if(Music_chart_DB::artist_albums_exist($_POST['artist_id'],$_POST['artist_name'])){
				$this->setMessage('Album record exist. You may select Album from the list or create new using another name');
			}elseif($album_id = Music_chart_DB::create_artist_album($_POST['album_name'], $_POST['artist_id'], @$_POST['artist_url'], @$_POST['album_cover'])){
				$_SESSION['step'] = 3;
				$_SESSION['album_id'] = $album_id;
			}
		}
		if(isset($_POST['album_id'])){
			$_SESSION['step'] = 3;
			$_SESSION['album_id'] = $_POST['album_id'];
		}

		/*get Song */
		if(isset($_POST['song_name']) and isset($_POST['album_id'])){ //create new Artist
			if(Music_chart_DB::albums_song_exist($_POST['album_id'],$_POST['song_name'])){
				$this->setMessage('Song record exist. You may select Album from the list or create new using another name');
			}elseif($song_id = Music_chart_DB::create_album_song($_POST)){
				$_SESSION['step'] = 4;
				$_SESSION['song_id'] = $song_id;
				$_SESSION['song_status'] = $_POST['song_status']? : 'active';
			}
		}
		if(isset($_POST['song_id']) and isset($_POST['song_status'])){
			$_SESSION['step'] = 4;
			$_SESSION['song_id'] = $_POST['song_id'];
			$_SESSION['song_status'] = $_POST['song_status'];
		}
		@$_SESSION['step'] = ((int)$_SESSION['step'])? : 1;
	}

	private function setMessage($content, $type='warning', $dismisable = true){
		if($content)
			$this->messages[] = array(
				'content' 		=> $content,
				'type'			=> $type,
				'dismisable'	=> $dismisable
			);
	}
	public function display_msg( $echo = true){
		if(!$this->messages) return false;
		$str = '';
		foreach($this->messages as $message){
			@$type = $message['type']? :'warning';
			@$content = $message['content'];
			@$dismisable = $message['dismisable']? ' is-dismissible':'';

			$str .= '<div id="message" class="notice notice-'.$type.$dismisable.'">
			<p>'.$content.'</p>'.
			($message['dismisable']? '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>': '').
			'</div>';
		}
	}
}
