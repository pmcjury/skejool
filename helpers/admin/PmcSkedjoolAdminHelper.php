<?php

function get_posts_drop_down( $value = '' ){
    query_posts( array ('category__in' => array( 8, 10, 13 ) ) );
    if (have_posts()) : 
        ?> 
        <select name="pmc_schedule_data_related_post[]" >
            <option value="">None</option>
        <?php
        while (have_posts()) :
            the_post();
            $selected = get_permalink() == $value ? ' selected=selected' : '';
        ?>
            <option value="<?php the_permalink(); ?>" <?php echo $selected;?>><?php the_title(); ?></option>
        <?php
        endwhile;
        ?> </select> <?php
    endif;
}