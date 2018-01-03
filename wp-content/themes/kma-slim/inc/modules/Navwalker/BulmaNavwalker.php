<?php

namespace Includes\Modules\Navwalker;

/**
 * Custom Navwalker Class
 * Class Name: BulmaNavwalker
 * Description: A custom WordPress nav walker class to implement the Bulma navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 0.0.1
 * Author: Scops UG (haftungsbeschränkt)
 * Credit: Based on Bootstrap navwalker from Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 // Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

class BulmaNavwalker extends \Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div role=\"menu\" class=\"navbar-dropdown is-boxedu\">\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</div>\n";
    }
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $walkerAtts = $args->walker;
        $menuAtts 	= $args->menu;

        //echo '<pre>' . print_r($item) . '</pre>';

        if ( strcasecmp( $item->description, 'divider' ) == 0 && $depth === 1 ) {
            $output .= '<hr class="navbar-divider">';
        }else{

            if ( $walkerAtts->has_children == 1 ){
                $containerClasses[] = 'has-children';
                $containerClasses[] = 'is-hoverable';
            }
            if ( $item->menu_item_parent > 0 ){
                $containerClasses[] = 'has-parent';
            }
            $containerClasses[] = 'parent-is-' . $item->menu_item_parent;
            $containerClasses[] = 'is-item-' . $item->ID;

            $containerClasses = implode(' ', $containerClasses);
            $output .= '<div class="navbar-item ' . $containerClasses . '">';

            $class_names = '';
            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'navbar-item';
            if ( $walkerAtts->has_children == 1 ){
                $classes[] = 'navbar-link';
            }
            if ( in_array('current-menu-item',$item->classes) ){
                $classes[] = 'is-active';
            }
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
            $class_names = $class_names ? ' ' . esc_attr( $class_names ) . '' : '';
            $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
            $output .= $indent . '';
            $attributes  = ! empty( $class_names ) 		? ' class="'  . esc_attr( $class_names 	 	) .'"' : '';
            $attributes .= ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

            if ( in_array('button',$item->classes) && $depth === 1 ) {
                $item_output = $args->before;
                $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
                $item_output .= '<a class="button is-small" target="'.$item->target.'" href="'.$item->url.'" style="margin-left:1rem" >';
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->description, $item->ID ) . $args->link_after;
                $item_output .= '</a>';
                $item_output .= $args->after;
            }else{
                $item_output = $args->before;
                $item_output .= '<a '. $attributes .'>';
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                $item_output .= '</a>';
                $item_output .= $args->after;
            }
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

        }
    }
    function end_el( &$output, $item, $depth = 0, $args = array() ) {
//        if( ($item->menu_item_parent == 0 || $args->walker->has_children == 1) || (strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1) || (in_array('button',$item->classes) && $depth <= 1) ){
//            $output .= "</div>\n";
//        }
        $output .= '</div>';
    }
}
