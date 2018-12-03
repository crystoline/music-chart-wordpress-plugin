<div class="wrap">
    <?php
    if(isset($_GET['edit_chart'])){
       include_once 'edit_chart.php';
    }/*elseif(!empty($_GET['my_page'])){
        switch($_GET['my_page']){
            case 'add_chart_song': include_once 'add_chart_song.php'; break;
        }
    }*/else{


    ?>
    <h1>My Music Charts <button class="page-title-action" onclick="jQuery(create_chart).toggle()">Add New</button></h1>
    <?php
include_once 'actions/delete.php';
include_once 'actions/create.php';
include_once 'actions/edit.php';


$data = Music_chart_DB::all_charts();
        foreach($data as $chart){
            if(Music_chart_DB::chart_week_ended($chart->id)){
                Music_chart_DB::migrate_chart_song($chart->id);
            }
        }
?>


    <form method="post" name="create_chart" style="display: none; max-width: 400px">
        <fieldset >
            <legend>Create New Music Chart</legend>
            <div class="form-group">
                <label for="chart_name"><b>Title</b></label>
                <input id="chart_name" name="chart_name" required placeholder="Enter Chart Title" autocomplete="off" class="form-control">


            </div>
            <button class="button-primary" type="submit">Create</button>
        </fieldset>

    </form><br>
<form method="post" action="?page=music_chart">
    <select name="action">
        <option value="">Choose an Action</option>
        <option value="delete">Delete Selected</option>
    </select>
    <button class="button action" type="submit">Go</button>
<table class="table table-hover table-striped">

    <thead>
        <tr>
            <th><input type="checkbox" id="select_all"></th>
            <th>Name</th>
            <th>Songs</th>
            <th>Short Code</th>
            <th>Date Created</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($data as $chart):
    ?>
        <tr>
            <td><input type="checkbox" name="charts_id[]" value="<?php print $chart->id ?>"></td>
            <td>
                <!-- content -->
                <div class="text-label">
                    <strong><a href="?page=music_chart&edit_chart=<?php print $chart->id ?>"><?php print $chart->name ?></a></strong>
                    <div class="row-actions">
                        <span><a href="?page=music_chart&edit_chart=<?php print $chart->id ?>">View Songs</a> | </span>
                        <span class="inline hide-if-no-js"><a href="#" class="editinline" aria-label="Edit “<?php print $chart->name ?>” now">Rename</a> | </span>

                        <span class="delete">
                                    <button style="display:none" type="submit" name="delete_chart" value="<?php print $chart->id ?>"></button>
                                    <a href="" class="submitdelete" aria-label="Delete “<?php print $chart->name ?>” permanently, Including all it's rating, and votes? ">Delete</a>
                    </div>
                </div>
                <!-- form -->
                <div id="hidden-form<?php print $artist->id ?>" class="hidden-form" style="display: none">
                    <div class="form-group">
                        <label for="chart_name"><b>Edit Title</b></label>
                        <input id="chart_name" name="edit_chart_name[<?php print $chart->id ?>]" required value="<?php print $chart->name ?>" autocomplete="off" class="form-control">
                    </div>
                    <!-- buttons -->
                    <div class="col-sm-6">
                        <button type="button" class="button-secondary cancel">Cancel</button>
                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <button type="submit" class="button-primary save alignright" name="edit_chart" value="<?php print $chart->id ?>" >Edit</button>
                    </div>
                </div>


            </td>
            <td><?php print $chart->songs ?></td>
            <td>
                <label for="wpcf7-shortcode"></label>
                    <span class="shortcode wp-ui-highlight">
                        <input type="text" onfocus="this.select();" readonly="readonly"
                               value="[my-music-chart id=&quot;<?php print $chart->id?>&quot; top=&quot;10&quot;]">
                    </span>
            </td>
            <td><?php print $chart->created_at ?></td>
        </tr>
    <?php
        endforeach;
    ?>
    </tbody>
</table>
</form>
    <?php
    }
    ?>
</div>