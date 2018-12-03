<h1>My Music Chart</h1>

<?php
@$chart_id = (int) $_GET['edit_chart'];
if(Music_chart_DB::chart_week_ended($chart_id)){
    Music_chart_DB::migrate_chart_song($chart_id);
}

include_once 'actions/create.php';
include_once 'actions/delete.php';
include_once 'actions/edit.php';


if($chart = Music_chart_DB::get_chart($chart_id)){
print '<h3>'.$chart->name.' <button class="page-title-action" onclick="jQuery(create_chart).toggle(200)">Add Song</button></h3>';
    //var_dump($_POST);
    ?>

    <form method="post" name="create_chart" style="border: grey solid thin; padding: 5px; display: none" action="?page=music_chart&edit_chart=<?php print $chart_id?>">

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
            <div class="col-sm-4" style=" min-height: 400px">

                <h4>Step 3: Select a Song</h4>
                <hr>
                <div id="choose_song">
                    <?php include_once 'forms/choose_song.php'?>
                </div>
                <div id="new_song" style="display: none">
                    <button type="button" class="toggle btnn btn-info" onclick="hide_new_song()">Choose Song</button>
                    <?php include_once 'forms/new_song.php' ?>
                    <div class="form-group">
                        <label class="form-field"><b>Status</b></label><br>
                        <label>Active &nbsp;<input type="radio" name="song_status" value="active" checked required></label> &nbsp;
                        <label>Inactive &nbsp;<input class="ignore" type="radio" name="song_status" value="inactive" required></label>
                    </div>
                </div>
                <button type="submit" class="btnn btn-primary">Create</button>
            </div>
        </div>
        <br>

    </form>
    <div class="inside">
        <p class="description">
            <label for="wpcf7-shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label>
            <span class="shortcode wp-ui-highlight"><input type="text" id="wpcf7-shortcode"
                onfocus="this.select();" readonly="readonly" class="large-text code"
                value="[my-music-chart id=&quot;<?php print $chart->id?>&quot; top=&quot;10&quot;]"></span>
        </p>
    </div>
    <?php
        //var_dump(Music_chart_DB::chart_weeks($chart->id));
        @$week = $_GET['week']? : 0;
        $songs = Music_chart_DB::all_chart_songs($chart->id,'all',0,1,$week);
   //var_dump($songs);

    ?>
    <style>
        #sortable tr td:not(input){
            cursor: move;
        }
        .sort-item{
            width: 100%;
        }
    </style>
    <table width="100%">
        <tr>
            <td><a class="button-secondary" href="<?php print '?page=music_chart&edit_chart='.$chart_id.'&week='.($week+1)?>">&laquo; Previous</a></td>
            <td align="center">
                <?php if(isset($songs[0]) ) {
                    $week_date = new DateTime($songs[0]->week);
                    print $week_date->format('Y-m-d');
                }?>
            </td>


            <td align="right" <?php print  ($week == 0)? 'style="visibility: hidden"': '' ?>>
                <a class="button-secondary"  href="<?php print '?page=music_chart&edit_chart='.$chart_id.'&week='.($week-1)?>">Next &raquo;</a>

            </td>

        </tr>
    </table>

    <form name="set_ranking" onsubmit="" method="post">
    <?php if($week == 0){ ?>
        <div class="form-group" style="">
            <label>
                <select name="action" >
                    <option value="">Choose an Action</option>
                    <option value="delete">Delete Selected</option>
                    <option value="disable">Disable Selected</option>
                    <option value="enable">Enable Selected</option>
                </select>

            </label>
            <button type="submit" name="go" value="go" class="button-secondary">Go</button>
            <button type="button" onclick="save_ranking(set_ranking)" class="btn_set_ranking btn-primary">Save Changes</button>
        </div>

    <?php }?>
    <table class="dataTables-noPaging table striped table-hovered pages">
        <thead>
            <tr>
    <?php if($week == 0){ ?> <th><input type="checkbox" id="select_all"></th> <?php }?>
                <th>Rank</th>
                <th>Song Name</th>
                <th>Album</th>
                <th>Artist</th>
                <th>Genre</th>
                <th>Votes</th>
                <th>Ratings</th>
            </tr>
        </thead>
        <tbody <?php print  ($week == 0)? 'id="sortable"': '' ?>>
        <?php
        foreach($songs as $i => $song):
        ?>
            <tr>
                <td> <?php if($week == 0){ ?>
                    <input name="music_chart_songs_id[]" type="checkbox" value="<?php print $song->music_chart_id ?>"></td>
                <td>

                    <input class="ranking_box" name="rankings[<?php print $song->music_chart_id ?>]" value="<?php print $i+1?>" size="2" style="width:  35px;"></td>
                    <?php } else {
                        print $song->ranking;
                    }
                    ?>
                <td class="sorting-item">
                    <!-- content -->
                    <div class="text-label">
                        <strong <?php if($song->status == 'inactive') print 'style="color:grey"'?>><?php print $song->name; print $song->status == 'inactive'? '(disabled)':'';?></strong>
                        <div class="row-actions" <?php print  ($week != 0)? 'style="display:none"': '' ?>>
                            <span class="change-status">
                                <?php
                                $s_status_field = ($song->status == 'inactive')? 'enable': 'disable';
                                $s_status_text = ($song->status == 'inactive')? 'Enable': 'Disable';
                                ?>
                                <button style="display:none" type="submit" name="<?php print $s_status_field ?>-status" value="<?php print $song->music_chart_id ?>"></button>
                                <a href="" class="submitstatus" aria-label="<?php print $s_status_text ?> “<?php print $song->name ?>”?"><?php print $s_status_text ?> </a> |
                            </span>
                            <span class="delete">
                                <button style="display:none" type="submit" name="remove_chart_song" value="<?php print $song->music_chart_id ?>"></button>
                                <a href="" class="submitdelete" aria-label="Removing “<?php print $song->name ?>” from list will permanently delete it's rating and votes? ">Remove</a>
                        </div>
                    </div>



                </td>
                <td class="sorting-item"><?php print $song->album_name?></td>
                <td class="sorting-item"><?php print $song->artist_name?></td>
                <td class="sorting-item"><?php print $song->genre?></td>
                <td class="sorting-item"><?php print $song->votes | 0?></td>
                <td class="sorting-item"><?php print $song->rating | 0?></td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
    </form>
