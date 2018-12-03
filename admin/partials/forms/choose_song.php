<div style="text-align: right"> <button type="button" class="btnn btn-warning" onclick="show_new_song()">New Song <span class="glyphicon glyphicon-plus"></button></div>
<label for="song_id"><b>Songs from <span class="song_from">Album</span> ,by <span class="album_by">Artist</span></b></label>
<select name="song_id" id="song_id" class="form-control" placeholder="Choose Song" required
        onchange="show_song_details(this.value)">
    <option></option>
</select>

<div id="song_detail" style="display: none">
    <audio controls autoplay style="width: 100%"></audio>
    <div class="form-field" for="genre"><b>Genre:</b> <span id="s_genre"></span></div>
    <div class="form-field" for="youtube_url"><b>Youtube Link:</b><span id="s_youtube"></span></div>
    <div class="form-field" for="amazon_url"><b>Amazon Link:</b><span id="s_amazon"></span></div>
    <div class="form-field" for="itunes_url"><b>ITunes Link:</b><span id="s_itunes"></span></div>
</div>

<label><b>Status</b></label>
<label>Active &nbsp;<input type="radio" name="song_status" value="active" checked="checked" required></label> &nbsp;
<label>Inactive &nbsp;<input type="radio" name="song_status" value="inactive" required></label>