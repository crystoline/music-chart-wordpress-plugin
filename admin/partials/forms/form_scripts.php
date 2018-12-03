<script>
    function show_form(dst_id,hide_id){
        var $ = jQuery;
        $(hide_id).hide(100);
        $(dst_id).show(100);
        $(hide_id+' input:not([type=radio]), '+hide_id+' select').prop("disabled", true).val('');
        $(dst_id+' input:not([type=radio]), '+dst_id+' select').prop("disabled", false).val('');
    }
    function hide_form(hide_id,dst_id){
        var $ = jQuery;
        $(hide_id).hide(100);
        $(dst_id).show(100);
        $(hide_id+' input:not([type=radio]), '+hide_id+' select').prop("disabled", true).val('');
        $(dst_id+' input:not([type=radio]), '+dst_id+' select').prop("disabled", false).val('');
    }
    function show_new_artist(){
        show_form('#new_artist','#choose_artist'); //show new artist form
        show_form('#new_album','#choose_album'); //show new album form
        show_form('#new_song','#choose_song'); //show new song form
        jQuery('#new_album button.toggle').hide();
        jQuery('#new_song button.toggle').hide();
        hide_song_details();

    }
    function hide_new_artist(){
        hide_form('#new_artist','#choose_artist'); //show new artist form
        hide_form('#new_album','#choose_album'); //show new album form
        hide_form('#new_song','#choose_song'); //show new song form
        jQuery('#new_album button.toggle').show();
        jQuery('#new_song button.toggle').show();
        jQuery('#new_song').find('audio').attr({src:''});
    }
    function show_new_album(){
        show_form('#new_album','#choose_album'); //show new album form
        show_form('#new_song','#choose_song'); //show new song form
        jQuery('#new_song button.toggle').hide();
        hide_song_details();
    }
    function hide_new_album(){
        hide_form('#new_album','#choose_album'); //show new album form
        hide_form('#new_song','#choose_song'); //show new song form
        jQuery('#new_song button.toggle').show();
        jQuery('#new_song').find('audio').attr({src:''});
    }
    function show_new_song(){
        show_form('#new_song','#choose_song'); //show new song form
        hide_song_details();
    }
    function hide_new_song(){
        hide_form('#new_song','#choose_song'); //show new song form
        jQuery('#new_song').find('audio').attr({src:''});
    }

    var mysongs =[]; //album_by
    var myartists = <?php print json_encode($artists) ?>;
    var myalbums = [];
    var retry = 0;

    function show_song_details(song_id){
        var $ = jQuery;
        for(var i in mysongs){
            var song = mysongs[i];
            if(song.id == song_id){
                $('#song_detail audio').attr({'src': song.url});
                $('#song_detail #s_genre').text(song.genre);
                $('#song_detail #s_youtube').text(song.youtube_url);
                $('#song_detail #s_amazon').text(song.amazon_url);
                $('#song_detail #s_itunes').text(song.itunes_url);
                $('#song_detail').show(300);
                return;
            }

        }
    }
    function hide_song_details(){
        var $ = jQuery;
        $('#song_detail').hide(300);
        $('#song_detail audio').attr({'src': ''});

    }
    function show_artist_name(artist_id){
        var $ = jQuery;
        for(var i in myartists){
            var myartist = myartists[i];
            if(myartist.id == artist_id){
                $('.album_by').html(myartist.name);
                return;
            }
        }
    }

    function show_album_name(album_id){
        var $ = jQuery;
        for(var i in myartists){
            var myalbum = myalbums[i];
            if(myalbum.id == album_id){
                $('.song_from').html(myalbum.name);
                return;
            }
        }
    }
    jQuery(function(){
        hide_form('new_artist','choose_artist'); //show new artist form
        hide_form('new_album','choose_album'); //show new album form
        hide_form('new_song','choose_song'); //show new song form
    })

    hide_new_artist();//fix for form not submitting
    jQuery('.dataTables').dataTable();
    jQuery('.dataTables-noPaging').dataTable({bPaginate: false});

</script>