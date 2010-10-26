<?php

class PmcSkedjoolAdminHelper{

    private $terms;
    private $cats;
    private $posts_drop_down_html;

    public function __construct(){
        
    }

    function get_posts_drop_down( $arguments = array() ){
        global $wp_query;
        $default_args = array( 'value' => '', 'posts_per_page' => -1, 'do_not_show_stickies' => 1, 'show_nav' => false, 'display_type' => 'index', 'order' => 'DESC', 'echo' => true );
        extract ( array_merge( $default_args, $arguments) );
        if( !isset( $this->posts_drop_down_html ) ){
            $cats = $this->get_the_related_posts_cats();
            if( count( $cats ) > 0 ){
                $args = array(
                        'category__in' => $cats,
                        'orderby' => 'date',
                        'order' => $order,
                        'paged' => false,
                        'posts_per_page' => $posts_per_page,
                        'caller_get_posts' => $do_not_show_stickies
                );
                $temp = $wp_query;
                $wp_query = new WP_Query( $args );
                $this->posts_drop_down_html = '
                <select name="pmc_schedule_data_related_post[]" style="width:150px;" >
                    <option value="">None</option>';
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        $this->posts_drop_down_html .=
                                '<option value="' . get_permalink() . '" ' . selected( get_permalink(), $value, false ) . '>' . get_the_title() . ' </option>';
                    endwhile;
                endif;
                $this->posts_drop_down_html .= '
                    </select>';
                $wp_query = $temp;
            }
            else{
                ?>
                    <span class="regular-text"><br/>Please assign a `Type` to this schedule and save to see this option.</span>
                <?php
            }
        }
        
        if( $echo == true ){
            echo $this->posts_drop_down_html;
        }
        else{
            return $this->posts_drop_down_html;
        }
    }

    private function get_the_related_posts_cats(){
        global $pmc_skejool_options;
        if( !isset( $this->terms ) ){
            $this->terms = get_the_terms( 0, 'schedule_type' );
        }
        if( !isset( $this->cats ) ){
            $this->cats = array();
            for($i = 0; $i < count ( $pmc_skejool_options['related_post_taxonomies']['taxonomy'] ); $i++ ){
                if( $pmc_skejool_options['related_post_taxonomies']['taxonomy'][$i] == $this->terms[0]->term_id ){
                    array_push( $this->cats,  $pmc_skejool_options['related_post_taxonomies']['associated_category'][$i]);
                }
            }
        }
        return $this->cats;
    }
}