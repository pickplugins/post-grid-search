<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


add_action('post_grid_container', 'post_grid_search_form', 5);

function post_grid_search_form($args){

    $post_grid_settings = get_option('post_grid_settings');

    $grid_id = $args['grid_id'];
    $post_grid_options = $args['options'];

    $font_aw_version = isset($post_grid_settings['font_aw_version']) ? $post_grid_settings['font_aw_version'] : 'v_5';


    $nav_top_search = isset($post_grid_options['nav_top']['search']) ? $post_grid_options['nav_top']['search'] : 'no';
    $grid_type = isset($post_grid_options['grid_type']) ? $post_grid_options['grid_type'] : 'grid';

    //if($nav_top_search !='yes') return;


    if($font_aw_version == 'v_5'){
        $nav_top_search_icon = '<i class="fas fa-search"></i>';
    }elseif($font_aw_version == 'v_4'){
        $nav_top_search_icon = '<i class="fa fa-search"></i>';
    }

    $nav_top_search_placeholder = isset($post_grid_options['nav_top']['search_placeholder']) ? $post_grid_options['nav_top']['search_placeholder'] : __('Start typing', 'post-grid');
    $nav_top_search_icon = isset($post_grid_options['nav_top']['search_icon']) ? $post_grid_options['nav_top']['search_icon'] : $nav_top_search_icon;


    $keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
    wp_enqueue_style('post-grid-search');

    //echo '<pre>'.var_export($_SERVER, true).'</pre>';

    $page_url = '';

    ?>
    <div class="post-grid-search">
        <form action="#<?php //echo $page_url; ?>" method="get">
            <?php

            do_action('post_grid_search', $args);

            ?>
            <div class="field-wrap submit">
                <div class="field-input">
                    <?php wp_nonce_field( 'nonce_post_grid_search' ); ?>
                    <input type="submit" class=""  placeholder="" value="Submit">

                </div>
            </div>
        </form>




    </div>




    <?php



}



add_action('post_grid_search', 'post_grid_search_keyword_field');

function post_grid_search_keyword_field($args){


//    $grid_id = isset($args['grid_id']) ? $args['grid_id'] : '';
//    if($grid_id != 7) return;
//    // 7 is grid ID where you want to display this input field
    

    $pgs_keyword = isset($_GET['pgs_keyword']) ? sanitize_text_field($_GET['pgs_keyword']) : '';

    ?>

    <div class="field-wrap">
        <div class="field-label">Keyword</div>
        <div class="field-input">
            <input type="text" class="" name="pgs_keyword" placeholder="Write keyword" value="<?php echo $pgs_keyword; ?>">
            <p class="description">Write your keyword here.</p>

        </div>
    </div>

    <?php

}



add_action('post_grid_search', 'post_grid_search_category_field');

function post_grid_search_category_field($args){

    $terms = get_terms( array(
        'taxonomy' => 'category',
        'hide_empty' => false,
    ) );

    //var_dump($terms);
    $pgs_category = isset($_GET['pgs_category']) ? sanitize_text_field($_GET['pgs_category']) : '';


    ?>

    <div class="field-wrap">
        <div class="field-label">Category</div>
        <div class="field-input">
            <select name="pgs_category">
                <option><?php echo __('Choose category', ''); ?></option>
                <?php

                if(!empty($terms))
                foreach ($terms as $term){

                    $term_id = isset($term->term_id) ? $term->term_id : '';
                    $term_name = isset($term->name) ? $term->name : '';

                    ?>
                    <option <?php echo selected($term_id, $pgs_category)?> value="<?php echo $term_id; ?>"><?php echo $term_name; ?></option>
                    <?php

                }

                ?>
            </select>
            <p class="description">Write your keyword here.</p>

        </div>
    </div>

    <?php

}



add_action('post_grid_search', 'post_grid_search_post_tag_field');

function post_grid_search_post_tag_field($args){

    $terms = get_terms( array(
        'taxonomy' => 'post_tag',
        'hide_empty' => false,
    ) );

    //var_dump($terms);
    $pgs_tag = isset($_GET['pgs_tag']) ? sanitize_text_field($_GET['pgs_tag']) : '';


    ?>

    <div class="field-wrap">
        <div class="field-label">Tags</div>
        <div class="field-input">
            <select name="pgs_tag">
                <option><?php echo __('Choose tags', ''); ?></option>
                <?php

                if(!empty($terms))
                    foreach ($terms as $term){

                        $term_id = isset($term->term_id) ? $term->term_id : '';
                        $term_name = isset($term->name) ? $term->name : '';

                        ?>
                        <option <?php echo selected($term_id, $pgs_tag)?> value="<?php echo $term_id; ?>"><?php echo $term_name; ?></option>
                        <?php

                    }

                ?>
            </select>
            <p class="description">Write your keyword here.</p>

        </div>
    </div>

    <?php

}





// Process form data and post query

function post_grid_query_custom_search($query_args, $args){

    $current_post_id = get_the_ID();

    $pgs_tag = isset($_GET['pgs_tag']) ? sanitize_text_field($_GET['pgs_tag']) : '';
    $pgs_category = isset($_GET['pgs_category']) ? sanitize_text_field($_GET['pgs_category']) : '';
    $pgs_keyword = isset($_GET['pgs_keyword']) ? sanitize_text_field($_GET['pgs_keyword']) : '';

    $query_args['s'] = $pgs_keyword;

    return $query_args;
}

add_filter('post_grid_query_args','post_grid_query_custom_search', 90, 2);







