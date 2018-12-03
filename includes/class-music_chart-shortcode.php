<?php

class Music_chart_Shortcode {

    /**
     * @param $chart_id
     * @param $limit
     * @return string
     */
    public static function music_chart2($chart_id, $limit){
        @$week = $_GET['week'];
        $str = '';

        $music_chart = Music_chart_DB::get_chart($chart_id);

        $songs = Music_chart_DB::all_chart_songs($chart_id,'active',$limit,'', $week);// var_dump($_POST);
        $cuser = wp_get_current_user();
        $cuser_id = $cuser->get('ID');
        $cuser_id32 =  ($cuser_id)? strtoupper(base_convert($cuser_id, 10, 32 )).':': '';
        if($songs){
            $str .= '
<script src=\'https://assets.fortumo.com/fmp/fortumopay.js\' type=\'text/javascript\'></script>
<div class="row">
    <div class="col-md-offset-2">

<table id="music-chart2">';

        $str .= '<caption>';
            $str .= '
                <table border="0" style="width:100%;border: none;" cellpadding="5px">
                    <tr>
                        <td style="border: none; ">
                        <a class="" '.(($week < $music_chart->count-1)? '': 'disabled').' href="?week='.($week+1).'">&laquo;'.($week == 0? 'Last': ' Previous').' Week</a></td>
                        <td align="center" valign="middle" style="border: none;">
                            <div class="date"> Week of<br>';
                        if(isset($songs[0]) ) {
                            $week_date = new DateTime($songs[0]->week);
                            $str .= $week_date->format('F j, Y');

                        }
                        $str .= '</div>
                            </td>
                            <td align="right" style="border: none;'.(($week == 0)? 'visibility:hidden': '' ).'">
                            <a class="" href="?week='.($week-1).'">Next Week&raquo;</a>

                        </td>

                    </tr>
                </table>';
        $str .='</caption>
            <tbody class="chart-body">';
            foreach($songs as $pos => $song){// print $song->votes;
                $str .= '<tr>
                            <td width="40px" style="position: relative">
                                <div class="stats-main" style="position: absolute; width: 230px; left: -212px; color: white !important;display:none; text-align: center">
                                    <div class="visible-md visible-lg callout left">
                                        <table cellpadding="0" style="margin: 0px">
                                        <tr>
                                            <td style="border-top: none !important;border-left: none !important;"><span style="font-size: 10px">
                                            Last week: <br><span class="callout-number">'.($song->previous_ranking? '#'.$song->previous_ranking:'-' ).'</span></span></td>
                                            <td style="border-top: none !important;border-right: none !important;">
                                            <span style="font-size: 10px">Weeks in Chart: <br><span class="callout-number">'.$song->featured_count.'</span></span></td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: none !important;border-left: none !important;">
                                                <span style="font-size: 10px">Peak: <br> <span class="callout-number">'.(($pos == 0)? '#1': (($song->peak_ranking and $song->peak_ranking != 999)? '#'.$song->peak_ranking: '-')).'</span></span></td>
                                            <td style="border-bottom: none !important;border-right: none !important;">
                                                <span style="font-size: 10px">Weeks in #1: <br> <span class="callout-number">'.(($pos == 0)? $song->toping_count+1:$song->toping_count).'</span></span></td>
                                        </tr>
                                        </table>
                                    </div>
                                </div>
                                <span class="movement"><span class="fa '.self::direction($song->previous_ranking, $pos+1).'"></span></span>
                                <span class="prev_rank">Lw: '.$song->previous_ranking.'</span>
                                <span>'.($song->votes? 'Vote: +'. $song->votes:'').'</span>
                        </td>';

                $str .= '<td width="100px" align="center">
                            <span class="position">'.($pos+1).'</span>
                        </td>';

                $str .= '<td>
                        <div class="row details">
                            <div class="col-xs-3" style="min-width: 100px">
                                 <div class="music-chart-album-cover" style="margin: 0px auto; height:90px; width:90px !important; background-image: url('.($song->album_cover? :'http://dalelyles.com/musicmp3s/no_cover.jpg').')"></div>
                            </div>
                            <div class="col-xs-4" style="min-width: 200px;">
                                <div class="artist">'.$song->artist_name.'</div>
                                <div class="title">'.$song->name.'</div>
                            </div>
                            <div class="col-xs-4 icons" style="min-width: 200px;">
                                    <a href="'.$song->url.'" class="fa fa-play-circle play-song" aria-hidden="true">
                                        <audio autoplay></audio>
                                    </a>
                                    <a href="'.($song->youtube_url|'#').'" title="Watch video on YouTube" class="fa fa-youtube-play" aria-hidden="true"></a>
                                    <a href="'.$song->url.'" download title="Download Song"><i class="fa fa-download" aria-hidden="true"></i></a>
                                    <a href="" class="vote">Vote</a>
                                <div style="display:none" class="voting" data-number="55508" data-text="TXT MVOTE '. $cuser_id32.
                                strtoupper(base_convert( $song->music_chart_id, 10, 32 )).'">
                                    Vote for this Song, <br>
                                    Method 1:<br>
                                    TEXT TXT MVOTE '.$cuser_id32.
                                    strtoupper(base_convert( $song->music_chart_id, 10, 32 )).' to 55508
                                    <a href="#" class="fa fa-envelope send-vote"></a>
                                    <hr>'.(($cuser_id)?
                                    'Method 2:<br>
                                    Click <a href="#" data-song="'.$song->music_chart_id.'" class="votes_by_point" style="font-size: 11px; text-decoration: underline">here to vote</a> for 30pts<br>Your Balance: '.
                                    mycred_get_users_balance(get_current_user_id()).'pts'  : '' ).
                                    '<hr>
                                    Method 3:<br>
                                    <a id="fmp-button" href="#" rel="52dab933003a30a8d3bab2524f1fd090/'.$cuser_id32.
                    strtoupper(base_convert( $song->music_chart_id, 10, 32 )).'" style="text-decoration:underline; font-size: 11px">VOTE BY MOBILE</a> for 30 NGN

                                </div>
                            </div>
                        </div>
                        </td>';

                $str .= '</tr>';
            }
            $str .= '</tbody></table>
</div>
</div>';
        }else{
            $str = '<div class="alert alert-warning">There are no songs in this Chart</div>';
        }

