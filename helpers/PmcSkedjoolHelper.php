<?php

/**
 * File holding helper for display fo objetcs i the templates for the front
 */
function display_schedule_as_table( $schedule, $args = array( ) ){

?>
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
            <tr class="<?php cycle( $index ); ?>">
                <td class="schedule_table_body_cell">
                    <?php echo $game->date ?>
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