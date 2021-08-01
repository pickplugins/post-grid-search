<?php
if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('post_grid_search', 'post_grid_search_category_field_01082021');

function post_grid_search_category_field_01082021($args){

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
                <option value=""><?php echo __('Choose category', ''); ?></option>
                <?php

                if(!empty($terms))
                foreach ($terms as $term){

                    $term_id = isset($term->term_id) ? $term->term_id : '';
                    $term_name = isset($term->name) ? $term->name : '';
                    $term_count = isset($term->count) ? $term->count : 0;

                    ?>
                    <option <?php echo selected($term_id, $pgs_category)?> value="<?php echo $term_id; ?>"><?php echo $term_name; ?>(<?php echo $term_count; ?>)</option>
                    <?php

                }

                ?>
            </select>
            <p class="description">Write your keyword here.</p>

        </div>
    </div>

    <?php

}



add_action('post_grid_search', 'post_grid_search_post_tag_field_01082021');

function post_grid_search_post_tag_field_01082021($args){

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
                <option value=""><?php echo __('Choose tags', ''); ?></option>
                <?php

                if(!empty($terms))
                    foreach ($terms as $term){

                        $term_id = isset($term->term_id) ? $term->term_id : '';
                        $term_name = isset($term->name) ? $term->name : '';
                        $term_count = isset($term->count) ? $term->count : 0;

                        ?>
                        <option <?php echo selected($term_id, $pgs_tag)?> value="<?php echo $term_id; ?>"><?php echo $term_name; ?>(<?php echo $term_count; ?>)</option>
                        <?php

                    }

                ?>
            </select>
            <p class="description">Write your keyword here.</p>

        </div>
    </div>

    <?php

}




add_action('post_grid_search', 'post_grid_search_post_reset_field_01082021');

function post_grid_search_post_reset_field_01082021($args){

    ?>
    <div class="field-wrap">
        <div class="field-label"></div>
        <div class="field-input">
            <input type="reset" value="Reset">
        </div>
    </div>

    <?php

}



// Process form data and post query

function post_grid_query_custom_search_01082021($query_args, $args){

    $tax_query = isset($query_args['tax_query']) ? $query_args['tax_query'] : array();

    $current_post_id = get_the_ID();

    $pgs_tag = isset($_GET['pgs_tag']) ? sanitize_text_field($_GET['pgs_tag']) : '';
    $pgs_category = isset($_GET['pgs_category']) ? sanitize_text_field($_GET['pgs_category']) : '';


    if(!empty($pgs_category)){
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => array($pgs_category),
        );
    }

    if(!empty($pgs_tag)){
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'term_id',
            'terms'    => array($pgs_tag),
        );
    }

    //$tax_query['relation'] = 'AND';

    $query_args['tax_query'] = $tax_query;

    //echo '<pre>'.var_export($tax_query, true).'</pre>';



    return $query_args;
}

add_filter('post_grid_query_args','post_grid_query_custom_search_01082021', 90, 2);







