<?php

class PmcSkedjoolHelper{
    /**
     * File holding helper for display fo objetcs i the templates for the front
     */
    public static function display_schedule_as_table( $schedule, $args = array( ) ){

    ?>
        <?php foreach($schedule->games as $index => $game ) : ?>
            <?php if( $game->is_current_game() ) : ?>
                <div class="schedule_next_game">
                    Next Game:<strong> <?php echo $game->date; ?> <?php echo $game->home_team; ?> @ <?php echo $game->away_team; ?></strong>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <table id="schedule_table" class="schedule_table" title="Schedule">
            <thead class="schedule_table_head">
                <tr class="schedule_table_head_row">
                    <th scope="col" class="schedule_table_head_cell">Date</th>
                    <th scope="col" class="schedule_table_head_cell">Home</th>
                    <th scope="col" class="schedule_table_head_cell">Away</th>
                    <th scope="col" class="schedule_table_head_cell schedule_result">Result</th>
                </tr>
            </thead>
            <tbody class="schedule_table_body">
                <?php foreach($schedule->games as $index => $game ) : ?>
                <tr class="<?php get_row_class( $game, $index ); ?>" >
                    <td class="schedule_table_body_cell">
                        
                        <?php echo $game->date ?>
                        <?php if( $game->is_current_game() && false ) : ?>
                                <br/>
                                <small>Next Game</small>
                        <?php endif; ?>
                    </td>
                    <td class="schedule_table_body_cell">
                        <?php self::the_home_team_link( $game );?>
                    </td>
                    <td class="schedule_table_body_cell">
                        <?php echo $game->away_team; ?>
                    </td>
                    <td class="schedule_table_body_cell schedule_result">
                        <?php self::the_single_result_link( $game ); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php

    }

