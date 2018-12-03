<div id="choose_album">
    <div style="text-align: right"> <button type="button" class="btnn btn-warning" onclick="show_new_album()">New Album <span class="glyphicon glyphicon-plus"></button></div>
    <label for="album_id"><b>Albums by <span class="album_by">?</span></b></label>
    <select name="album_id" id="album_id" class="form-control" onchange="get_album_songs(this.value)" required>
        <option></option>
    </select>
</div>