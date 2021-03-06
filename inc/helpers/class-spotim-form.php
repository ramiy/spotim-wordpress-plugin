<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * SpotIM_Form_Helper
 *
 * Form helpers.
 *
 * @since 3.0.0
 */
class SpotIM_Form_Helper {

    /**
     * Set name
     *
     * @since 3.0.0
     *
     * @access private
     * @static
     *
     * @param array $args
     *
     * @return array
     */
    private static function set_name( $args ) {
        if ( ! isset( $args['name'] ) ) {
            $args['name'] = sprintf(
                '%s[%s]',
                esc_attr( $args['page'] ),
                esc_attr( $args['id'] )
            );
        }

        return $args;
    }

    /**
     * Get description
     *
     * @since 3.0.0
     *
     * @access private
     * @static
     *
     * @param string $text
     *
     * @return string
     */
    private static function get_description_html( $text = '' ) {
        return sprintf( '<p class="description">%s</p>', wp_kses_post( $text ) );
    }

    /**
     * Hidden fields
     *
     * @since 4.0.0
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function hidden_field( $args ) {
        $args = self::set_name( $args );
        $args['value'] = sanitize_text_field( $args['value'] );

        // Text input template
        printf(
            '<input name="%1$s" type="hidden" value="%2$s" />',
            esc_attr( $args['name'] ), // Input's name.
            esc_attr( $args['value'] ) // Input's value.
        );
    }

    /**
     * Yes/No fields
     *
     * @since 3.0.0
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function yes_no_fields( $args ) {
        $args = self::set_name( $args );
        $radio_template = '<label class="description"><input type="radio" name="%s" value="%d" %s /> %s &nbsp;&nbsp;&nbsp;</label>';
        $yes_value = 1;
        $no_value = 0;

        // Backward compatability condition
        if ( ! isset( $args['value'] ) || false === $args['value'] ) {
            $args['value'] = $no_value;
        } else if ( true === $args['value'] ) {
            $args['value'] = $yes_value;
        }

        // Yes template
        $template = sprintf( $radio_template,
            esc_attr( $args['name'] ), // Input's name.
            esc_html( $yes_value ), // Input's value.
            checked( $args['value'], $yes_value, 0 ), // If input checked or not.
            esc_html__( 'Yes', 'spotim-comments' ) // Translated text.
        );

        // No template
        $template .= sprintf( $radio_template,
            esc_attr( $args['name'] ), // Input's name.
            esc_html( $no_value ), // Input's value.
            checked( $args['value'], $no_value, 0 ), // If input checked or not.
            esc_html__( 'No', 'spotim-comments' ) // Translated text.
        );

        // Description template
        if ( isset( $args['description'] ) ) {
            $template .= self::get_description_html( $args['description'] );
        }

        echo $template;
    }

    /**
     * Radio fields
     *
     * @since 4.0.0
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function radio_fields( $args ) {
        $args = self::set_name( $args );
        $template = '';

        foreach ( $args['fields'] as $key => $value ) {
            $template .= sprintf(
                '<label class="description"><input type="radio" name="%1$s" value="%2$s" %3$s /> %4$s</label><br>',
                esc_attr( $args['name'] ), // Input's name.
                esc_attr( $key ), // Input's value.
                checked( $args['value'], $key, 0 ), // If input checked or not.
                esc_html( $value ) // Translated text.
            );
        }

        // Description template
        if ( isset( $args['description'] ) ) {
            $template .= self::get_description_html( $args['description'] );
        }

        echo $template;
    }

    /**
     * Text fields
     *
     * @since 3.0.0
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function text_field( $args ) {
        $args = self::set_name( $args );
        $args['value'] = sanitize_text_field( $args['value'] );
        if ( isset( $args['other'] ) ) {
            $args['other'] = sanitize_text_field( $args['other'] );
        } else {
            $args['other'] = '';
        }

        // Text input template
        $template = sprintf(
            '<input name="%1$s" type="text" value="%2$s" autocomplete="off" %3$s />',
            esc_attr( $args['name'] ),  // Input's name.
            esc_attr( $args['value'] ), // Input's value.
            esc_attr( $args['other'] )  // Other input attributes like 'readonly' or `disabled`.
        );

        // Description template
        if ( isset( $args['description'] ) ) {
            $template .= self::get_description_html( $args['description'] );
        }

        echo $template;
    }

    /**
     * Number fields
     *
     * @since 4.0.4
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function number_field( $args ) {
        $args = self::set_name( $args );
        $args['value'] = (int) $args['value'];
        if ( isset( $args['other'] ) ) {
            $args['other'] = sanitize_text_field( $args['other'] );
        } else {
            $args['other'] = '';
        }

        // Text input template
        $template = sprintf(
            '<input name="%1$s" type="number" value="%2$s" min="%3$s" max="%4$s" autocomplete="off" %5$s />',
            esc_attr( $args['name'] ),  // Input's name.
            esc_attr( $args['value'] ), // Input's value.
            esc_attr( $args['min'] ),   // Input's min value.
            esc_attr( $args['max'] ),   // Input's max value.
            esc_attr( $args['other'] )  // Other input attributes like 'readonly' or `disabled`.
        );

        // Description template
        if ( isset( $args['description'] ) ) {
            $template .= self::get_description_html( $args['description'] );
        }

        echo $template;
    }

    /**
     * Button fields
     *
     * @since 3.0.0
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function button( $args ) {
        $template = sprintf(
            '<button id="%1$s" class="button button-primary">%2$s</button>',
            esc_attr( $args['id'] ), // Button's id.
            esc_attr( $args['text'] ) // Button's text.
        );

        // Description template
        if ( isset( $args['description'] ) ) {
            $template .= self::get_description_html( $args['description'] );
        }

        echo $template;
    }

    /**
     * Import Button fields
     *
     * @since 3.0.0
     *
     * @access public
     * @static
     *
     * @param array $args
     *
     * @return string
     */
    public static function import_button( $args ) {
        $spotim = spotim_instance();

        // Import button
        $template = sprintf(
            '<button id="%1$s" class="button button-primary" data-import-token="%2$s" data-spot-id="%3$s" data-posts-per-request="%4$s">%5$s</button>',
            esc_attr( $args['import_button']['id'] ), // Button's id.
            esc_attr( $spotim->options->get( 'import_token' ) ), // Import token
            esc_attr( $spotim->options->get( 'spot_id' ) ), // Spot ID
            esc_attr( $spotim->options->get( 'posts_per_request' ) ), // Posts per request
            esc_attr( $args['import_button']['text'] ) // Button's text.
        );

        // Cancel import
        $template .= sprintf(
            '<a href="#cancel" id="%1$s" class="">%2$s</a>',
            esc_attr( $args['cancel_import_link']['id'] ), // Link's id.
            esc_attr( $args['cancel_import_link']['text'] ) // Link's text.
        );

        // Description template
        $template .= self::get_description_html();
        $template .= '<div class="errors spotim-errors spotim-hide red-color"></div>';

        echo $template;
    }
}
