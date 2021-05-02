<?php
/**
* A tool to create weekly slider
* Example: the slider used on the TAR homepage
* Depends of stfSaveImages in library/stf_weekly_slider.php
* @param mixed $images, $title, $id
* @return html
* @author Laud Tetteh (STF)
*/

class STFWeeklySlider {

    public function __construct() {
        // Silence is golden
    }

    public function sftGetImageId() {
        $id_array = [];
        $today = date('l');// What's today (eg. Tuesday)?
        $today_full = date( 'Y-m-d', strtotime('today') );
        $most_recent_monday = date( 'Y-m-d', strtotime('previous monday', strtotime($today) ) );
        $day_no = date('N', strtotime($today)); // What's today's number (eg. Tuesday is 2)
        $saved_gallery = json_decode( get_option('stf_weekly_slider'), true );
        $last_key_in_saved = array_key_last($saved_gallery);

        if( $day_no === 1 && array_key_exists($today_full, $saved_gallery)) {// If today is Monday, set $current_monday to today
            $current_monday = date( 'Y-m-d', strtotime('today') );
        } elseif( array_key_exists($most_recent_monday, $saved_gallery) ) {// Else grab most recent monday
            $current_monday = $most_recent_monday;
        } else {
            $current_monday = $last_key_in_saved;
        }
        $current_image_id = $saved_gallery[$current_monday];

        return $current_image_id;
    }

    public function stfCurrentImage($title, $id) {
        $html = '';
        $current_image_id = $this->sftGetImageId();
        $size_array = array (
            'small',
            'medium',
        );
        $alt_placeholder = 'Featured image for '. $title;
        $sparkle = new SparkleImage();
        $sparkle_img = $sparkle->grabImage ( $current_image_id, 'medium', $size_array, $id, $alt_placeholder );
        $sparkle_caption = $sparkle->grabImageCaption( $current_image_id );
        $html .= $sparkle_img;

        if ( $sparkle_caption ) {
            $html .= '<figcaption>';
            $html .= $sparkle_caption;
            $html .= '</figcaption>';
        }

        return $html;
    }
}