        return $str;
    }
	public static function music_chart($chart_id, $limit){
        @$week = $_GET['week'];
        $str = '';

        $music_chart = Music_chart_DB::get_chart($chart_id);

        $songs = Music_chart_DB::all_chart_songs($chart_id,'active',$limit,'', $week);// var_dump($_POST);

        $str .= ' <table width="100%" border="0" style="border: none;">
        <tr>
            <td style="border: none; "><a class="btn btn-info" '.(($week > $music_chart->count-1)? '': 'disabled').' href="?week='.($week+1).'">&laquo; Previous</a></td>
            <td align="center" style="border: none;">
                <span class="label label-success">';
                if(isset($songs[0]) ) {
                    $week_date = new DateTime($songs[0]->week);
                    $str .= $week_date->format('Y-m-d');
                }
        $str .= '</span>
                </td>
                <td align="right" style="border: none;">
                <a class="btn btn-info" '.(($week == 0)? 'disabled': '' ).'  href="?week='.($week-1).'">Next &raquo;</a>

            </td>

        </tr>
    </table>';
        if($songs){
            $str .= '<div class="music-chart">';
            foreach($songs as $pos =>$song){

                $str .= '
<script src=\'https://assets.fortumo.com/fmp/fortumopay.js\' type=\'text/javascript\'></script>
<div class="row">';
                    $str .= '<div class="music-chart-item col-xs-3" style="padding: 0px; width:182px ">';
                    $str .=     '


                    <div class="music-chart-album-cover" style="height:182px; width:182px !important; background-image: url('.($song->album_cover? :'http://dalelyles.com/musicmp3s/no_cover.jpg').')">
                                   <!-- <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" width=100%>-->
                                </div>';
                    $str .=     '<div  class="music-chart-media">
                                    <a href="'.$song->url.'" class="fa fa-play-circle play-song" aria-hidden="true">
                                        <audio autoplay></audio>
                                    </a>
                                    <a href="'.($song->youtube_url|'#').'" title="Watch video on YouTube" class="fa fa-youtube-play" aria-hidden="true"></a>
                                    <a href="'.$song->amazon_url.'" title="Check out on Amazon" class="fa fa-amazon" aria-hidden="true"></a>
                                    <a href="'.$song->itunes_url.'" title="Check out on IStore"  class="fa fa-apple" aria-hidden="true"></a>
                                    <a href="'.$song->url.'" download title="Download Song"><i class="fa fa-download" aria-hidden="true"></i></a>
                                    <!--<div class="slider"></div>-->
                                </div>
                                

                                ';
                    $str .= '</div>
                            <div class="col-xs-1 music-chart-info " style="padding:0px; width: 95px" >
                                <div style="text-align: center; font-size: 45px; height: 70px;background-color: black; color: white;margin: 0 0 2px 2px">
                                    <!--<a href="#" title="New in Chart" class="music-chart-new-song '.(($song->featured_count == 1)? 'fa - fa-star': '').'" ></a>-->
                                    <span style="" class="fa '.self::direction($song->previous_ranking, $pos+1).'"></span>
                                </div>
                                <div style="line-height: 12px;height: 110px;background-color: black; color: white;margin: 2px 0 0 2px; padding:20px 5px 0px">
                                    <div style="text-align: center;font-size: 40px;margin-bottom:20px">'.($pos+1).'</div>
                                    <span style="font-size: 9px">Last week: '.($song->previous_ranking? '#'.$song->previous_ranking:'-' ).'</span><br>
                                    <span style="font-size: 9px">Weeks in Chart: '.$song->featured_count.'</span><br>
                                    <span style="font-size: 9px">Peak: '.(($pos == 0)? '#1': (($song->peak_ranking and $song->peak_ranking != 999)? '#'.$song->peak_ranking: '-')).'</span><br>
                                    <span style="font-size: 9px">Weeks in #1: '.(($pos == 0)? $song->toping_count+1:$song->toping_count).'</span>
                                </div>

                            </div>';
                    $str .= '<div class="music-chart-item col-xs-6" style="min-width: 250px">
                                <h5> <span class="fa fa-music" aria-hidden="true"></span> '.$song->name.'</h5>
                                <h6> <span class="fa fa-file-audio-o" aria-hidden="true"></span> '.$song->album_name.'</h5>
                                <h6> <span class="fa fa-user" aria-hidden="true"></span> '.$song->artist_name.'</h5>
                                <div> <i class="fa fa-external-link" aria-hidden="true"></i> <a href="'.$song->album_url.'" target="_blank" >'.$song->album_url.'</a></div>
                                <div style="width:100%">
                                    Votes: <span class="votes" data-song="'.$song->music_chart_id.'">
                                                <i class="vote">'.$song->votes.'</i>
                                                '.($week == 0? '<a href="" class="fa fa-thumbs-up '.($song->voted? 'voted':'').'"></a>': '').'
                                           </span>
                                    Rating: '.($song->rating? : 0).'
                               <!-- </div>

                                <div>

                                    <table style="width:auto">
                                        <tr>
                                            <td width="100px">
                                                <div class="rating-chart">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td class="rating-chart-info">
                                                <div>65</div>
                                                <div>50</div>
                                                <div>20</div>
                                                <div>5</div>
                                                <div>25</div>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                <div style="width:100%">
-->
                                    '.self::get_rating_html($song->rating).'
                                </div> '.self::get_rating_form($song->id, $song->user_rating).'
                            </div>';
                $str .= '</div>';
            }
            $str .= '</div>';
        }else{
            $str = '<div class="alert alert-warning">There are no songs in this Chart</div>';
        }

        return $str;
	}
    private static function get_rating_html($rating = 0){ //$rating = 2.7;
        $str = '';
        $str .= '<div class="rating" style="display:inline-block; height:20px">';

                    for($i=5; $i>=1;$i-- ){
                        $l = $i-0.5;
                        $str .= ($rating >= $i-0.1)? '<span class = "full selected"></span>': '<span class = "full"></span>';
                        $str .= ($rating < $i and $rating >= $l)? '<span class = "half selected"></span>': '<span class = "half"></span>';

                    }

        $str .= '</div>';
        return $str;
    }

    private static function get_rating_form($song_id, $user_rating){
        $user_id = get_current_user_id();
        if($user_id){
            $ratings = array(
                '5'       => 'Excellent',
                '4.5'     => 'Great',
                '4'       => 'Very Good',
                '3.5'     => 'Cool',
                '3'       => 'Good',
                '2.5'     => 'Nice',
                '2'       => 'Fair',
                '1.5'     => 'Not Good',
                '1'       => 'Poor',
                '0.5'     => 'Very Poor',

            );
            $str = '<i>Your Rating</i>
                    <fieldset class="rating" title="Your Rating" data-song="'.$song_id.'">';
            foreach($ratings as $rating => $title){
                    $checked = ($rating == $user_rating)? 'checked' : '';
                    $class = (($rating*2)%2 == 0)? 'full' : 'half';
                    $id = str_replace('.','_',$rating).'_'.$song_id;
                    $str .= '<input name="rating['.$song_id.']" type="radio" '.$checked.' id="'.$id .'" value="'.$rating.'" ><label class = "'.$class.'" for="'.$id .'" title="'.$title.'"></label>';
            }
            $str .= '</fieldset>';
            return $str;
        }

    }

    public static function direction($prev, $cur){
        if($prev == ''){
            $direction = 'fa-star';
        }
        elseif($prev < $cur){
            $direction = 'fa-arrow-down';
        }elseif($prev >  $cur){
            $direction = 'fa-arrow-up';
        }else{
            $direction = 'fa-minus';
        }
        return $direction;
    }
    /*public static function decTo32(){

    }*/
}
