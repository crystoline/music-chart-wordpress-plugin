<?php

/**
 * Fired during plugin activation
 *
 * @link       crysto.netronit.com
 * @since      1.0.0
 *
 * @package    Music_chart
 * @subpackage Music_chart/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Music_chart
 * @subpackage Music_chart/includes
 * @author     Kunle Adekoya <crystoline@gmail.com>
 */
class Music_chart_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::installDb();
	}

	private static function installDb(){

		global $wpdb;
		global $music_chart;
		$music_chart_db_version = '1.0';

		$table_pre = 'music_chart_';

		$charset_collate = $wpdb->get_charset_collate();

		$previous_db_version = get_option('music_chart_db_version');

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		switch($previous_db_version){
			case '1.0.1':break;
			default:
                dbDelta( self::createArtistsSQL($table_pre));
                dbDelta( self::createAlbumsSQL($table_pre));
                dbDelta( self::createSongsSQL($table_pre));
                dbDelta( self::createAChartsSQL($table_pre));
                dbDelta( self::createMusicChartsSQL($table_pre));
                dbDelta( self::createVotesSQL($table_pre));
                dbDelta( self::createRatingsSQL($table_pre));
		}

        $previous_db_version?
            update_option('music_chart_db_version', $music_chart_db_version ):
		    add_option( 'music_chart_db_version', $music_chart_db_version );
	}

    private static function createSongsSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}songs(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `name` varchar(100),
                  `album_id` INT NOT NULL,
                  `genre` varchar(255),
                  `youtube_url` varchar(255),
                  `amazon_url` varchar(255),
                  `itunes_url` varchar(255),
                  `url` varchar(255),
                  `created_at` DATETIME,
                  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (id),
                  UNIQUE INDEX song_name_album (`name`, `album_id`),
                  CONSTRAINT fk_album_id FOREIGN KEY (album_id) REFERENCES {$table_pre}albums(id) ON DELETE CASCADE
              ) $charset_collate;";
    }

    private static function createAlbumsSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}albums(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `name` varchar(100),
                  `artist_id` INT NOT NULL,
                  `album_url` varchar(255),
                  `cover` varchar(255),
                  `created_at` DATETIME,
                  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (id),
                  UNIQUE INDEX album_name_artist (`name`, `artist_id`),
                  CONSTRAINT fk_artist_id FOREIGN KEY (artist_id) REFERENCES {$table_pre}artists(id) ON DELETE CASCADE
              ) $charset_collate;";
    }

    private static function createArtistsSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}artists(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `name` varchar(100) ,
                  `artist_url` varchar(255),
                  `created_at` DATETIME,
                  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (id),
                  UNIQUE(`name`)
              ) $charset_collate;";
    }

    private static function createAChartsSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}charts(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `name` varchar(100),
                  `created_at` DATETIME,
                  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (id),
                  UNIQUE chart_name (`name`)
              ) $charset_collate;";
    }

    private static function createMusicChartsSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}music_charts(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `song_id` INT NOT NULL,
                  `ranking` INT NULL,
                  `status` ENUM('active', 'inactive') DEFAULT 'active',
                  `chart_id` INT NOT NULL,
                  `week` DATETIME NULL,
                  `created_at` DATETIME,
                  PRIMARY KEY (id),
                  UNIQUE chart_weekly_song (`song_id`, `chart_id`, `week`),
                  CONSTRAINT fk_song_id FOREIGN KEY (song_id) REFERENCES {$table_pre}songs(id) ON DELETE CASCADE,
                  CONSTRAINT fk_chart_id FOREIGN KEY (chart_id) REFERENCES {$table_pre}charts(id) ON DELETE CASCADE
              ) $charset_collate;";
    }

    private static function createRatingsSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}ratings(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `user_id` INT NOT NULL,
                  `song_id` INT NOT NULL,
                  `rate` DOUBLE NOT NULL,
                  `created_at` DATETIME,
                  PRIMARY KEY (id),
                  UNIQUE user_id_song_id (`song_id`, `user_id`),
                  CONSTRAINT fk_rating_song_id FOREIGN KEY (song_id) REFERENCES {$table_pre}songs(id) ON DELETE CASCADE
              ) $charset_collate;";
    }

    private static function createVotesSQL($table_pre){
        global $wpdb;
        $table_pre = $wpdb->prefix . $table_pre;
        $charset_collate = $wpdb->get_charset_collate();
        return  "CREATE TABLE IF NOT EXISTS {$table_pre}votes(
                  `id` INT NOT NULL AUTO_INCREMENT,
                  `user_id` INT NOT NULL,
                  `music_chart_id` INT NOT NULL,
                  `created_at` DATETIME,
                  PRIMARY KEY (id),
                  /*UNIQUE user_song_votes (`music_chart_id`, `user_id`),*/
                  CONSTRAINT fk_vote_music_chart_id FOREIGN KEY (music_chart_id) REFERENCES {$table_pre}music_charts(id) ON DELETE CASCADE
              ) $charset_collate;";
    }
}