<?php
}else{
    print '<div id="message" class="notice notice-error is-dismissible"><p>The selected Music Chart is not valid.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';
    print '<h4>Please click <a href="?page=music_chart">here</a> to return</h4>';
}
?>
<script>
    function reorderRanking (){
        var rank = 1;
        jQuery( "#sortable").find('.ranking_box').each(function(){
            jQuery(this).val(rank);
            rank++;
        })
    }
    jQuery( function() {

        jQuery('.ranking_box').change(function(){
            var $ = jQuery;
            var $table= $(this).closest('table');
            var rows = $table.find('tbody tr').get();

            rows.sort(function(a, b) {
                var valA = parseInt($(a).find('.ranking_box').val(),10);
                var valB = parseInt($(b).find('.ranking_box').val(),10);
                if (valA < valB) return -1;
                if (valA > valB) return 1;
                return 0;
            });
            $.each(rows, function(index, row) {
                $table.children('tbody').append(row);
            });
            reorderRanking();
            save_ranking(set_ranking);
        });
        jQuery( "#sortable" ).sortable({
            stop: function(event, ui) {
                console.log("New position: " + ui.placeholder.index());
                reorderRanking ();
                save_ranking(set_ranking);
                $(ui.item).removeClass("sort-item");
            },
            start: function( event, ui ) {
                $(ui.item).addClass("sort-item");
            }
        });
        jQuery( "#sortable" ).disableSelection();
        $ = jQuery;
        $(document).click(function(){
            $('#show_album_cover').html($('#album_cover').val())
        })

    } );

</script>
<?php
include_once 'forms/form_scripts.php';
?>