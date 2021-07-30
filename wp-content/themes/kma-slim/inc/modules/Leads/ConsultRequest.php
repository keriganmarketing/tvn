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
        parent::set('adminEmail', 'websites@kerigan.com');

        // parent::set('adminEmail', 'thevirtualnephrologist@gmail.com');
       
        $this->assembleLeadData([
            //'name' => 'label'
            'terms' => 'Terms Accepted'
        ]);
    }

        /**
     * Handle data submitted by lead form
     * @param array $dataSubmitted
     * @instructions pass $_POST to $dataSubmitted from template file
     * @return boolean
     */
    public function handleLead ($dataSubmitted = [])
    {
        $fullName = (isset($dataSubmitted['full_name']) ? $dataSubmitted['full_name'] : null);
        $dataSubmitted['full_name'] = (isset($dataSubmitted['first_name']) && isset($dataSubmitted['last_name']) ? $dataSubmitted['first_name'] . ' ' . $dataSubmitted['last_name'] : $fullName);

        $this->addToDashboard($dataSubmitted);
        if(!$this->validateSubmission($dataSubmitted)){ return false; }
        $this->sendNotifications($dataSubmitted);
        return true;
    }

     /*
     * Validate certain data types on the backend
     * @param array $dataSubmitted
     * @return boolean $passCheck
     */
    protected function validateSubmission($dataSubmitted)
    {

        $passCheck = true;
        if ($dataSubmitted['email_address'] == '') {
            $passCheck = false;
        } elseif (!filter_var($dataSubmitted['email_address'], FILTER_VALIDATE_EMAIL) && !preg_match('/@.+\./',
                $dataSubmitted['email_address'])) {
            $passCheck = false;
        }
        if ($dataSubmitted['full_name'] == '') {
            $passCheck = false;
        }

        if (!$dataSubmitted['terms']) {
            $passCheck = false;
            echo "The Terms of Services checkbox was not checked. Please go back and review the terms and accept by clicking on the box before submitting. Thank you.";
        }
        
        if (function_exists('akismet_verify_key') && !empty(akismet_get_key())){
            if ($this->checkSpam($dataSubmitted)){
                $passCheck = false;
            }
        }

        return $passCheck;

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
                return '<message title="Success" class="is-success">Thank you for requesting a virtual consultation with Dr. Rifai. Please download the <a href="https://thevirtualnephrologist.com/wp-content/uploads/2021/07/TVN-New-Patient-Packet-Complete.pdf">New Patient Packet file</a>. Once downloaded, please fill it out and fax to (850)914-3004 and then keep them safe as your copy. Once we receive your fax Dr. Rifai will contact you to set up your appointment.</message>';
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

     /*
     * grabs blank template file and fills with content
     * @return string
     */
    protected function createEmailTemplate ($emailData)
    {
        $eol           = "\r\n";
        $emailTemplate = file_get_contents(wp_normalize_path(get_template_directory() . '/inc/modules/Leads/emailtemplate.php'));
        $emailTemplate = str_replace('{headline}', $eol . $emailData['headline'] . $eol, $emailTemplate);
        $emailTemplate = str_replace('{introcopy}', $eol . $emailData['introcopy'] . $eol, $emailTemplate);
        $emailTemplate = str_replace('{data}', $eol . $emailData['leadData'] . $eol, $emailTemplate);
        $emailTemplate = str_replace('{datetime}', date('M j, Y') . ' @ ' . date('g:i a'), $emailTemplate);
        $emailTemplate = str_replace('{website}', 'www.' . $this->domain, $emailTemplate);
        $emailTemplate = str_replace('{url}', 'https://' . $this->domain, $emailTemplate);
        $emailTemplate = str_replace('{copyright}', date('Y') . ' ' . get_bloginfo(), $emailTemplate);
        return $emailTemplate;
    }

    protected function sendNotifications ($leadInfo)
    {
        $emailAddress  = (isset($leadInfo['email_address']) ? $leadInfo['email_address'] : null);
        $fullName      = (isset($leadInfo['full_name']) ? $leadInfo['full_name'] : null);
        $rifaiIntro    = '<p>A patient has requested a virtual consultation and has been provided with the "New Patient Packet" which contains the following forms:</p><ol><li>Comprehensive Medical History</li><li>TVN HIPAA &amp; Privacy Notice</li><li>TVN HIPAA &amp; Privacy Notice Receipt</li></ol><p>The patient has also been provided with your office fax number and instructions to fax the completed packet to you.</p><p>Please check your fax machine for their completed paperwork and reach out to set up their virtual consultation appointment.<p><p>The Prosective Patient Details are below:</P>';
        $patientIntro  = '<p>Thank you for requesting a virtual consultation with Dr. Rifai.</p><p>Please download the <a href="https://thevirtualnephrologist.com/wp-content/uploads/2021/07/TVN-New-Patient-Packet-Complete.pdf">New Patient Packet pdf file</a>. Once downloaded, please fill out the information as complete as possible and fax to (850)914-3004. Be sure to keep your copies safe for use as a reference/record. Once we receive your fax we will review the information you have provided and get back with you as soon as we can.</p><p>If you do not have Adobe Reader, you can download it from <a href="https://get.adobe.com/reader/">https://get.adobe.com/reader/</a></p><p><strong>Important</strong>: All of our virtual consultations take place using Zoom to share our screen for your education. If you prefer a different Face to Face method, please let us know.</p><p>Zoom is free and you can join your appointment by going to <a href="https://zoom.us/">https://zoom.us/.</a>and entering the Meeting ID number that you will be provided with when your appointment is confirmed.<p>The information you provided to us is:</p></p>';

        $tableData = '';
        foreach ($this->additionalFields as $key => $var) {
            if(isset($leadInfo[$key])) {
                $tableData .= '<tr><td class="label"><strong>' . $var . '</strong></td><td>' . $leadInfo[$key] . '</td>';
            }
        }

        $this->sendEmail(
            [
                'to'        => $this->adminEmail,
                'from'      => get_bloginfo() . ' <noreply@' . $this->domain . '>',
                'subject'   => $this->postType . ' submitted on website',
                'cc'        => $this->ccEmail,
                'bcc'       => $this->bccEmail,
                'replyto'   => $fullName . '<' . $emailAddress . '>',
                'headline'  => 'You have received a new virtual ' . strtolower($this->postType),
                'introcopy' => $rifaiIntro,
                'leadData'  => $tableData
            ]
        );

        $this->sendEmail(
            [
                'to'        => $fullName . '<' . $emailAddress . '>',
                'from'      => get_bloginfo() . ' <noreply@' . $this->domain . '>',
                'subject'   => 'Your virtual consult request has been received',
                'bcc'       => $this->bccEmail,
                'headline'  => 'Thank you for your virtual consultation request',
                'introcopy' => $patientIntro,
                'leadData'  => $tableData
            ]
        );

    }

}