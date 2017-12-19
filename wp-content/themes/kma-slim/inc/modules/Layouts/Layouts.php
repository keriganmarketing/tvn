<?php

namespace Includes\Modules\Layouts;

use KeriganSolutions\CPT\CustomPostType;

class Layouts
{

    protected $sidebarTitle;

    /**
     * Layouts constructor.
     */
    function __construct()
    {

    }

    public function addPageHeadlines(){
        $page = new CustomPostType('Page');
        $page->addMetaBox(
            'Page Information',
            array(
                'Headline'     => 'text',
                'Subhead'      => 'text',
                'Preview Text' => 'textarea'
            )
        );
    }

    public function createLayouts(){
        $this->createTaxonomy();
        $this->createDefaultFormats();
    }

    /**
     * @return null
     */
    protected function createTaxonomy()
    {
        $page = new CustomPostType('Page');

        $page->addTaxonomy('layout', array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'format'),
            'capabilities'      => array(
                'manage_terms' => '',
                'edit_terms'   => '',
                'delete_terms' => '',
                'assign_terms' => 'edit_posts'
            ),
            'public'            => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
        ));

        $page->convertCheckToRadio('layout');

    }

    /**
     * @return null
     */
    protected function createDefaultFormats()
    {

        add_action('init', function () {
            wp_insert_term(
                'Default',
                'layout',
                array(
                    'description' => '',
                    'slug'        => 'default'
                )
            );
        });

    }

    /**
     * @param term
     * @param slug
     * @param description
     */
    public function addLayout($term = '', $description = '', $slug = '')
    {

        wp_insert_term(
            $term,
            'layout',
            [
                'description' => $description,
                'slug'        => $slug
            ]
        );

    }

    public function addContentBox($term = 'default', $title = 'Content'){

        $page = new CustomPostType('Page');
        $page->addMetaBox(
            $title,
            array(
                'HTML' => 'wysiwyg'
            )
        );

    }

    /**
     * @return null
     */
    public function createSidebarSelector()
    {
        $page = new CustomPostType('Page');

        $page->addTaxonomy('sidebar', array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'capabilities'      => array(
                'manage_terms' => '',
                'edit_terms'   => '',
                'delete_terms' => '',
                'assign_terms' => 'edit_posts'
            ),
            'public'            => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
        ));

    }

    protected function uglify( $name ){
        return str_replace(' ', '_', strtolower($name) );
    }

    public function addSidebar( $title )
    {
        $this->sidebarTitle[] = $title;

        add_action('init', function () {

            foreach($this->sidebarTitle as $title) {
                wp_insert_term(
                    $title,
                    'sidebar',
                    array(
                        'description' => '',
                        'slug'        => $this->uglify($title)
                    )
                );
            }
        });

    }

    public function hasSidebars($post){
        $terms = wp_get_post_terms($post->ID,'sidebar');
        return count($terms) > 0 ? true : false;
    }

    public function getSidebars($post){

        $terms = wp_get_post_terms($post->ID,'sidebar');
        $sidebars = [];

        foreach ($terms as $term){
            $sidebars[] = str_replace('_','-', $term->slug);
        }

        return $sidebars;

    }

}
