<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PmcSkejoolAdminOptionsController
 *
 * @author pmcjury
 */
class PmcSkejoolAdminOptionsController {

    public function __construct(){

    }


    public function delete_options(){
        delete_option( 'pmc_skejool_options' );
    }

    public function activate_options(){
        $related_post_taxonomies = array( '');
        $options = array(
            'version' => 1.0,
            'active' => true,
            'related_post_taxonomies' => array( 'taxonomy' => array( 0 ), 'associated_category' => array( 0 )  )
            );
        update_option( 'pmc_skejool_options', $options );
    }

    public function options_init() {
        register_setting( 'pmc_skejool_options_settings_fields', 'pmc_skejool_options', array( &$this, 'validate_options' ));
        add_settings_section('pmc_skejool_options_settings_section_id', '', array( &$this, 'show_options_section_text' ), 'pmc_skejool_options_settings');
        add_settings_field('pmc_skejool_options', '', array( &$this, 'show_options' ), 'pmc_skejool_options_settings', 'pmc_skejool_options_settings_section_id');
    }
    
    public function validate_options( $options ) {
        foreach( $options as $option ){
            if( is_array( $option ) ){
                foreach( $option as $key => $value ){
                }
            }
        }
        return $options;
    }

    public function show_options() {
        $options = get_option( 'pmc_skejool_options' );
        $helper = new PmcSkejoolAdminOptionsHelper();
        foreach( $options as $key => $value ) : ?>
            <tr valign="top">
                <th scope="row"><label for="<?php echo $key; ?>_id"><?php echo $helper->get_option_description( $key ) ?></label></th>
                <td>
                <?php
                switch( $key ){
                    case 'related_post_taxonomies':
                        ?>
                        <input type="button" class="button-secondary" value="+ Add" id="add_tax_cat"/>
                        <?php
                        $i = 0;
                        foreach( $value['taxonomy'] as $tax => $cat ) :
                        ?>
                        <div class="tax_cat">
                            <?php $helper->get_taxonomy_term_display( $i ); ?>
                            <?php //$helper->get_taxonomy_drop_down( $value['taxonomy'][$i] ) ;?>
                            <?php $helper->get_associated_category_drop_down( $value['associated_category'][$i] ) ;?>
                            <a href="#" class="remove_tax_cat">remove</a>
                        </div>
                     
                        <?php
                            $i++;
                        endforeach;
                        ?>
                            <span class="description" id="error_message" style="color:#c00; display: none;">Only One mapping per Taxonomy Term. Also can only map the max number of terms.</span>
                            <script type="text/javascript">
                            (function($){
                                $(document).ready(function(){
                                    $.max_tax_cat = $('.taxonomy_dd:first').children().length;
                                    $('#add_tax_cat').click(function(e){
                                        if( $('div.tax_cat').length <  $.max_tax_cat ){
                                            var orig = $('div.tax_cat:last');
                                            var clone = orig.clone(true);
                                            clone.children().each(function(e){
                                                if($(this).is('a')){
                                                    $(this).css('display', '');
                                                }
                                                else{
                                                    $(this).val('');
                                                }
                                            });
                                            clone.css('display', 'none');
                                            orig.after(clone);
                                            clone.fadeIn();
                                            $('#error_message').hide();
                                        }
                                        else{
                                            $('#error_message').fadeIn();
                                        }
                                    });
                                    $('a.remove_tax_cat').click(function(e){
                                        $(this).parent().fadeOut(250, function(e){
                                            $(this).remove();
                                        });
                                        $('#error_message').hide();
                                        return false;
                                    });
                                    $('a.remove_tax_cat:first').css('display', 'none');
                                });
                            })(jQuery);
                            </script>
                        <?php
                        break;
                    case 'version':
                    case 'active':
                        ?>
                        <input name="pmc_skejool_options[<?php echo $key; ?>]" type="hidden" value="<?php echo $value ; ?>" />
                        <span class="regular-text description"><?php echo $value ; ?></span>
                        <?php
                        break;
                    default:
                        $class = 'regular-text';
                        if( strstr( $key , 'url' ) ) {
                            $class .= ' code';
                        }
                        ?>
                        <input name="pmc_skejool_options[<?php echo $key; ?>]" type="text" id="<?php echo $key; ?>_id" value="<?php echo $value ; ?>" class="<?php echo $class; ?>" />
                        <?php
                        break;
                }
                ?>
                </td>
           </tr>
           <?php
        endforeach;
    }

    public function show_options_page() {
        include ( PMC_SKEJOOL_VIEWS_DIR . '/admin/options.php' );
    }

    public function show_options_section_text() {
        echo 'These are some setting used to configure some of the schedules.';
    }
}

class PmcSkejoolAdminOptionsHelper{

    private $taxonomy_terms;
    
    public function __construct(){

    }

    public function get_option_description( $key ){
        $description = ucwords( str_replace('_', ' ', $key ) );
        return $description;
    }

    public function get_taxonomy_term_display( $index ){
        if( !isset( $this->taxonomy_terms ) ){
            $this->taxonomy_terms = get_terms( 'schedule_type' );
        }
        $tax_term = $this->taxonomy_terms[$index];
        ?>
           <label>Schedule Type</label>
           <input type="text" value="<?php echo $tax_term->name ; ?>" disabled/>
           <input type="hidden" name="pmc_skejool_options[related_post_taxonomies][taxonomy][]" value="<?php echo $tax_term->term_id ; ?>" />
        <?php
    }

    public function get_taxonomy_drop_down( $value ){
        $this->get_a_drop_down( get_terms( 'schedule_type'), 'taxonomy', $value );
    }

    public function get_associated_category_drop_down( $value ){
        $this->get_a_drop_down( get_categories(), 'associated_category', $value );
    }

    private function get_a_drop_down( $values, $key, $value ){
        ?>
        <label><?php echo $this->get_option_description($key); ?></label>
        <select class="<?php echo $key; ?>_dd" name="pmc_skejool_options[related_post_taxonomies][<?php echo $key; ?>][]">
        <?php
        foreach( $values as $val ){
            $v = '';
            $display = '';
            switch( $key ){
                case 'associated_category':
                    $v = $val->cat_ID;
                    $display = $val->cat_name;
                    break;
                case 'taxonomy':
                    $v = $val->term_id;
                    $display = $val->name;
                    break;
                default:
                    break;
            }
            ?>
                <option value="<?php echo $v; ?>" <?php selected( $v, $value );?>><?php echo $display; ?></option>
            <?php
        }
        ?>
        </select>
        <?php
    }
}
?>
