(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	var song_playing = false;
    $(function(){
        $('.chart-body tr').mouseover (function(){
            $('.stats-main').hide();
            $(this).find('.stats-main').show();
        }).mouseout(function(){
            $(this).find('.stats-main').hide();
        })

        $('.play-song').click(function(e){
            //alert(this)
            e.preventDefault();

            var btn = $(this)
            var player = $(this).find('audio');
            var song_url = btn.attr('href');
            if(!player.attr('src')){
                $('.play-song audio').attr({src:''});//stop all music
                $('.play-song').removeClass('fa-stop-circle-o').addClass('fa-play-circle');

                btn.removeClass('fa-play-circle').addClass('fa-pause')
                player.attr({src:song_url})
                song_playing = song_url;
            }else{
                var playerObj = player.get();
                if(song_playing == song_url){
                    btn.removeClass('fa-stop-circle-o').addClass('fa-play-circle');
                    player[0].pause();
                    song_playing = false;
                }else {
                    btn.removeClass('fa-play-circle').addClass('fa-pause')
                    player[0].play();
                    song_playing = song_url;
                }


            }
            player.one('ended', function() {
                //alert('yes');
                song_playing = false;
                // enable button/link
                btn.removeClass('fa-stop-circle-o').addClass('fa-play-circle');
                //$(this).attr({src:''})
            });

        })
		
        

		/*$(".slider").slider({
			value : 75,
			step  : 1,
			range : 'min',
			min   : 0,
			max   : 100,
			slide : function(){
				var value = $(this).slider("value");
				 $('.slider').closest('audio')[0].volume = (value / 100);
			}
		});*/
        //alert(ApiSettings.root+ 'music-charts/v1/test_sms_api');
        $('.rating input').on('change',function(e){
            e.preventDefault();
            var star = $(this);
            var rating = $(this).val();
            var song_id = $(this).parent().attr('data-song');
            var url = wpApiSettings.root+ 'music-charts/v1/song/rate';// $(this).parent().attr('data-url');

            $.ajax({
                url: url,
                type: 'post',
                data: {
                    song_id: song_id,
                    rating: rating,

                },
                success: function (response) {
                    console.log(response);
                    response? star.prop({'checked': true}): star.prop({'checked': false});
                },
                error: function (a) {
                    star.prop({'checked': false});
                },
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                }

            });

        })
        $('.votes a').on('click',function(e){
            e.preventDefault();
            var btn = $(this);
            if( btn.hasClass('voted')) return;

            var vote = btn.parent().find('.vote');
            var cur_vote = parseInt(vote.html());

            var music_chart_song_id = $(this).parent().attr('data-song');
            var url = wpApiSettings.root+ 'music-charts/v1/music_chart/vote_song';// $(this).parent().attr('data-url');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    song: music_chart_song_id,
                },
                success: function (response) {
                    console.log(response);
                    vote.html(cur_vote+1);
                    btn.addClass('voted')
                },
                error: function (a) {

                },
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                }

            });

        })
        $('a.votes_by_point').on('click',function(e){
            //alert('sdkdskjskjd');
            e.preventDefault();
            var btn = $(this);

            var music_chart_song_id = $(this).attr('data-song');
            var url = wpApiSettings.root+ 'music-charts/v1/music_chart/vote_song_by_point';// $(this).parent().attr('data-url');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    song: music_chart_song_id,
                },
                success: function (response) {
                    console.log(response);
                    alert(response);
                    window.location.reload();
                    //btn.addClass('voted')
                },
                error: function (a) {
                    console.log(a.content);
                },
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                }

            });

        })
        $('.vote').click(function(e){
            e.preventDefault();
            var voting = $(this).parent().find('.voting');
            if(voting.css('display') == 'none'){// close opened one before another
                $('.voting').hide(300);
            }
            voting.toggle(300);
        })
        $(document).click(function(e){
            if(!$(e.target).hasClass('voting') && !$(e.target).hasClass('vote'))
                $('.voting').hide(300)
        })
        var ua = navigator.userAgent.toLowerCase();
        $('.send-vote').click(function(e){
            var url;
            var telephone = $(this).parent().attr('data-number');
            var message = $(this).parent().attr('data-text');// alert(ua);
            if (ua.indexOf("iphone") > -1 || ua.indexOf("ipad") > -1)
                url = "sms:"+telephone+";body=" + encodeURIComponent(message);
            else
                url = "sms:"+telephone+"?body=" + encodeURIComponent(message);

            location.href = url;
        });
    })



})( jQuery );