    public static function display_schedule_as_divs( $schedule, $args = array( ) ){
        ?>
        <div id="schedule_divs">
            WEEK <?php self::get_week_anchor_links( count( $schedule->games ) ); ?>
        <?php foreach( $schedule->games as $index => $game ) : ?>
            <?php if( $game->is_current_game() ) : ?>
                <div class="schedule_game_div next_game">
                    <h3 class="week_date">NEXT GAME</h3>
                    <img alt="team logo" align="left" src="<?php self::get_opponent_icon( $game ); ?>" style="width:100px; height: 100px; "/>
                    <div class="schedule_game_info">
                        <div class="schedule_game_opponent"><?php self::get_vlrfc_opponent( $game ); ?></div>
                        <div class="schedule_game_date"><?php self::get_game_date( $game );?></div>
                        <div class="schedule_game_venue"><?php self::get_venue_link( $game->location ) ; ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
            <?php $record = array( 'wins' => 0, 'losses' => 0, 'draws' => 0); ?>
            <?php foreach( $schedule->games as $index => $game ) : ?>
                <div class="schedule_game_div <?php echo $class = $game->is_current_game() ? 'next_game' : '' ; ?>">
                    <h3 class="week_date">WEEK <?php echo $index + 1; self::get_week_anchor( $index ); ?></h3>
                    <img alt="team logo" align="left" src="<?php self::get_opponent_icon( $game ); ?>" style="width:100px; height: 100px; "/>
                    <div class="schedule_game_info">
                        <div class="schedule_game_opponent"><?php self::get_vlrfc_opponent( $game ); ?></div>
                        <div class="schedule_game_date"><?php self::get_game_date( $game );?></div>
                        <div class="schedule_game_venue"><?php self::get_venue_link( $game->location ) ; ?></div>
                        
                        <div class="clear"></div>
                    </div>
                    <div class="schedule_game_info">
                        <div class="schedule_game_result_record">
                             <?php $record = self::get_win_loss_or_draw( $game, $record ); ?>
                        </div>
                        <div class="schedule_game_result">
                            <?php self::get_record( $game, $record );?>
                        </div>
                        <div>
                            <?php self::get_match_report_link( $game, $record ); ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                     <div class="clear"></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    public static function get_match_report_link( $game, $record ){
       if( self::is_game_played( $game->away_team_score, $game->home_team_score, $game->date ) && !empty( $game->related_post ) ){
            echo '<a href="' . $game->related_post . '" title="Match Report &raquo;" >Match Report &raquo;</a>';
       }
    }
    
    public static function get_record( $game, $record ){
        if( self::is_game_played( $game->away_team_score, $game->home_team_score, $game->date ) ){
            $s = $game->type != 'Friendly' ? $record['wins'] . ' - ' . $record['losses'] . ' - ' . $record['draws'] : 'Friendly';
        }
        $is_b_game_only = $game->type == 'Friendly' &&  ( stristr( $game->home_team, 'vlrfc b' ) || stristr( $game->home_team, 'vlrfc b' ) );
        $s .= $is_b_game_only ? ' (B Side Only)' : '';
        echo $s;
    }
    
    public static function get_venue_link( $url ){
        if( !empty( $url ) ){
        ?>
        <a href="<?php echo $url; ?>">Venue Directions &raquo;</a>
        <?php
        }
    }
    
    public static function get_week_anchor_links( $count ){
        for( $i = 1; $i <= $count; $i++ ) :
        ?>
        <a href="#week_<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php
        endfor;
    }
    
    public static function get_week_anchor( $i ){
        $i++;
        ?>
        <a name="week_<?php echo $i; ?>"></a>
        <?php
    }
    
    public static function get_opponent_icon( $game ){
        if( !empty( $game->image_src ) ){
            echo $game->image_src;
        }
        else{
            echo 'http://www.villagelions.org/wp-content/uploads/2010/09/lion_avatar-150x150.png';
        }
    }
    
    public static function is_game_played( $score_1, $score_2, $game_date ){
        return ( $score_1 != ''  && $score_2 != '' );
    }
    
    public static function get_win_loss_or_draw( $game, $record ){
        if( self::is_game_played( $game->away_team_score, $game->home_team_score, $game->date ) ){
            $is_home = stristr( $game->home_team, 'vlrfc' );
            $is_friendly = $game->type == 'Friendly';
            if( $is_home ){
                if( $game->home_team_score > $game->away_team_score ){
                    echo '<span class="win">Win ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                    if( !$is_friendly ) {
                        $record['wins']++;
                    }
                }
                else if( $game->home_team_score < $game->away_team_score ){
                    echo '<span class="loss">Loss ' . $game->away_team_score . ' - ' . $game->home_team_score  . '</span>';
                   if( !$is_friendly ) {
                        $record['losses']++;
                    }
                }
                else{
                    echo '<span class="draw">Draw ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                    if( !$is_friendly ) {
                        $record['draws']++;
                    }
                }
            }
            else{
                if( $game->home_team_score > $game->away_team_score ){
                    echo '<span class="loss">Loss ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                    if( !$is_friendly ) {
                        $record['losses']++;
                    }
                }
                else if( $game->home_team_score < $game->away_team_score ){
                    echo '<span class="win">Win ' . $game->away_team_score . ' - ' . $game->home_team_score  . '</span>';
                     if( !$is_friendly ) {
                        $record['wins']++;
                    }
                }
                else{
                    echo '<span class="draw">Draw ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                    if( !$is_friendly ) {
                        $record['draws']++;
                    }
                }
            }
        }
      
        return $record;
    }
    
    public static function get_game_date( $game,  $format = 'l, m/d ' ){
        if( !empty($game->time) ){ // for backward compatability
            $game_date_time =  $game->date . ' ' . $game->time;
            $format .= 'h:i A';
        }
        else{
            $game_date_time =  $game->date
        }
        echo date( $format, strtotime( $game_date_time ) ) ;
    }
    
    public static function get_vlrfc_opponent( $game ){
        $is_home = stristr( $game->home_team, 'vlrfc' );
        if( $is_home ){
            echo $game->away_team;
        }
        else{
            echo '@ ' . $game->home_team;
        }
    }

    public static function get_row_class( $game, $index ){
        if( $game->isPast() ){
            cycle( $index , array( 'schedule_table_body_row_past', 'schedule_table_body_row_past_alt' ) );
        }
        else if( $game->isCurrentGame() ){
            echo "schedule_table_body_row_current";
        }
        else{
            cycle( $index );
        }
    }
    
    public static function display_schedule_as_list( $schedule, $args = array( ) ){
    ?>
    <ul id="schedule_list" class="schedule_list" title="Schedule">
        <?php foreach($schedule->games as $index => $game ) : ?>
        <li class="<?php self::cycle( $index, array( 'game_li', 'game_li_alt' ) ); ?>">
            <div class="schedule_list_left">
                <span class="schedule_list_date"><?php echo $game->date ?></span> <span class="schedule_list_away_team"><?php echo $game->away_team; ?></span> @ <span class="schedule_list_home_team"><?php self::the_home_team_link( $game );?></span>
                <div style="float: none; clear:both"></div>
            </div>
            <div class="schedule_list_right">
                <span class="schedule_list_match_report_link"><?php self::the_match_report_link($game); ?></span>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
    }
    
    public static function cycle( $index, $classes = array( 'schedule_table_body_row', 'schedule_table_body_row_alt' ) ){
        $modulous = count( $classes );
        $class = '';
        if( $index < $modulous ){
            $class = $classes[$index];
        }
        else{
            $class = $classes[$index % $modulous];
        }
        echo $class;
    }
    
    public static function the_home_team_link( $game, $echo = true ){
    ?>
    <?php if( empty( $game->location ) ) : ?>
                <?php echo $game->home_team; ?>
    <?php else : ?>
                <a href="<?php echo $game->location; ?>" title="Directions"  class="schedule_table_body_cell_link"><?php echo $game->home_team; ?></a>
    <?php endif;
    }

    public static function the_single_result_link( $game, $echo = true ){
    ?>
    <?php if( empty( $game->home_team_score ) || empty( $game->away_team_score ) ) : ?>
                <span class="schedule_table_body_cell_span">-</span>
    <?php elseif( !empty( $game->related_post ) ) : ?>
                <a href="<?php echo $game->related_post; ?>" title="Match Report" class="schedule_table_body_cell_link"><strong><?php echo $game->home_team_score; ?> - <?php echo $game->away_team_score; ?></strong></a>
    <?php else: ?>
                <span class="schedule_table_body_cell_span"><?php echo $game->home_team_score; ?> - <?php echo $game->away_team_score; ?></span>
    <?php endif;
    }

    public static function the_match_report_link( $game ){
    ?>
    <?php if( !empty( $game->home_team_score ) && !empty( $game->away_team_score ) ) : ?>
        <?php if( !empty( $game->related_post ) ) : ?>
                <a href="<?php echo $game->related_post; ?>" title="Match Report" class="schedule_table_body_cell_link"><strong><?php echo $game->away_team_score; ?> - <?php echo $game->home_team_score; ?></strong></a>
        <?php else: ?>
                <?php echo $game->away_team_score; ?> - <?php echo $game->home_team_score; ?>
        <?php endif; ?>
    <?php else: ?>
                &nbsp;
    <?php endif;
    }
}