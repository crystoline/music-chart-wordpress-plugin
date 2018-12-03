<div class="wrap">
    <?php
    include_once 'actions/create.php';
    include_once 'actions/edit.php';
    include_once 'actions/delete.php'
    ?>
    <h1>Artists <button onclick="jQuery('#new_artist_form').toggle(400)" href="?page=music_chart-albums&new=1" class="page-title-action">Add New</button></h1>

    <form method="post" class="row">
        <div class="col-sm-6" id="new_artist_form"  style="display: none;">
           <div>
                <?php include_once 'forms/new_artist.php' ?>
            </div>
            <button type="submit" class="btnn btn-primary">Create Artist</button>
        </div>
    </form>
    <?php
    $artists = Music_chart_DB::all_artists();
    //var_dump($_POST);
    ?>
    <br>
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
                <th>Name</th>
                <th>Albums</th>
                <th>Songs</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($artists as $artist): ?>
                <tr class="check-column" scope="row">
                    <td><input name="artists_id[]" type="checkbox" value="<?php print $artist->id ?>"></td>
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        <!-- content -->
                        <div id="text-label<?php print $artist->id ?>" class="text-label">
                            <strong><a href="?page=music_chart-albums&artist_id=<?php print $artist->id ?>"><?php print $artist->name ?></a></strong>
                            <div class="row-actions">
                                <span class="inline hide-if-no-js"><a href="#" class="editinline" aria-label="Edit “<?php print $artist->name ?>” now">Edit</a> | </span>
                                <span class="delete">
                                    <button style="display:none" type="submit" name="delete_artist" value="<?php print $artist->id ?>"></button>
                                    <a href="" class="submitdelete" aria-label="Delete “<?php print $artist->name ?>” permanently, with all the albums and songs? ">Delete</a>
                            </div>
                        </div>
                        <!-- form -->
                       <div id="hidden-form<?php print $artist->id ?>" class="hidden-form" style="display: none">
                            <div class="form-group">
                                <label class="form-field" for="name"><b>Artist Name</b></label>
                                <br>
                                <input disabled type="text" id="name" name="edit_artist_name[<?php print $artist->id ?>]" class="form-control" value="<?php print $artist->name ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-field" for="artist_url"><b>Artist Website</b></label>
                                <br>
                                <input disabled type="url" id="artist_url" name="edit_artist_url[<?php print $artist->id ?>]" class="form-control" value="<?php print $artist->artist_url ?>">
                            </div>
                           <!-- buttons -->
                           <div class="col-sm-6">
                               <button type="button" class="button-secondary cancel">Cancel</button>
                           </div>
                               <div class="col-sm-6" style="text-align: right">
                               <button type="submit" class="button-primary save alignright" name="edit_artist" value="<?php print $artist->id ?>" >Edit</button>
                           </div>
                        </div>

                    </td>
                    <td><?php print $artist->albums | 0 ?></td>
                    <td><?php print $artist->songs | 0 ?></td>
                    <td>Created
                        <?php $date = new DateTime($artist->created_at);  print $date->format('Y/m/d')?><br>
                        Edited
                        <?php $date = new DateTime($artist->modified_at);  print $date->format('Y/m/d')?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
<?php
include_once 'forms/form_scripts.php';
?>
<script>
    jQuery(function(){
        /*var $ = jQuery;
        function show_edit_form(e){
            e.preventDefault();
            $('.cancel').each(function () {
                $(this).trigger('click');
            });
            $(this).parent().parent().parent().parent().find('.hidden-form').show().find('input').prop("disabled", false)//.attr('id');
            $(this).parent().parent().parent().hide()//attr('id');

        }
        function hide_edit_form(e){
            e.preventDefault();
            $(this).parent().parent().hide().find('input').prop("disabled", true)//.attr('id');
            $(this).parent().parent().parent().find('.text-label').show()//attr('id');
            //show_form(show_id,hide_id);
        }
        $('.editinline').click(show_edit_form);
        $('.cancel').click(hide_edit_form)
        $('.submitdelete').click(function(e){
            e.preventDefault();
            var text = $(this).attr('aria-label');
            if(confirm(text)) $(this).parent().find('button').trigger('click');
        });*/
    });
</script>
