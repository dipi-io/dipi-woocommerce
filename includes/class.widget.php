<?php

/**
 * @since      2.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Widget
{
    public function add_shortcodes()
    {
        add_shortcode( 'dipi_widget', function ( $atts ) {
            $brand_id = esc_attr( get_option( 'dipi_woocommerce_brand_id' ) );
            $atts = shortcode_atts( array(
                'language' => esc_attr( get_option( 'dipi_woocommerce_widget_language', 'en' ) ),
                'button' => esc_attr( get_option( 'dipi_woocommerce_widget_button_color', '#000000') ),
                'text' => esc_attr( get_option( 'dipi_woocommerce_widget_text_color', '#ffffff' ) ),
            ), $atts );
            ob_start();
            if ( $brand_id ) {
                echo '<div id="dipi-widget">
                    <iframe
                    frameborder="0"
                    style="height:485px;width:100%;max-width:400px;"
                    src="https://dipi.io/widgets/register/' . $brand_id . '?button_background_color=' . esc_attr( str_replace( '#', null, $atts['button'] ) ) . '&button_text_color=' . esc_attr( str_replace( '#', null, $atts['text'] ) ) . '&language=' . esc_attr( $atts['language'] ) . '">
                </iframe>
                </div>';
            }
            $widget = ob_get_contents();
            ob_end_clean();

            return $widget;
        } );
    }
}