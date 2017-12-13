<?php
namespace Includes\Modules\Helpers;

use KeriganSolutions\CPT\CustomPostType;

class PageField
{
    public function addField( $pageId = 0, $metaBoxTitle, $metaBoxFields ){
        if(is_admin()) { //is logged in
            $postEdited = (isset($_GET['post']) ? intval($_GET['post']) : (isset($_POST['post_ID']) ? intval($_POST['post_ID']) : 0));

            if ($postEdited == $pageId) { //is editing correct page
                $pageObject = new CustomPostType('page');
                $pageObject->addMetaBox($metaBoxTitle, $metaBoxFields);
            }
        }
    }

    public function getField( $fieldKey, $pageId = '' )
    {
        $pageId = $pageId != '' ? $pageId : get_the_ID();
        return get_post_meta( $pageId, $fieldKey, true);
    }
}