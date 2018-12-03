<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>New Music Chart</legend>

        <div class="form-group">
            <label class="form-field" for="name">Title</label>
            <input id="name" name="name" class="form-control" required placeholder="Enter Chart Title">
        </div>
        <button class="btnn btn-primary" type="submit">Create</button>
    </fieldset>
</form>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Add Artist</legend>

        <div class="form-group">
            <label class="form-field" for="name">Artist Name</label>
            <input id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-field" for="artist_url">Artist Website</label>
            <input type="url" id="artist_url" name="artist_url" class="form-control">
        </div>
        <button class="btnn btn-primary" type="submit">Create</button>
    </fieldset>
</form>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Add Album</legend>

        <div class="form-group">
            <label class="form-field" for="name">Album Name</label>
            <input id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-field" for="artist">Artist</label>
            <select id="artist" name="artist" class="form-control">
                <option></option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-field" for="album_url">Album Website</label>
            <input type="url" id="album_url" name="album_url" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-field" for="cover">Album Cover</label>
            <input type="file" id="cover" name="album_cover" class="form-control">
        </div>

        <button class="btnn btn-primary" type="submit">Create</button>
    </fieldset>
</form>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Add Song</legend>

        <div class="form-group">
            <label class="form-field" for="name">Song Name</label>
            <input id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-field" for="album">Album</label>
            <select id="album" name="album" class="form-control">
                <option></option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-field" for="genre">Genre</label>
            <input id="genre" name="genre" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-field" for="youtube_url">Youtube Link</label>
            <input type="url" id="youtube_url" name="youtube_url" class="form-control" placeholder="Link to the youtube video">
        </div>
        <div class="form-group">
            <label class="form-field" for="amason_url">Amazon Link</label>
            <input type="url" id="amason_url" name="amason_url" class="form-control" placeholder="Amazon Link">
        </div>
        <div class="form-group">
            <label class="form-field" for="itunes_url">ITunes Link</label>
            <input type="url" id="itunes_url" name="itunes_url" class="form-control" placeholder="ITunes Link">
        </div>
        <div class="form-group">
            <label class="form-field" for="other_url">Other Link</label>
            <input type="url" id="other_url" name="other_url" class="form-control" placeholder="Other Link">
        </div>

        <button class="btnn btn-primary" type="submit">Create</button>
    </fieldset>
</form>