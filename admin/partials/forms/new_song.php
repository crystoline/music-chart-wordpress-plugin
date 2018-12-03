<fieldset>
    <label><b>Upload Song</b></label><br>
    <div class="upload_audio" style="cursor: pointer;" id="song_upload">
        <audio controls autoplay style="width: 100%">
        </audio><br>
        <button type="button" class="page-title-action" >Choose Song</button><br>
        <label><b><i>Or Paste the direct URL here</i></b></label><br>
        <input name="song_url" class="form-control" placeholder="Or Enter Song Url" onchange="jQuery('#song_upload').find('audio').attr({'src':this.value})" >
    </div>
    <div class="form-group">
        <label class="form-field" for="name"><b>Song Name</b></label><br>
        <input id="name" name="song_name" class="form-control" required>
    </div>


    <div class="form-group">
        <label class="form-field" for="genre"><b>Genre</b></label><br>
        <input id="genre" name="song_genre" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="form-field" for="youtube_url"><b>Youtube Link</b></label><br>
        <input type="url" id="youtube_url" name="song_youtube_url" class="form-control" placeholder="Link to the youtube video">
    </div>
    <div class="form-group">
        <label class="form-field" for="amazon_url"><b>Amazon Link</b></label><br>
        <input type="url" id="amazon_url" name="song_amzson_url" class="form-control" placeholder="Amazon Link">
    </div>
    <div class="form-group">
        <label class="form-field" for="itunes_url"><b>ITunes Link</b></label><br>
        <input type="url" id="itunes_url" name="song_itunes_url" class="form-control" placeholder="ITunes Link">
    </div>
</fieldset>