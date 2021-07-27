<?php

namespace Includes\Modules\Leads;

class ConsultRequest extends Leads
{
    public function __construct ()
    {
        parent::__construct ();
        parent::assembleLeadData(
            [
                'message' => 'Message'
            ]
        );
        parent::set('postType', 'Consult Submission');
        parent::set('adminEmail', 'thevirtualnephrologist@gmail.com');
    }

    protected function showForm()
    {
        $form = file_get_contents(locate_template('template-parts/forms/consult-form.php'));
        $form = str_replace('{{user-agent}}', $_SERVER['HTTP_USER_AGENT'], $form);
		$form = str_replace('{{ip-address}}', parent::getIP(), $form);
        if(isset($_SERVER['HTTP_REFERER'])){
            $form = str_replace('{{referrer}}', $_SERVER['HTTP_REFERER'], $form);
        }
        
        $formSubmitted = (isset($_POST['sec']) ? ($_POST['sec'] == '' ? true : false) : false );
        ob_start();
        if($formSubmitted){
            if($this->handleLead($_POST)){
                return '<message title="Success" class="is-success">Thank you for contacting us. Please download the <a href="https://thevirtualnephrologist.com/wp-content/uploads/2021/07/TVN-New-Patient-Packet-Complete.pdf">New Patient Packet file</a>. Once downloaded, please fill them out and fax them to (850)914-3004 and then keep them safe as your copy. </message>';
            }else{
                return '<message title="Error" class="is-danger">There was an error with your submission. Please try again.</message>';
                echo $form;
                return ob_get_clean();
            }
        }else{
            echo $form;
            return ob_get_clean();
        }
    }

    public function setupShortcode()
    {
        add_shortcode( 'consult-form', function( $atts ){
            return $this->showForm();
        } );
    }

}