<div class="wrap">
    <?php

    include_once 'actions/create.php';
    include_once 'actions/edit.php';
    include_once 'actions/delete.php'
    ?>
    <h1>Albums <button onclick="jQuery('#new_album_form').toggle()" href="?page=music_chart-albums&new=1" class="page-title-action">Add New</button></h1>

    <form id="new_album_form" method="post" style="display: none;">
        <div class="col-sm-6">
            <h3>Step 1: Choose an Artist</h3>
            <hr>
            <div id="choose_artist">
                <?php include_once 'forms/choose_artist.php' ?>
            </div>
            <div id="new_artist" style="display: none">
                <button type="button" class="btnn btn-info" onclick="hide_new_artist()">Choose Artist</button>
                <?php include_once 'forms/new_artist.php' ?>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Step 2: Choose an Album</h3>
            <hr>
            <div>
                <?php include_once 'forms/new_album.php' ?>
            </div>
            <button type="submit" class="btnn btn-primary">Create Album</button>
        </div>
    </form>
    <?php
    $artist_id = $_GET['artist_id'];
    $albums = Music_chart_DB::get_albums($artist_id);
    if($artist_id and $artist = Music_chart_DB::get_artist($artist_id)){
        print '<h4>Albums by '.$artist->name.'</h4>';
    }
    //var_dump($albums);
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
        <table class="dataTables table table-striped table-hovered">
            <thead>
            <tr>
                <th><input type="checkbox" id="select_all"></th>
                <th></th>
                <th>Name</th>
                <th>Artist</th>
                <th>Songs</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($albums as $i => $album): ?>
                <tr class="check-column" scope="row">
                    <td><input name="albums_id[]" type="checkbox" value="<?php print $album->id ?>"></td>
                    <td><div style="width: 40px;height: 40px; background: url('<?php echo $album->cover? : 'http://dalelyles.com/musicmp3s/no_cover.jpg' ?>') no-repeat;background-size: cover;"></div> </td>
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">

                        <!-- content -->
                        <div id="text-label<?php print $artist->id ?>" class="text-label">
                            <strong><a href="?page=music_chart-songs&album_id=<?php print $album->id ?>"><?php print $album->name ?></a></strong>
                            <div class="row-actions">
                                <span class="inline hide-if-no-js"><a href="#" class="editinline" aria-label="Edit “<?php print $album->name ?>” now">Edit</a> | </span>
                                <span class="delete">
                                    <button style="display:none" type="submit" name="delete_album" value="<?php print $album->id ?>"></button>
                                    <a href="" class="submitdelete" aria-label="Delete “<?php print $album->name ?>” permanently, including all the songs? ">Delete</a>
                            </div>
                        </div>
                        <!-- form -->
                        <div id="hidden-form<?php print $artist->id ?>" class="hidden-form" style="display: none">
                            <label><b>Edit Album Cover</b></label><br>
                            <div class="upload_photo" style="cursor: pointer; display: inline-block; width:150px; height: 150px;
        background: url('<?php echo $album->cover? : 'http://dalelyles.com/musicmp3s/no_cover.jpg' ?>') no-repeat;background-size: cover;">
                                <input type="hidden" name="edit_album_cover[<?php print $album->id ?>]" value="<?php print $album->cover ?>">
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="form-field" for="name"><b>Album Name</b></label><br>
                                <input id="name" name="edit_album_name[<?php print $album->id ?>]" class="form-control" value="<?php print $album->name ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-field" for="album_url"><b>Album Website</b></label><br>
                                <input type="url" id="album_url" name="edit_album_url[<?php print $album->id ?>]" class="form-control" value="<?php print $album->album_url ?>">
                            </div>

                            <!-- buttons -->
                            <div class="col-sm-6">
                                <button type="button" class="button-secondary cancel">Cancel</button>
                            </div>
                            <div class="col-sm-6" style="text-align: right">
                                <button type="submit" class="button-primary save alignright" name="edit_album" value="<?php print $album->id ?>" >Edit</button>
                            </div>
                        </div>
                    </td>
                    <td><a href="?page=music_chart-artists&artist_id=<?php print $album->artist_id ?>"><?php print $album->artist_name ?></a></td>
                    <td><?php print $album->songs | 0 ?></td>
                    <td>Created
                        <?php $date = new DateTime($album->created_at);  print $date->format('Y/m/d')?><br>
                        Edited
                        <?php $date = new DateTime($album->modified_at);  print $date->format('Y/m/d')?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
<?php
include_once 'forms/form_scripts.php';