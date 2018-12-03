<div id="choose_artist">
<div style="text-align: right"> <button type="button" class="btnn btn-warning" onclick="show_new_artist()">New Artist <span class="glyphicon glyphicon-plus"></span></button></div>
<label for="artist"><b>Choose an Artist</b></label><br>
<?php
if($artists = Music_chart_DB::all_artists()) {
    ?>
    <select name="artist_id" id="artist" class="form-control" required onchange="get_albums(this.value)">
        <option value="" selected disabled class="placeholder">Choose an Artist</option>
        <?php
        foreach($artists as $artist)
            print '<option value="'.$artist->id.'">'.$artist->name.'</option>';
        ?>
    </select>

    <?php
}else{
    print '<br>No artists yet';
}
?>
</div>
