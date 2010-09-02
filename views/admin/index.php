<div class="kform-wrap">
    <input type='button' value='<?php _e( '+ Add a game' ); ?>' class='button-secondary' style="margin-bottom: 5px;" id="add_game"/>

    <table class="widefat fixed" id="games_table">
        <thead>
            <tr>
                <th scope="col" class="manage-column" style="width:80px;">Date</th>
                <th scope="col" class="manage-column">Home</th>
                <th scope="col" class="manage-column">Away</th>
                <th scope="col" class="manage-column">Location ( Google Maps link )</th>
                <th scope="col" class="manage-column">Match Report</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Date</th>
                <th>Home</th>
                <th>Away</th>
                <th>Location ( Google Maps link )</th>
                <th>Match Report</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            if( !$schedule->isEmpty() ) :
                foreach( $schedule->getGames() as $game ) :
            ?>
                    <tr>
                        <td>
                            <input type="text" name="pmc_schedule_data_date[]" value="<?php echo htmlspecialchars( $game->date ); ?>" maxlength="8" size="8" class="date_picker" />
                            <div class="row-actions"><a href="#" class="remove">Remove Game</a></div>
                        </td>
                        <td>
                            <input type="text" name="pmc_schedule_data_home_team[]" value="<?php echo htmlspecialchars( $game->home_team ); ?>" maxlength="45" size="18" class="" />
                            <div class="row-actionss">
                                <label for="pmc_schedule_data_home_team_score" ><strong>Score </strong></label><input type="text" name="pmc_schedule_data_home_team_score[]" value="<?php echo htmlspecialchars( $game->home_team_score ); ?>" maxlength="3" size="3" class="" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="pmc_schedule_data_away_team[]" value="<?php echo htmlspecialchars( $game->away_team ); ?>" maxlength="45" size="18" class="" />
                            <div class="row-actionss">
                                <label for="pmc_schedule_data_away_team_score" ><strong>Score </strong></label><input type="text" name="pmc_schedule_data_away_team_score[]" value="<?php echo htmlspecialchars( $game->away_team_score ); ?>" maxlength="3" size="3" class="" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="pmc_schedule_data_location[]" value="<?php echo $game->location; ?>" maxlength="45" size="18" class="" />
                        </td>
                        <td>
                            <?php get_posts_drop_down( $game->related_post ); ?>
                        </td>
                    </tr>
            <?php endforeach; else : ?>
                    <tr>
                        <td>
                            <input type="text" name="pmc_schedule_data_date[]" value="" maxlength="8" size="8" class="date_picker" />
                            <div class="row-actions"><a href="#" class="remove">Remove Game</a></div>
                        </td>
                        <td>
                            <input type="text" name="pmc_schedule_data_home_team[]" value="" maxlength="45" size="18" class="" />
                            <div class="row-actionss">
                                <label for="pmc_schedule_data_home_team_score" ><strong>Score </strong></label><input type="text" name="pmc_schedule_data_home_team_score[]" value="" maxlength="3" size="3" class="" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="pmc_schedule_data_away_team[]" value="" maxlength="45" size="18" class="" />
                            <div class="row-actionss">
                                <label for="pmc_schedule_data_away_team_score" ><strong>Score </strong></label><input type="text" name="pmc_schedule_data_away_team_score[]" value="" maxlength="3" size="3" class="" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="pmc_schedule_data_location[]" value="" maxlength="45" size="18" class="" />
                        </td>
                        <td>
                            <?php get_posts_drop_down(); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
      </table>
            <script type="text/javascript" >
                (function($){
                    $(document).ready(function(){
                        $('a.remove').click(remove_parent);
                        $('#add_game').click(function(e){
                            var last_game_row = $('#games_table tbody tr:last').clone(true);
                            last_game_row.find('input').val('');
                            last_game_row.find('a.remove').click(remove_parent);
                            last_game_row.css('backgroundColor', '#FFFF66');
                            last_game_row.appendTo('#games_table tbody');
                            last_game_row.animate({ backgroundColor: "white" }, 350);
                            last_game_row.find('td:first input:text.date_picker').attr('id', '').datepicker('destroy'); // remove date picker and id, because in the datepicker data store that id is mapped to an instance
                            last_game_row.find('td:first input:text.date_picker').datepicker({
                                numberOfMonths: 3,
                                showButtonPanel: true
                            });
                        });
                        add_date_picker();
                        function add_date_picker(e){
                            $('input:text.date_picker').datepicker({
                                numberOfMonths: 3,
                                showButtonPanel: true
                            });
                        }

                        function remove_parent(e){
                            if($('#games_table tbody tr').length > 1){
                                var p = $(this).parents('tr');
                                p.fadeOut(350, function(e){$(this).remove()});
                            }
                            else{
                                alert('You need at least one game on the schedule.');
                            }
                            return false;
                        }
                    });
                })(jQuery);
            </script>
             <?php wp_nonce_field( PMC_SKEJOOL_PLUGIN_BASENAME, $this->key . '_wpnonce', false, true ); ?>
</div>