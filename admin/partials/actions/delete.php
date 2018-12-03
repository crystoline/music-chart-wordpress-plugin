<?php

//delete song
if(isset($_POST['delete_song'])){
    if(Music_chart_DB::delete_songs(array($_POST['delete_song']) )){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song was deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>SOng  was not deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}
elseif(@$_POST['action'] == 'delete' and !empty($_POST['songs_id'])){
    if(Music_chart_DB::delete_songs($_POST['songs_id'])){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Songs were deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to delete song </p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}


//delete album
if(isset($_POST['delete_album'])){
    if(Music_chart_DB::delete_albums(array($_POST['delete_album']) )){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Album info was deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Album info was not deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}
elseif(@$_POST['action'] == 'delete' and !empty($_POST['albums_id'])){
    if(Music_chart_DB::delete_albums($_POST['albums_id'])){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Albums info were deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to delete Albums info</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}

//delete artist
if(isset($_POST['delete_artist'])){
    if(Music_chart_DB::delete_artists(array($_POST['delete_artist']) )){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Artist info was deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Artist info was not deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}
elseif(@$_POST['action'] == 'delete' and !empty($_POST['artists_id'])){
    if(Music_chart_DB::delete_artists($_POST['artists_id'])){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Artists info were deleted</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to delete Artists info</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}

//delete charts
if(isset($_POST['delete_chart'])){
    if(Music_chart_DB::delete_charts(array($_POST['delete_chart']))){
        print '<div id="message" class="notice notice-success is-dismissible"><p>One Music chart has been deleted.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to deleted music chart.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}
elseif(isset($_POST['action']) and $_POST['action'] == 'delete' and !empty($_POST['charts_id']) ){
    $i = Music_chart_DB::delete_charts($_POST['charts_id']);
    if($i){
        print '<div id="message" class="notice notice-success is-dismissible"><p>'.$i.' Record(s) were deleted.</p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to deleted music charts.</p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>';
    }

}

//remove chart songs
if(isset($_POST['remove_chart_song'])){
    if(Music_chart_DB::delete_music_chart_song(array($_POST['remove_chart_song']))){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song has been removed.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to remove song.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}elseif(isset($_POST['action']) and $_POST['action'] == 'delete' and !empty($_POST['music_chart_songs_id'] )){
    if(Music_chart_DB::delete_music_chart_song($_POST['music_chart_songs_id'])){
        print '<div id="message" class="notice notice-success is-dismissible"><p>Song(s) were removed.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }else{
        print '<div id="message" class="notice notice-error is-dismissible"><p>Unable to remove song(s).</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    }
}