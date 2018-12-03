<?php
//edit song
if(isset($_POST['edit_song'])){
    $edit_song_id      = $_POST['edit_song'];
    $edit_song_name    = $_POST['edit_song_name'][$edit_song_id];
    $edit_song_url     = $_POST['edit_song_url'][$edit_song_id];
    $edit_song_genre        = $_POST['edit_song_genre'][$edit_song_id];
    $edit_song_youtube_url  = $_POST['edit_song_youtube_url'][$edit_song_id];
    $edit_song_amazon_url   = $_POST['edit_song_amazon_url'][$edit_song_id];
    $edit_song_itunes_url   = $_POST['edit_song_itunes_url'][$edit_song_id];

    if(Music_chart_DB::edit_song($edit_song_id,$edit_song_name,$edit_song_url,$edit_song_genre,$edit_song_youtube_url, $edit_song_amazon_url, $edit_song_itunes_url)){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song info was updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Song info was not updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}

//edit album
if(isset($_POST['edit_album'])){
    $edit_album_id      = $_POST['edit_album'];
    $edit_album_name    = $_POST['edit_album_name'][$edit_album_id];
    $edit_album_cover   = $_POST['edit_album_cover'][$edit_album_id];
    $edit_album_url     = $_POST['edit_album_url'][$edit_album_id];
    if(Music_chart_DB::update_album($edit_album_id, $edit_album_name, $edit_album_url, $edit_album_cover)){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Album info was updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Album info was not updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}

//edit artist

if(isset($_POST['edit_artist'])){
    $edit_artist_id     = $_POST['edit_artist'];
    $edit_artist_name   = $_POST['edit_artist_name'][$edit_artist_id];
    $edit_artist_url   = $_POST['edit_artist_url'][$edit_artist_id];
    if(Music_chart_DB::update_artist($edit_artist_id, $edit_artist_name,$edit_artist_url)){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Artist info was updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Artist info was not updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}

//edit chart
if(isset($_POST['edit_chart'])){
    $edit_chart_id  = $_POST['edit_chart'];
    $edit_chart_name = $_POST['edit_chart_name'][$edit_chart_id];
    if(Music_chart_DB::update_chart($edit_chart_id, $edit_chart_name)){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Music Chart info was updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Music Chart info was not updated</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}
//change chart song status
if(isset($_POST['enable-status'])){
    if(Music_chart_DB::music_chart_songs_change_status(array($_POST['enable-status']), 'active')){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song was enabled</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Song could not be enabled</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}elseif(isset($_POST['disable-status'])){
    if(Music_chart_DB::music_chart_songs_change_status(array($_POST['disable-status']), 'inactive')){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song was disabled</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Song could not be disabled</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}elseif(isset($_POST['action']) and $_POST['action'] == 'enable' and !empty($_POST['music_chart_songs_id'] )){
    if(Music_chart_DB::music_chart_songs_change_status($_POST['music_chart_songs_id'], 'active')){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song(s) were enabled.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to enable song(s).</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}
elseif(isset($_POST['action']) and $_POST['action'] == 'disable' and !empty($_POST['music_chart_songs_id'] )){
    if(Music_chart_DB::music_chart_songs_change_status($_POST['music_chart_songs_id'], 'inactive')){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song(s) were disabled.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to disable song(s).</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}

