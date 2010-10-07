<?php

/**
 * File holding helper for display fo objetcs i the templates for the front
 */
function display_schedule_as_table( $schedule, $args = array( ) ){

?>
    <?php foreach($schedule->games as $index => $game ) : ?>
        <?php if( $game->isCurrentGame() ) : ?>
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
                    <?php if( $game->isCurrentGame() && false ) : ?>
                            <br/>
                            <small>Next Game</small>
                    <?php endif; ?>
                </td>
                <td class="schedule_table_body_cell">
                    <?php the_home_team_link( $game );?>
                </td>
                <td class="schedule_table_body_cell">
                    <?php echo $game->away_team; ?>
                </td>
                <td class="schedule_table_body_cell schedule_result">
                    <?php the_single_result_link( $game ); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php

}

function display_schedule_as_divs( $schedule, $args = array( ) ){
    ?>
    <div id="schedule_divs">
        WEEK <?php get_week_anchor_links( count( $schedule->games ) ); ?>
    <?php foreach( $schedule->games as $index => $game ) : ?>
        <?php if( $game->isCurrentGame() ) : ?>
            <div class="schedule_game_div next_game">
                <h3 class="week_date">NEXT GAME</h3>
                <img alt="team logo" align="left" src="<?php get_opponent_icon( $game ); ?>" style="width:100px; height: 100px; "/>
                <div class="schedule_game_info">
                    <div class="schedule_game_opponent"><?php get_vlrfc_opponent( $game ); ?></div>
                    <div class="schedule_game_date"><?php get_game_date( $game->date);?></div>
                    <div class="schedule_game_venue"><?php get_venue_link( $game->location ) ; ?></div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
        <?php $record = array( 'wins' => 0, 'losses' => 0, 'draws' => 0); ?>
        <?php foreach( $schedule->games as $index => $game ) : ?>
            <div class="schedule_game_div <?php echo $class = $game->isCurrentGame() ? 'next_game' : '' ; ?>">
                <h3 class="week_date">WEEK <?php echo $index + 1; get_week_anchor( $index ); ?></h3>
                <img alt="team logo" align="left" src="<?php get_opponent_icon( $game ); ?>" style="width:100px; height: 100px; "/>
                <div class="schedule_game_info">
                    <div class="schedule_game_opponent"><?php get_vlrfc_opponent( $game ); ?></div>
                    <div class="schedule_game_date"><?php get_game_date( $game->date);?></div>
                    <div class="schedule_game_venue"><?php get_venue_link( $game->location ) ; ?></div>
                    
                    <div class="clear"></div>
                </div>
                <div class="schedule_game_info">
                    <div class="schedule_game_result_record">
                         <?php $record = get_win_loss_or_draw( $game, $record ); ?>
                    </div>
                    <div class="schedule_game_result">
                        <?php echo $record['wins'] . ' - ' . $record['losses'] . ' - ' . $record['draws']; ?>
                    </div>
                    <div class="clear"></div>
                </div>
                 <div class="clear"></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
function get_venue_link( $url ){
    ?>
    <a href="<?php echo $url; ?>">Venue Directions &raquo;</a>
    <?php
}
function get_week_anchor_links( $count ){
    for( $i = 1; $i <= $count; $i++ ) :
    ?>
    <a href="#week_<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php
    endfor;
}
function get_week_anchor( $i ){
    $i++;
    ?>
    <a name="week_<?php echo $i; ?>"></a>
    <?php
}
function get_opponent_icon( $game ){
    if( !empty( $game->image_src ) ){
        echo $game->image_src;
    }
    else{
        echo 'http://www.villagelions.org/wp-content/uploads/2010/09/lion_avatar-150x150.png';
    }
}
function get_win_loss_or_draw( $game, $record ){
    if( !empty( $game->away_team_score ) && !empty( $game->home_team_score ) ){
        $is_home = stristr( $game->home_team, 'vlrfc' );
        if( $is_home ){
            if( $game->home_team_score > $game->away_team_score ){
                echo '<span class="win">Win ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                $record['wins']++;
            }
            else if( $game->home_team_score < $game->away_team_score ){
                echo '<span class="win">Loss ' . $game->away_team_score . ' - ' . $game->home_team_score  . '</span>';
                $record['losses']++;
            }
            else{
                echo '<span class="draw">Draw ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                $record['draws']++;
            }
        }
        else{
            if( $game->home_team_score > $game->away_team_score ){
                echo '<span class="loss">Loss ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                $record['losses']++;
            }
            else if( $game->home_team_score < $game->away_team_score ){
                echo '<span class="win">Win ' . $game->away_team_score . ' - ' . $game->home_team_score  . '</span>';
                $record['wins']++;
            }
            else{
                echo '<span class="draw">Draw ' . $game->home_team_score . ' - ' . $game->away_team_score  . '</span>';
                $record['draws']++;
            }
        }
    }
    return $record;
}
function get_game_date( $date, $format = 'l, m/d' ){
   echo date( $format, strtotime( $date ) ) . ' at 1:00pm EDT ';
}
function get_vlrfc_opponent( $game ){
    $is_home = stristr( $game->home_team, 'vlrfc' );
    if( $is_home ){
        echo $game->away_team;
    }
    else{
        echo '@ ' . $game->home_team;
    }
}

function get_row_class( $game, $index ){
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
function display_schedule_as_list( $schedule, $args = array( ) ){
?>
<ul id="schedule_list" class="schedule_list" title="Schedule">
    <?php foreach($schedule->games as $index => $game ) : ?>
    <li class="<?php cycle( $index, array( 'game_li', 'game_li_alt' ) ); ?>">
        <div class="schedule_list_left">
            <span class="schedule_list_date"><?php echo $game->date ?></span> <span class="schedule_list_away_team"><?php echo $game->away_team; ?></span> @ <span class="schedule_list_home_team"><?php the_home_team_link( $game );?></span>
            <div style="float: none; clear:both"></div>
        </div>
        <div class="schedule_list_right">
            <span class="schedule_list_match_report_link"><?php the_match_report_link($game); ?></span>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php
}
function cycle( $index, $classes = array( 'schedule_table_body_row', 'schedule_table_body_row_alt' ) ){
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
function the_home_team_link( $game, $echo = true ){
?>
<?php if( empty( $game->location ) ) : ?>
            <?php echo $game->home_team; ?>
<?php else : ?>
            <a href="<?php echo $game->location; ?>" title="Directions"  class="schedule_table_body_cell_link"><?php echo $game->home_team; ?></a>
<?php endif;
}

function the_single_result_link( $game, $echo = true ){
?>
<?php if( empty( $game->home_team_score ) || empty( $game->away_team_score ) ) : ?>
            <span class="schedule_table_body_cell_span">-</span>
<?php elseif( !empty( $game->related_post ) ) : ?>
            <a href="<?php echo $game->related_post; ?>" title="Match Report" class="schedule_table_body_cell_link"><strong><?php echo $game->home_team_score; ?> - <?php echo $game->away_team_score; ?></strong></a>
<?php else: ?>
            <span class="schedule_table_body_cell_span"><?php echo $game->home_team_score; ?> - <?php echo $game->away_team_score; ?></span>
<?php endif;
}
function the_match_report_link( $game ){
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