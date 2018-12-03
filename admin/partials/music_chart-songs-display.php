<div class="wrap">
    <?php
        //create song
        include_once 'actions/create.php';
        //edit song
        include_once 'actions/edit.php';
        //delete song
        include_once 'actions/delete.php'

    ?>
    <h1>Songs <button onclick="jQuery('#new_song_form').toggle()"class="page-title-action">Add New</button></h1>

    <form id="new_song_form" method="post" style="display: none;">
        <div class="row">
            <div class="col-sm-4">
                <h4>Step 1: Choose an Artist</h4>
                <hr>
                <div id="choose_artist">
                    <?php include_once 'forms/choose_artist.php' ?>
                </div>
                <div id="new_artist" style="display: none">
                    <button type="button" class="btnn btn-info" onclick="hide_new_artist()">Choose Artist</button>
                    <?php include_once 'forms/new_artist.php' ?>
                </div>
            </div>
            <div class="col-sm-4">
                <h4>Step 2: Choose an Album</h4>
                <hr>
                <div id="choose_album">
                    <?php include_once 'forms/choose_album.php' ?>
                </div>
                <div id="new_album" style="display: none">
                    <button type="button" class="toggle btnn btn-info" onclick="hide_new_album()">Choose Album</button>
                    <?php include_once 'forms/new_album.php' ?>
                </div>
            </div>
            <div class="col-sm-4" style="">

                <h4>Step 3: Upload a Song</h4>
                <hr>
                <div>
                    <?php include_once 'forms/new_song.php' ?>
                </div>
                <button type="submit" class="btnn btn-primary">Create</button>

            </div>
        </div>
    </form>
    <?php
    $album_id = $_GET['album_id'];
    $songs = Music_chart_DB::get_songs(0, $album_id);
    //var_dump($songs);

    if($album_id and $album = Music_chart_DB::get_album($album_id)){
        print '<h4>Album: '.$album->name.'. Artist '.$album->artist_name.'</h4>';
    }
    ?>
    <form method="post">
        <div class="form-group">
            <label>
                <select name="action" >
                    <option value="">Choose an Action</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button type="submit" name="go" value="go" class="button-secondary">Go</button>
            </label>
        </div>
        <table class="dataTables table table-striped table-hovered ">
            <thead>
            <tr>
                <th><input type="checkbox" id="select_all"></th>
                <th>Name</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($songs as $i => $song): ?>
                <tr class="check-column" scope="row">
                    <td><input name="songs_id[]" type="checkbox" value="<?php print $song->id ?>"></td>
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        <!-- content -->
                        <div id="text-label<?php print $song->id ?>" class="text-label">
                            <strong><?php print $song->name ?></strong>
                            <div class="row-actions">
                                <span class="inline hide-if-no-js"><a href="#" class="editinline" aria-label="Edit “<?php print $song->name ?>” now">Edit</a> | </span>
                                <span class="delete">
                                    <button style="display:none" type="submit" name="delete_song" value="<?php print $song->id ?>"></button>
                                    <a href="" class="submitdelete" aria-label="Delete “<?php print $song->name ?>” permanently, including all the ratings, votes and chart listings? ">Delete</a>
                            </div>
                        </div>
                        <!-- form -->
                        <div id="hidden-form<?php print $song->id ?>" class="hidden-form" style="display: none">
                            <label><b>Edit Song</b></label><br>
                            <div class="upload_audio"  style="cursor: pointer;" id="song_upload">
                                <audio controls src="" style="width: 100%">
                                </audio><br>
                                <button type="button" class="page-title-action" >Choose Song</button><br>
                                <label><b><i>Or Paste the direct URL here</i></b></label><br>
                                <input name="edit_song_url[<?php print $song->id ?>]" value="<?php print $song->url ?>" class="form-control" placeholder="Or Enter Song Url" onchange="jQuery('#song_upload').find('audio').attr({'src':this.value})" >
                            </div>
                            <div class="form-group">
                                <label class="form-field" for="name"><b>Song Name</b></label><br>
                                <input id="name" name="edit_song_name[<?php print $song->id ?>]" value="<?php print $song->name ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-field" for="genre"><b>Genre</b></label><br>
                                <input id="genre" name="edit_song_genre[<?php print $song->id ?>]" value="<?php print $song->genre ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-field" for="youtube_url"><b>Youtube Link</b></label><br>
                                <input type="url" id="youtube_url" name="edit_song_youtube_url[<?php print $song->id ?>]" value="<?php print $song->youtube_url ?>" class="form-control" placeholder="Link to the youtube video">
                            </div>
                            <div class="form-group">
                                <label class="form-field" for="amason_url"><b>Amazon Link</b></label><br>
                                <input type="url" id="amason_url" name="edit_song_amazon_url[<?php print $song->id ?>]" class="form-control" value="<?php print $song->amazon_url ?>" placeholder="Amazon Link">
                            </div>
                            <div class="form-group">
                                <label class="form-field" for="itunes_url"><b>ITunes Link</b></label><br>
                                <input type="url" id="itunes_url" name="edit_song_itunes_url[<?php print $song->id ?>]" value="<?php print $song->itunes_url ?>" class="form-control" placeholder="ITunes Link">
                            </div>

                            <!-- buttons -->
                            <div class="col-sm-6">
                                <button type="button" class="button-secondary cancel">Cancel</button>
                            </div>
                            <div class="col-sm-6" style="text-align: right">
                                <button type="submit" class="button-primary save alignright" name="edit_song" value="<?php print $song->id ?>" >Edit</button>
                            </div>
                        </div>
                    </td>
                    <td><a href="?page=music_chart-albums&artist_id=<?php print $song->artist_id ?>"><?php print $song->artist_name ?></a></td>
                    <td><?php print $song->album_name ?></td>
                    <td>Created
                        <?php $date = new DateTime($song->created_at);  print $date->format('Y/m/d')?><br>
                        Edited
                        <?php $date = new DateTime($song->modified_at);  print $date->format('Y/m/d')?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
<?php
include_once 'forms/form_scripts.php';