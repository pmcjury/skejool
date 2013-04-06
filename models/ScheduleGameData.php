<?php

/*
 * Class for Schedule Game Data Encapsulation
 */

/**
 * Description of ScheduleGameData
 *
 * @author pmcjury
 */
class ScheduleGameData{

    public $data;
    public $fields;
    public $games;

    public function __construct( $data, $map_from_meta = true ){
        $this->data = $data;
        $this->fields = ScheduleGame::$game_input_fields;
        $this->games = array();
        if( $map_from_meta ){
            $this->map_data_from_post_meta();
        }
        else{
            $this->map_data_from_form_post();
        }
        if( $this->is_empty() ){
            $this->games[] = new ScheduleGame();
        }
    }

    public function is_empty(){
        return ( empty( $this->games ) || $this->get_games_count() <= 0 );
    }

    public function get_games_count(){
        return count( $this->games );
    }

    private function map_data_from_form_post(){
        for( $i = 0; $i < count( $this->data['pmc_schedule_data_date'] ); $i++ ){
            $g = new ScheduleGame();
            foreach( $this->fields as $input ){
                $field = $input;
                if( empty( $this->data['pmc_schedule_data_' . $input] ) ){
                    $g->$field = 'Enter a Value.';
                }
                else{
                    $g->$field = $this->data['pmc_schedule_data_' . $input][$i];
                }
            }
            $this->games[] = $g;
        }
    }

    private function map_data_from_post_meta(){
        $this->games = $this->data;
        $this->set_current_game();
    }

    private function set_current_game(){
        if( count( $this->games ) > 1 ){
            foreach( $this->games as $index => $game ){
                if ( !( strtotime( $game->date ) < time() ) ){
                    $game->is_current_game = true;
                    break;
                }
            }
        }
    }

}

/**
 * Game class to represetn game meta data per scheduled game
 */
class ScheduleGame{

    public $home_team;
    public $away_team;
    public $date;
    public $time;
    public $home_score;
    public $away_score;
    public $location;
    public $related_post;
    public $friends_and_family;
    public $is_current_game = false;
    public $image_src;
    public static $game_input_fields = array( 'date' => 'date', 'time' => 'time', 'home_team' => 'home_team', 'home_team_score' => 'home_team_score', 'away_team' => 'away_team', 'away_team_score' => 'away_team_score',
        'location' => 'location', 'related_post' => 'related_post', 'friends_and_family' => 'friends_and_family', 'image_src' => 'image_src', 'type'=>'type' );

    public function __construct( $args = array( ) ){
        // auto map
        foreach( $args as $key => $value ){
            $this->$key = $value;
        }
    }

    public function is_past(){
        return strtotime( $this->date ) < time();
    }

    public function is_current_game(){
        return $this->is_current_game;
    }

}