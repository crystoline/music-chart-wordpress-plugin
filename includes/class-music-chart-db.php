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
class Music_chart_DB {
	private static function get_table_prefix(){
		global $wpdb; $wpdb->show_errors = true;
		return $wpdb->prefix . 'music_chart_';
	}
	public static function week($date = ''){
		/*print date('Y-m-d',strtotime("next monday")); print '<br>';
		print date('Y-m-d', strtotime("this sunday"));print '<br>';
		print date('Y-m-d',strtotime("last monday"));print '<br>';
		print date('Y-m-d',strtotime("monday this week"));print '<br>';*/

		$options = get_option(Music_chart::PLUGIN_NAME);
		$weekStart  = $options['weekStart']? : 'sunday';

		$time = ($date)? strtotime( $date) : strtotime('this sunday');
        return date( 'Y-m-d' , strtotime("next {$weekStart} -1 week", $time) );
	}
    public static function previousWeek($date=''){
        $options = get_option(Music_chart::PLUGIN_NAME);
        $weekStart  = $options['weekStart']? : 'sunday';
        $time = ($date)? strtotime( date("Y-m-d")) : strtotime('this sunday');
        return date( 'Y-m-d' , strtotime("next {$weekStart} -2 week", $time) );
    }

	/**
	 * Create New Music Chart Record
	 * @param $name string
	 * @return false|int
	 */
	public static function create_chart($name)
	{
		global $wpdb;
		$table_pre = self::get_table_prefix();
		return $wpdb->insert(
				$table_pre.'charts',
				array(
					'name'				=> $name,
					'created_at'		=> current_time( 'mysql' ),
				)
		);
	}

