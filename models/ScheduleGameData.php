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
        $this->games = array( );
        if( $map_from_meta ){
            $this->map_data_from_post_meta();
        }
        else{
            $this->map_data_from_form_post();
        }
    }

    public function isEmpty(){
        return ( empty( $this->games ) || $this->get_games_count() <= 0 );
    }

    public function get_games_count(){
        return count( $this->games );
    }

    public function __call( $name, $value ){
        $method = substr( $name, 0, 3 );
        $name = strtolower( substr( $name, 3 ) );
        if( $method == 'get' ){
            return $this->$name;
        }
        elseif( $method == 'set' ){
            $this->$name = $value;
        }
        else{
            throw new Exception( 'Not a valid method for this object.' );
        }
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
    }

}

/**
 * Game class to represetn game meta data per scheduled game
 */
class ScheduleGame{

    public $home_team;
    public $away_team;
    public $date;
    public $home_score;
    public $away_score;
    public $location;
    public $related_post;
    public $friends_and_family;
    public static $game_input_fields = array( 'date' => 'date', 'home_team' => 'home_team', 'home_team_score' => 'home_team_score', 'away_team' => 'away_team', 'away_team_score' => 'away_team_score',
        'location' => 'location', 'related_post' => 'related_post', 'friends_and_family' => 'friends_and_family' );

    public function __construct( $args = array( ) ){
        // auto map
        foreach( $args as $key => $value ){
            $this->$key = $value;
        }
    }

}