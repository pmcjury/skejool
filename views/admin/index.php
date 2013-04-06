<div >
    <input type='button' value='<?php _e( '+ Add a game' ); ?>' class='button-secondary' style="margin-bottom: 5px;" id="add_game"/>

    <table class="widefat fixed" id="games_table" style="overflow: scroll;">
        <thead>
            <tr>
                <th scope="col" class="manage-column" style="width:80px;">Date</th>
                <th scope="col" class="manage-column" >Home</th>
                <th scope="col" class="manage-column" >Away</th>
                <th scope="col" class="manage-column" >Location</th>
                <th scope="col" class="manage-column" >Match Report</th>
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
            foreach( $schedule->games as $game ) :
            ?>
                <tr>
                    <td>
                        <label for="pmc_schedule_data_date" ><strong>Date </strong></label>
                        <br/>
                        <input type="text" name="pmc_schedule_data_date[]" value="<?php echo htmlspecialchars( $game->date ); ?>" maxlength="10" size="7" class="date_picker" />
                        <div class="row-actionss">
                            <label for="pmc_schedule_data_time" ><strong>Time </strong></label>
                            <br/>
                            <input type="text" name="pmc_schedule_data_time[]" value="<?php echo htmlspecialchars( $game->time );  ?>" maxlength="5" size="6" class="time_picker" />
                        </div>
                        <div class="row-actions"><a href="#" class="remove">Remove Game</a></div>
                    </td>
                    <td>
                        <label for="pmc_schedule_data_home_team" ><strong>Name </strong></label>
                        <br/>
                        <input type="text" name="pmc_schedule_data_home_team[]" value="<?php echo htmlspecialchars( $game->home_team ); ?>" maxlength="45" size="18" class="" />
                        <div class="row-actionss">
                            <label for="pmc_schedule_data_home_team_score" ><strong>Score </strong></label>
                            <br/>
                            <input type="text" name="pmc_schedule_data_home_team_score[]" value="<?php echo htmlspecialchars( $game->home_team_score ); ?>" maxlength="3" size="3" class="" />
                        </div>
                        <div class="row-actionss">
                            <label for="pmc_schedule_data_image_src" ><strong>Image URL </strong></label>
                            <br/>
                            <input type="text" name="pmc_schedule_data_image_src[]" value="<?php echo $game->image_src; ?>" size="18" class="" />
                        </div>
                    </td>
                    <td>
                        <label for="pmc_schedule_data_away_team" ><strong>Name </strong></label>
                        <br/>
                        <input type="text" name="pmc_schedule_data_away_team[]" value="<?php echo htmlspecialchars( $game->away_team ); ?>" maxlength="45" size="18" class="" />
                        <div class="row-actionss">
                            <label for="pmc_schedule_data_away_team_score" ><strong>Score </strong></label>
                            <br/>
                            <input type="text" name="pmc_schedule_data_away_team_score[]" value="<?php echo htmlspecialchars( $game->away_team_score ); ?>" maxlength="3" size="3" class="" />
                        </div>
                    </td>
                    <td >
                        <label for="pmc_schedule_data_location" ><strong>Map or Direction URL </strong></label>
                            <br/>
                        <input type="text" name="pmc_schedule_data_location[]" value="<?php echo $game->location; ?>" maxlength="45" size="16" class="" />
                        <div class="row-actionss">
                            <label for="pmc_schedule_data_type" ><strong>Type </strong></label>
                            <br/>
                            <select name="pmc_schedule_data_type[]" >
                                <option value="League" <?php  selected( $game->type, 'League' );?>>League</option>
                                <option value="Friendly" <?php  selected( $game->type, 'Friendly' );?>>Friendly</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <label for="pmc_schedule_data_related_post" ><strong>Link URL </strong></label>
                         <br/>
                        <?php $helper->get_posts_drop_down( array( 'value' => $game->related_post ) ); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="dialog-message" title="Duh!" style="display:none;" >
      <p class="ui-state-error">
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 10px 0;"></span>
        <strong>Duh!</strong> You need at least one game on the schedule.
      </p>
    </div>
            <script type="text/javascript" >
                (function($){
                    $(document).ready(function(){

                        var pmcScheduleWidget = {
                        
                            _pmc_time_pickers : 0,
                            _dialogSelector : '#dialog-message',

                            add_date_time_picker : function ( e, altFieldSelector ){
                                var me = this;
                                $(e).datetimepicker({
                                    altField: "#" + me.generate_time_picker_id(),
                                    hour : 13,
                                    defaultValue : 13,
                                    numberOfMonths: 1,
                                    showButtonPanel: true
                                });
                                me._pmc_time_pickers++;
                            },

                            add_date_time_pickers : function(){
                                var me = this;
                                $('input:text.date_picker').each(function(i,e){
                                    var id = me.generate_time_picker_id();
                                    $(e).next().find('input:text.time_picker').attr('id', id);
                                    me.add_date_time_picker(e, id);
                                });
                            },

                            remove_parent : function(eventObj){
                                var me = this;
                                if($('#games_table tbody tr').length > 1){
                                    var p = $(this).parents('tr');
                                    p.fadeOut(250, function(e){$(this).remove()});
                                }
                                else{
                                   eventObj.data.widget.remove_dialog();
                                }
                                return false;
                            },

                            generate_time_picker_id : function(){
                                return '_pmc_time_pickers' + this._pmc_time_pickers;
                            },

                            remove_dialog : function( ){
                                var me = this;
                                $(me._dialogSelector).dialog({
                                  modal: true,
                                  resizable: false,
                                  buttons: {
                                    Close: function() {
                                      $( this ).dialog( "close" );
                                    }
                                  }
                                });
                            },

                            init : function(){
                                var me = this;
                                this.add_date_time_pickers();
                                $('a.remove').click({ widget: me}, me.remove_parent);
                                $('#add_game').click(function(e){
                                    var last_game_row_1 = $('#games_table tbody tr:last').clone(true),
                                    id = me.generate_time_picker_id(),
                                    time_picker_el = last_game_row_1.find('td:first input:text.time_picker'),
                                    date_picker_el = last_game_row_1.find('td:first input:text.date_picker');

                                    last_game_row_1.find('input').val('');
                                    last_game_row_1.css('backgroundColor', '#FFFF66');
                                    last_game_row_1.appendTo('#games_table tbody');
                                    last_game_row_1.animate({ backgroundColor: "white" }, 350);
                                    time_picker_el.attr('id', id);
                                    date_picker_el.attr('id', '').datetimepicker('destroy'); // remove date picker and id, because in the datepicker data store that id is mapped to an instance
                                    me.add_date_time_picker(date_picker_el, id)
                                });
                            }
                        };
                        pmcScheduleWidget.init();
                    });
                })(jQuery);
            </script>
             <?php wp_nonce_field( PMC_SKEJOOL_PLUGIN_BASENAME, $this->key . '_wpnonce', false, true ); ?>
</div>