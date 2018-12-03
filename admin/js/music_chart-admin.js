(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	jQuery(document).ready(function($){
		$('.upload_photo').click(function(e) {
			var $this = this;
			e.preventDefault();
			var image = wp.media({
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false,
				library: { type : 'image' }
			}).open()
					.on('select', function(e){
						// This will return the selected image from the Media Uploader, the result is an object
						var uploaded_image = image.state().get('selection').first();
						// We convert uploaded_image to a JSON object to make accessing it easier
						// Output to the console uploaded_image
						console.log(uploaded_image);
						var image_url = uploaded_image.toJSON().url;
						// Let's assign the url value to the input field
						$($this).find('input').val(image_url);
						$($this).css({'background-image': "url('"+image_url+"')"});
					});
		});
		$(".upload_audio button").click(function(e) {
			var $this = this;
			e.preventDefault();
			var audio = wp.media({
				title: 'Upload Song',
				// mutiple: true if you want to upload multiple files at once
				multiple: false,
				library: { type : 'audio' }
			}).open()
					.on('select', function(e){
						// This will return the selected image from the Media Uploader, the result is an object
						var uploaded_audio = audio.state().get('selection').first();
						// We convert uploaded_image to a JSON object to make accessing it easier
						// Output to the console uploaded_image
						console.log(uploaded_audio.toJSON());
						var audio_url = uploaded_audio.toJSON().url;
						// Let's assign the url value to the input field
						//alert(audio_url);
						$($this).parent().find('input').val(audio_url);
						$($this).parent().find('audio').attr({'src': audio_url});
					});
		});
        function show_edit_form(e){
            e.preventDefault();
            $('.cancel').each(function () {
                $(this).trigger('click');
            });
            $(this).parent().parent().parent().parent().find('.hidden-form').show(200).find('input').prop("disabled", false)//.attr('id');
            $(this).parent().parent().parent().hide(200)//attr('id');
            $(this).parent().parent().parent().parent().find('.hidden-form').one( "mouseout", function() {
                hide_edit_form(e);
            })


        }
        function hide_edit_form(e){
            e.preventDefault();
            $(this).parent().parent().hide(200).find('input').prop("disabled", true)//.attr('id');
            $(this).parent().parent().parent().find('.text-label').show(200)//attr('id');
            //show_form(show_id,hide_id);
            jQuery('.upload_audio').find('audio').attr({src:''});
        }
        $('.editinline').click(show_edit_form);
        $('.cancel').click(hide_edit_form)
        $('.submitdelete').click(function(e){
            e.preventDefault();
            var text = $(this).attr('aria-label');
            if(confirm(text)) $(this).parent().find('button').trigger('click');
        });
        $('.submitstatus').click(function(e){
            e.preventDefault();
            var text = $(this).attr('aria-label');
            if(confirm(text)) $(this).parent().find('button').trigger('click');
        });

        $('#select_all').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
	});






})( jQuery );


function get_album_songs(album_id) {
    hide_new_song();
    show_album_name(album_id);
    var $ = jQuery;
    $.ajax({
        url: wpApiSettings.root+'music-charts/v1/album/get_songs',
        type: 'get',
        data: {
            album_id: album_id
        },
        success: function (songs) {
            mysongs = songs;
            //console.log(albums);
            if (songs.length == 0) {
                jQuery("#song_id").html('<option value="" selected disabled class="placeholder">No Songs Available</option>');
                return;
            }
            var str = '<option selected disabled class="placeholder" value="">Select a song</option>';
            for (var i in songs) {
                var song = songs[i];
                str += '<option value="' + song.id + '">' + song.name + '</option>';
            }
            jQuery("#song_id").html(str);

        },
        error: function (a) {
            if(retry < 10){
                get_album_songs(album_id);
                retry++;
            }
            jQuery("#song_id").html('<option value="" selected disabled class="placeholder">No Internet</option>');
        },

        beforeSend: function(xhr){
            xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            jQuery("#song_id").html('<option value="" selected disabled class="placeholder">Loading...</option>');
            hide_song_details();
        }

    });
}

function get_albums(artist_id){
    show_artist_name(artist_id);
    var $ = jQuery;
    $.ajax({
        url: wpApiSettings.root+'music-charts/v1/artist/get_albums',
        type: 'get',
        data : {
            artist_id: artist_id
        },
        success: function(albums){
            myalbums = albums
            //console.log(albums);
            if(albums.length == 0){ jQuery("#album_id").html('<option value="" selected disabled class="placeholder">No Albums Available</option>');return;}
            var str = '<option value="" selected disabled class="placeholder">Choose an album</option>';
            for (var i in albums){
                var album = albums[i];
                str += '<option value="'+album.id+'">'+album.name+'</option>';
            }
            jQuery("#album_id").html(str);
        },
        error: function(a){
            //console.log(a);
            if(retry < 10){
                get_albums(artist_id);
                retry++;
            }
            jQuery("#album_id").html('<option value="" selected disabled class="placeholder">No Internet</option>');
        }
        ,
        beforeSend: function(xhr){
            xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            jQuery("#album_id").html('<option value="" selected disabled class="placeholder">Loading...</option>');
            hide_song_details();
        }
    });


}
function save_ranking(form){
    var table= $('#select_all').prop('checked',false).closest('table');
    $('td input:checkbox',table).prop('checked',false);
    jQuery.ajax({
        url: wpApiSettings.root+'music-charts/v1/music_chart/set_rankings',
        data: jQuery(form).serializeArray(),
        type: 'post',
        success: function(data){
            console.log(data);
        },
        error: function(a){
            console.log(a);
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            jQuery('.btn_set_ranking')
                .html('Saving changes')
                .attr({disabled: 'disabled'})
                .removeClass('btn-primary');
        },
        complete: function(){
            jQuery('.btn_set_ranking')
                .html('Save changes')
                .prop("disabled", false).val('')
                .addClass('btn-primary');
        }
    });
    return false;
}