	public static function all_charts($date = ''){
		/*$week =  self::week($date);
		WHERE week = '{$week}'*/
		global $wpdb;
		$table_pre = self::get_table_prefix();
		$table_name = $table_pre . 'charts';
		return $wpdb->get_results("
			SELECT {$table_pre}charts.*,
					COUNT({$table_pre}songs.id) as songs
			FROM {$table_name}
			 LEFT JOIN {$table_pre}music_charts ON {$table_pre}music_charts.chart_id = {$table_pre}charts.id
			 LEFT JOIN {$table_pre}songs ON {$table_pre}music_charts.song_id = {$table_pre}songs.id
			 GROUP BY {$table_pre}charts.id
			 ORDER BY created_at DESC
		") ;
	}
    public static function chart_weeks($chart_id){
        $week = self::week();
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $table_name = $table_pre . 'music_charts';
        return $wpdb->get_results("
			SELECT DISTINCT({$table_name}.week) as chart_weeks
			FROM {$table_name}
            WHERE week < '$week'
            ORDER BY week DESC
		") ;
    }

	public static function get_chart($id)
    {
		$id = addslashes($id);
		global $wpdb;
		$table_pre = self::get_table_prefix();
		$table_name = $table_pre . 'charts';
		return $wpdb->get_row("
			SELECT {$table_pre}charts.*,
					COUNT({$table_pre}songs.id) as songs,
					(SELECT COUNT(DISTINCT(week)) FROM {$table_pre}music_charts WHERE id = {$table_pre}music_charts.id) AS count
			FROM {$table_name}
			 LEFT JOIN {$table_pre}music_charts ON {$table_pre}music_charts.chart_id = {$table_pre}charts.id
			 LEFT JOIN {$table_pre}songs ON {$table_pre}music_charts.song_id = {$table_pre}songs.id
			WHERE {$table_pre}charts.`id` = $id
			 GROUP BY {$table_pre}charts.id

		");
	}

    public static function update_chart($chart_id,$chart_name)
    {
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $table_name = $table_pre . 'charts';
        return $wpdb->update(
            $table_name,
            array('name' => $chart_name),
            array('id' => $chart_id),
            array( '%s')
        );
    }
	public static function delete_charts($music_chart_ids)
	{
        if(!$music_chart_ids or !is_array($music_chart_ids)) return false;
		global $wpdb;
		$table_pre = self::get_table_prefix();
		$table_name = $table_pre . 'charts';
        $music_chart_id_list = implode(', ',$music_chart_ids);
        return
            $wpdb->query("DELETE FROM {$table_name} WHERE id IN ($music_chart_id_list);");
	}

	public static function all_chart_songs($chart_id, $show = 'all',$limit = 0, $orderBy = '', $week=0)//show = active, inactive
	{
        $week = (int)$week;
        $prev_week = $week+1;

		global $wpdb;
		$table_pre = self::get_table_prefix();
        switch($show){
            case 'active'  :$clause = " AND {$table_pre}music_charts.status = 'active' "; break;
            case 'inactive' : $clause = " AND {$table_pre}music_charts.status = 'inactive' "; break;
            default: $clause = " ";
        }
        switch($orderBy){
            case 1:
                //By Rank
                $orderBy = "{$table_pre}music_charts.status ASC ,{$table_pre}music_charts.ranking ASC, {$table_pre}music_charts.id ASC";
                break;
            default:
                //By Votes
                $orderBy = "{$table_pre}music_charts.status ASC ,votes DESC, {$table_pre}music_charts.ranking ASC/*, {$table_pre}music_charts.id ASC*/";
        }
        $limit = (int)$limit;
        $lim = ($limit)? " LIMIT 0,  $limit": "";
		$user_id = get_current_user_id();
		return $wpdb->get_results("
			SELECT
			 {$table_pre}songs.*,
              {$table_pre}music_charts.id AS music_chart_id,
              {$table_pre}albums.name AS album_name,
              {$table_pre}albums.artist_id,
              {$table_pre}albums.id AS album_id,
              {$table_pre}albums.cover AS album_cover,
              {$table_pre}albums.album_url AS album_url,
              {$table_pre}artists.name AS artist_name,
              {$table_pre}music_charts.ranking,
              {$table_pre}music_charts.status,
              DATE ({$table_pre}music_charts.week) AS week,
			  TRUNCATE(SUM({$table_pre}ratings.rate) / COUNT({$table_pre}ratings.rate),1) AS rating,
			  (SELECT COUNT(*) FROM {$table_pre}votes WHERE {$table_pre}votes.music_chart_id  = {$table_pre}music_charts.id ) AS votes,
			  (
                  SELECT {$table_pre}music_charts.ranking
                  FROM {$table_pre}music_charts
                  WHERE {$table_pre}music_charts.id = (
                     SELECT {$table_pre}music_charts.id
                     FROM {$table_pre}music_charts
                     WHERE  {$table_pre}music_charts.week = (
                        SELECT DISTINCT ({$table_pre}music_charts.week)
                        FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$prev_week}, 1
                     )
			       AND {$table_pre}music_charts.chart_id = {$chart_id} AND {$table_pre}music_charts.song_id =  {$table_pre}songs.id /*Join*/
                  )
              )AS previous_ranking,
              (
                  SELECT COUNT({$table_pre}music_charts.id)
                  FROM {$table_pre}music_charts
                  WHERE {$table_pre}music_charts.id IN (
                     SELECT {$table_pre}music_charts.id
                     FROM {$table_pre}music_charts
                     WHERE
                        {$table_pre}music_charts.chart_id = {$chart_id} AND {$table_pre}music_charts.song_id =  {$table_pre}songs.id /*Join*/
                        AND {$table_pre}music_charts.week <= (
                            SELECT DISTINCT ({$table_pre}music_charts.week)
                            FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$week}, 1
                        )
                  )
              )AS featured_count,
              (
                  SELECT MIN({$table_pre}music_charts.ranking)
                  FROM {$table_pre}music_charts
                  WHERE {$table_pre}music_charts.id IN (
                     SELECT {$table_pre}music_charts.id
                     FROM {$table_pre}music_charts
                     WHERE
                        {$table_pre}music_charts.chart_id = {$chart_id} AND {$table_pre}music_charts.song_id =  {$table_pre}songs.id /*Join*/
                        AND {$table_pre}music_charts.week < (
                            SELECT DISTINCT ({$table_pre}music_charts.week)
                            FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$week}, 1
                        )
                  )
              ) AS peak_ranking,
              (
                  SELECT COUNT({$table_pre}music_charts.ranking)
                  FROM {$table_pre}music_charts
                  WHERE {$table_pre}music_charts.id IN (
                     SELECT {$table_pre}music_charts.id
                     FROM {$table_pre}music_charts
                     WHERE
                        {$table_pre}music_charts.chart_id = {$chart_id} AND {$table_pre}music_charts.song_id =  {$table_pre}songs.id /*Join*/
                        AND {$table_pre}music_charts.week <= (
                            SELECT DISTINCT ({$table_pre}music_charts.week)
                            FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$prev_week}, 1
                        )
                  ) AND {$table_pre}music_charts.ranking = 1
              ) AS toping_count,
			  (
			    SELECT COUNT(*)
			    FROM {$table_pre}votes
			    WHERE {$table_pre}votes.music_chart_id  = (
			      SELECT {$table_pre}music_charts.id
			      FROM {$table_pre}music_charts
			      WHERE {$table_pre}music_charts.week = (
			        SELECT DISTINCT ({$table_pre}music_charts.week)
			        FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$prev_week}, 1
			      )
			      AND {$table_pre}music_charts.chart_id = {$chart_id} AND {$table_pre}music_charts.song_id =  {$table_pre}songs.id
			    )
			  ) AS previous_votes,
			  (
			    SELECT COUNT(*)
			    FROM {$table_pre}votes
			    WHERE {$table_pre}votes.music_chart_id  IN (
			      SELECT {$table_pre}music_charts.id
			      FROM {$table_pre}music_charts
			      WHERE {$table_pre}music_charts.week = (
			        SELECT DISTINCT ({$table_pre}music_charts.week)
			        FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$prev_week}, 1
			      )
			       AND {$table_pre}music_charts.chart_id = {$chart_id}
			    )
			  ) AS previous_total_votes,

			  (SELECT rate from {$table_pre}ratings WHERE {$table_pre}ratings.song_id = {$table_pre}songs.id and user_id = {$user_id}) AS user_rating,
			  (SELECT SUM(id) from {$table_pre}votes WHERE {$table_pre}votes.music_chart_id ={$table_pre}music_charts.id and user_id = {$user_id}) AS voted
			 FROM  {$table_pre}songs
			 LEFT JOIN {$table_pre}albums ON {$table_pre}albums.id = {$table_pre}songs.album_id
			 LEFT JOIN {$table_pre}artists ON {$table_pre}artists.id = {$table_pre}albums.artist_id
			 LEFT JOIN {$table_pre}music_charts ON {$table_pre}music_charts.song_id = {$table_pre}songs.id
			 LEFT JOIN {$table_pre}ratings ON {$table_pre}ratings.song_id = {$table_pre}songs.id
			 LEFT JOIN {$table_pre}votes ON {$table_pre}votes.music_chart_id = {$table_pre}music_charts.id
			 WHERE {$table_pre}music_charts.chart_id = $chart_id AND
			 {$table_pre}music_charts.week = (SELECT DISTINCT ({$table_pre}music_charts.week) FROM {$table_pre}music_charts ORDER BY week DESC LIMIT {$week}, 1)
			 {$clause}
			 GROUP BY {$table_pre}songs.id
			 ORDER BY {$orderBy}
		     $lim
		") ;
	}


	/*Artists*/
	public static function create_artist($name,$url='')
	{
		global $wpdb;
		$table_pre = self::get_table_prefix();
		return $wpdb->insert(
				$table_pre.'artists',
				array(
						'name'				=> $name,
						'artist_url'		=> $url,
						'created_at'		=> current_time( 'mysql' ),
				)
		)? $wpdb->insert_id: 0;
	}
	public static function get_artist($id){
		$id = addslashes($id);
		global $wpdb;
		$table_pre = self::get_table_prefix();
		$table_name = $table_pre . 'artists';
		return $wpdb->get_row("SELECT * FROM {$table_name} WHERE `id` = $id");
	}
	public static function artist_exist($name){
		$name = addslashes($name);
		global $wpdb;
		$table_pre = self::get_table_prefix();
		$table_name = $table_pre . 'artists';
		return $wpdb->get_row("SELECT * FROM {$table_name} WHERE `name` = '$name'");
	}

    public static function delete_artists($artist_ids){
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $table_name = $table_pre . 'artists';
        $artists_list = implode(', ',$artist_ids);
        return
            $wpdb->query("
                DELETE FROM {$table_name} WHERE id IN ($artists_list);
            ");

    }

	public  static function update_artist($artist_id, $artist_name,$artist_url){
		$artist_id = addslashes($artist_id);
		$artist_name = addslashes($artist_name);
		$artist_url = addslashes($artist_url);
		global $wpdb;
        $table_pre = self::get_table_prefix();
		return
			$wpdb->update($table_pre . 'artists',
				array(
					'name' => $artist_name,
					'artist_url'  => $artist_url
				),
				array(
					'id' =>$artist_id
				)
			);
	}

	public static function all_artists(){
		global $wpdb;
		$table_pre = self::get_table_prefix();
		$table_name = $table_pre . 'artists';
		return $wpdb->get_results("
			SELECT
				{$table_pre}artists.*,
				COUNT({$table_pre}songs.id) as songs,
				COUNT({$table_pre}albums.id) as albums
			FROM {$table_name}
			LEFT JOIN {$table_pre}albums ON {$table_pre}albums.artist_id = {$table_pre}artists.id
			LEFT JOIN {$table_pre}songs ON {$table_pre}songs.album_id = {$table_pre}albums.id
			GROUP BY {$table_pre}artists.id
			ORDER BY name ASC
		") ;
	}

	public static function artist_albums($artist_id){
		$artist_id = addslashes((int)$artist_id);
		global $wpdb;
		$table_name= self::get_table_prefix().'albums';
		return $wpdb->get_results("
			SELECT *
			FROM {$table_name}
			 WHERE artist_id = $artist_id
			  ORDER BY name ASC
		") ;
	}

	public static function artist_albums_exist($artist_id, $album_name){
		$artist_id = addslashes((int)$artist_id);
		$album_name = addslashes($album_name);
		global $wpdb;
		$table_name= self::get_table_prefix().'albums';
		return $wpdb->get_results("
			SELECT *
			FROM {$table_name}
			 WHERE artist_id = $artist_id AND `name` = '$album_name'
			ORDER BY name ASC
		") ;
	}

	public static function create_artist_album($name,$artist_id, $album_url,$cover)
	{
		global $wpdb;
		$table_pre = self::get_table_prefix();
		return $wpdb->insert(
				$table_pre.'albums',
				array(
						'name'				=> $name,
						'artist_id'			=> $artist_id,
						'album_url'			=> $album_url,
						'cover'				=> $cover,
						'created_at'		=> current_time( 'mysql' ),
				)
		)? $wpdb->insert_id: 0;
	}

	public static function get_album($album_id){
		$album_id = addslashes((int)$album_id);
		global $wpdb;
		$table_pre= self::get_table_prefix();
		return $wpdb->get_row("
			SELECT
			{$table_pre}albums.*,
				{$table_pre}artists.name as artist_name,
				{$table_pre}artists.id as artist_id,
				COUNT({$table_pre}songs.id) as songs
			FROM {$table_pre}albums
			LEFT JOIN {$table_pre}artists ON {$table_pre}artists.id = {$table_pre}albums.artist_id
			LEFT JOIN {$table_pre}songs ON {$table_pre}songs.album_id = {$table_pre}albums.id

			WHERE {$table_pre}albums.id = $album_id
			GROUP BY {$table_pre}albums.id
			ORDER BY name ASC") ;
	}

	public static function get_albums($artist_id){
		global $wpdb;
		$table_pre= self::get_table_prefix();
        if(!empty($artist_id)){
            $artist_id = (int) $artist_id;
            $where = "WHERE {$table_pre}albums.artist_id = $artist_id";
        }else{
            $where = "";
        }
		return $wpdb->get_results("
			SELECT
				{$table_pre}albums.*,
				{$table_pre}artists.name as artist_name,
				{$table_pre}artists.id as artist_id,
				COUNT({$table_pre}songs.id) as songs
			FROM {$table_pre}albums
			LEFT JOIN {$table_pre}artists ON {$table_pre}artists.id = {$table_pre}albums.artist_id
			LEFT JOIN {$table_pre}songs ON {$table_pre}songs.album_id = {$table_pre}albums.id
			{$where}
			GROUP BY {$table_pre}albums.id
			ORDER BY name ASC
		") ;
	}

    public  static function update_album($album_id, $album_name,$album_url, $album_cover){
        $artist_id = addslashes($artist_id);
        $album_name = addslashes($album_name);
        $album_url = addslashes($album_url);
        $album_cover = addslashes($album_cover);

        global $wpdb;
        $table_pre = self::get_table_prefix();
        return
            $wpdb->update($table_pre . 'albums',
                array(
                    'name'          => $album_name,
                    'album_url'     => $album_url,
                    'cover'         => $album_cover
                ),
                array(
                    'id' =>$album_id
                )
            );
    }
    public static function delete_albums($album_ids){
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $table_name = $table_pre . 'albums';
        $albums_list = implode(', ',$album_ids);
        return
            $wpdb->query("
                DELETE FROM {$table_name} WHERE id IN ($albums_list);
            ");

    }
	public static function get_album_songs($album_id){
		$album_id = addslashes((int)$album_id);
		global $wpdb;
		$table_pre= self::get_table_prefix();
		return $wpdb->get_results("
			SELECT {$table_pre}songs.*, {$table_pre}albums.name as albums_name
			FROM {$table_pre}songs
			LEFT JOIN {$table_pre}albums ON {$table_pre}albums.id = {$table_pre}songs.album_id
			WHERE {$table_pre}albums.id = $album_id
			ORDER BY name ASC
		") ;
	}

	public static function get_songs($artist_id = 0,$album_id=0){
		$album_id = addslashes((int)$album_id);
		$artist_id = addslashes((int)$artist_id);

		global $wpdb;
		$table_pre= self::get_table_prefix();

		$clause = array();
		if($artist_id) $clause_r[] = "{$table_pre}artists.id = $artist_id" ;
		if($album_id) $clause_r[] = "{$table_pre}albums.id = $album_id";

		$clause = $clause_r? implode(' AND ', $clause_r): '';
		if($clause) $clause = " WHERE ".$clause;

		return $wpdb->get_results("
			SELECT
			{$table_pre}songs.*,
			{$table_pre}albums.id as album_id,
			{$table_pre}albums.name as album_name,
			{$table_pre}artists.name as artist_name,
			{$table_pre}artists.id as artist_id
			FROM {$table_pre}songs
			LEFT JOIN {$table_pre}albums ON {$table_pre}albums.id = {$table_pre}songs.album_id
			LEFT JOIN {$table_pre}artists ON {$table_pre}artists.id = {$table_pre}albums.artist_id
			$clause
			ORDER BY name ASC
		") ;
	}
	public static function albums_song_exist($album_id, $song_name){
		$album_id = addslashes((int)$album_id);
		$song_name = addslashes($song_name);
		global $wpdb;
		$table_name= self::get_table_prefix().'songs';
		return $wpdb->get_results("
			SELECT *
			FROM {$table_name}
			 WHERE album_id = $album_id AND `name` = '$song_name'
			ORDER BY name ASC
		") ;
	}

	public static function create_album_song($song_name,$album_id,$song_url,$song_genre,$song_youtube_url='', $song_amazon_url='', $song_itunes_url='')
	{
		global $wpdb;
		$table_pre = self::get_table_prefix();
		return $wpdb->insert(
			$table_pre.'songs',
			array(
				'name'				=> $song_name,
				'album_id'			=> $album_id,
				'url'				=> $song_url,
				'genre'				=> $song_genre,
				'youtube_url'		=> $song_youtube_url,
				'amazon_url'		=> $song_amazon_url,
				'itunes_url'		=> $song_itunes_url,
				'created_at'		=> current_time( 'mysql' ),
			)
		);
	}
    public static function delete_songs($song_ids){
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $table_name = $table_pre . 'songs';
        $songs_list = implode(', ',$song_ids);
        return
            $wpdb->query("
                DELETE FROM {$table_name} WHERE id IN ($songs_list);
            ");

    }
    public static function edit_song($song_id,$song_name,$song_url,$song_genre,$song_youtube_url, $song_amazon_url='', $song_itunes_url='')
    {
        global $wpdb;
        $table_pre = self::get_table_prefix();
        return
            $wpdb->update($table_pre . 'songs',
                array(
                    'name'				=> $song_name,
                    'url'				=> $song_url,
                    'genre'				=> $song_genre,
                    'youtube_url'		=> $song_youtube_url,
                    'amazon_url'		=> $song_amazon_url,
                    'itunes_url'		=> $song_itunes_url,
                ),
                array(
                    'id' =>$song_id
                )
            );
    }
    public static function migrate_chart_song($chart_id){
        global $wpdb;
        $table_pre = self::get_table_prefix();

        $songs = self::all_chart_songs($chart_id, 'active',0,''); //last week based on ranking
        if(!$songs) return true;
        $sql = "
            INSERT INTO {$table_pre}music_charts
            (chart_id, song_id, week, status, ranking, created_at)
            VALUES
            ";
        $records = array();
        $week = self::week();
        $date = current_time( 'mysql' );
        foreach($songs as $i =>$song){
            $song_id = $song->id;
            $music_chart_id = $song->music_chart_id;
            $rank =  $i+1;
            self::music_chart_song_set_ranking($music_chart_id, $rank);
            $records[] = "({$chart_id}, {$song_id}, '{$week}', 'active', {$rank}, '{$date}')";
        }

        $sql =  $sql.implode(', ',$records);
        return $wpdb->query($sql);

    }

    public static function chart_week_ended($chart_id){
        $chart_id = (int) $chart_id;
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $thisWeek = self::week();
        return $wpdb->get_row("SELECT * FROM {$table_pre}music_charts WHERE week = '{$thisWeek}' AND chart_id = $chart_id")? false: true;
    }
	public static function create_music_chart_song($chart_id, $song_id, $status='active')
	{
        //migrate songs to new week
        if(self::chart_week_ended($chart_id) and !self::migrate_chart_song($chart_id)){
            return false;
        }

        global $wpdb;
		$table_pre = self::get_table_prefix();
		return $wpdb->insert(
				$table_pre.'music_charts',
				array(
						'chart_id'			=> $chart_id,
						'song_id'			=> $song_id,
                        'week'              => self::week(),
                        'ranking'           => 999,
						'status'			=> (string) $status,
						'created_at'		=> current_time( 'mysql' ),

				)/*,
                array('%d', '%d', '%s')*/
		)? $wpdb->insert_id: 0;
	}
    public static function chart_song_exist($chart_id, $song_id, $week=''){
        $chart_id = addslashes((int)$chart_id);
        $song_id = addslashes((int)$song_id);
        global $wpdb;
        $table_name= self::get_table_prefix().'music_charts';
        return $wpdb->get_row("
			SELECT *
			FROM {$table_name}
			 WHERE chart_id = $chart_id AND `song_id` = '$song_id' AND week = '".self::week($week)."'
		") ;
    }
    public static function delete_music_chart_song($chart_song_ids, $week=''){
        global $wpdb;
        $table_name= self::get_table_prefix().'music_charts';
        $chart_song_id_list = implode(', ',$chart_song_ids);
        return
            $wpdb->query("
                DELETE FROM {$table_name} WHERE id IN ($chart_song_id_list) AND AND week = '".self::week($week)."';
            ");
    }
    public static function music_chart_songs_change_status($ids, $status, $week=''){
        global $wpdb;
        $table_pre = self::get_table_prefix();
        $id_list = implode(', ', $ids);
        return $wpdb->query(
            "UPDATE
            {$table_pre}music_charts
            SET status =  '$status' WHERE id IN ({$id_list}) AND AND week = '".self::week($week)."'
        ");
    }

	public static function music_chart_song_set_ranking($id, $ranking, $week=''){
		global $wpdb;
		$table_pre = self::get_table_prefix();

		return $wpdb->update($table_pre.'music_charts',array('ranking' =>$ranking), array('id' =>$id, 'week'=> self::week($week)));
	}
	public static function rate_song($song_id, $user_id, $rating){
		global $wpdb;
		$table_pre = self::get_table_prefix();

		$sql = "INSERT INTO {$table_pre}ratings (song_id, user_id, rate, created_at) VALUES (%d,%s,%s,%s) ON DUPLICATE KEY UPDATE rate = %s";
		$sql = $wpdb->prepare($sql,$song_id, $user_id, $rating,current_time( 'mysql' ),$rating);
		return $wpdb->query($sql);
	}
	public static function vote_music_chart_song($music_chart_song_id, $user_id = 0){
		global $wpdb;
		$table_pre = self::get_table_prefix();
		if(!$user_id){
			$user_id = time().rand(0,9);
		}

		$sql = "INSERT INTO {$table_pre}votes (music_chart_id, user_id, created_at) VALUES (%d,%d,%s)";
		$sql = $wpdb->prepare($sql,$music_chart_song_id, $user_id, current_time( 'mysql' ));
		return $wpdb->query($sql);
	}

}
