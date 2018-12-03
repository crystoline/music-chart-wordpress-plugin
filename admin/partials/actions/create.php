<?php

if($_POST){
    {//prevent flooding global scope with variables
        extract($_POST);

        /*if(isset($artist_name)){ //create new Artist
            if(Music_chart_DB::artist_exist($artist_name)){
                print '<div id="message" class="notice notice-success is-dismissible"><p>Artist record exist. You may select artist from the list or create new using another name</p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                    </div>';
            }else{
                $artist_id = Music_chart_DB::create_artist($artist_name, $artist_url);
            }
        }*/
        //create album for artist
        if (isset($artist_name)) { //create new Artist
            if (Music_chart_DB::artist_exist($artist_name)) {
                print '<div id="message" class="notice notice-warning is-dismissible"><p>Artist record exist. You may select artist from the list or create new using another name</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            } elseif ($artist_id = Music_chart_DB::create_artist($artist_name, $artist_url)) {
                print '<div id="message" class="notice notice-success is-dismissible"><p>Artist \'' . $artist_name . '\' was created</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            } else {
                print '<div id="message" class="notice notice-error is-dismissible"><p>Artist \'' . $artist_name . '\' was not created</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            }

        }
        //create album for album
        /*if(isset($album_name) and isset($artist_id)){
            if(Music_chart_DB::artist_albums_exist($artist_id,$album_name)){
                print '<div id="message" class="notice notice-success is-dismissible"><p>Album record exist. You may select Album from the list or create new using another name</p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                    </div>';
            }else{
                $album_id = Music_chart_DB::create_artist_album($album_name, $artist_id, @$album_url, @$album_cover);
            }
        }*/
        //create album for artist
        if (isset($album_name) and isset($artist_id)) {
            if (Music_chart_DB::artist_albums_exist($artist_id, $album_name)) {
                print '<div id="message" class="notice notice-warning is-dismissible"><p>Album record exist. You may select Album from the list or create new using another name</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            } elseif ($album_id = Music_chart_DB::create_artist_album($album_name, $artist_id, @$album_url, @$album_cover)) {
                print '<div id="message" class="notice notice-success is-dismissible"><p>Album \'' . $album_name . '\' was created</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            } else {
                print '<div id="message" class="notice notice-error is-dismissible"><p>Album \'' . $album_name . '\' was not created</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            }
        }


        /*if(isset($song_name) and isset($album_id)){ //create new Artist
            if(Music_chart_DB::albums_song_exist($album_id,$song_name)){
                print '<div id="message" class="notice notice-error is-dismissible"><p>Song record exist. You may select Album from the list or create new using another name</p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                    </div>';
            }else{
                $song_id = Music_chart_DB::create_album_song($song_name,$album_id,$song_url,$song_genre,$song_youtube_url, $song_amazon_url, $song_itunes_url);
            }
        }*/
        //create song album
        if (isset($song_name) and isset($album_id)) { //create new Artist
            if (Music_chart_DB::albums_song_exist($album_id, $song_name)) {
                print '<div id="message" class="notice notice-error is-dismissible"><p>Song record exist. You may select Album from the list or create new using another name</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            } elseif ($song_id = Music_chart_DB::create_album_song($song_name, $album_id, $song_url, $song_genre, $song_youtube_url, $song_amazon_url, $song_itunes_url)) {
                print '<div id="message" class="notice notice-success is-dismissible"><p>Song \'' . $song_name . '\' was created</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            } else {
                print '<div id="message" class="notice notice-error is-dismissible"><p>Song \'' . $song_name . '\' was not created</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            }
        }

        //add song to chart
        if (isset($song_id) and isset($song_status)) {
            if(Music_chart_DB::chart_song_exist($chart_id, $song_id)){
                print '<div id="message" class="notice notice-error is-dismissible"><p>Song already exist in current music chart</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            }
            elseif (Music_chart_DB::create_music_chart_song($chart_id, $song_id, $song_status)) {
                print '<div id="message" class="notice notice-success is-dismissible"><p>Song was added to Music Chart</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            }else{
                print '<div id="message" class="notice notice-error is-dismissible"><p>Song \'' . $song_name . '\' was not added to Music Chart</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
            }
        }
    }
}
//create music chart
if(isset($_POST['chart_name'])){

    if(Music_chart_DB::create_chart($_POST['chart_name'])){
        print '<div id="message" class="notice notice-success is-dismissible"><p>New Music Chart was created.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>An Error occurred while creating the music chart.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}