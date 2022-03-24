<?php

namespace Includes\Modules\Leads;

class ConsultRequest extends Leads
{
    public    $errors              = [];
    public    $postType            = 'Consult Submission';
    // public    $adminEmail          = 'bryan@kerigan.com';
    public    $adminEmail          = 'thevirtualnephrologist@gmail.com';
    public    $domain              = 'thevirtualnephrologist.com';
    public    $ccEmail             = '';
    public    $bccEmail            = 'support@kerigan.com';
    public    $additionalFields    = ['message' => 'Message'];
    public    $shortcode           = 'consult-form';
    public    $successMessage      = 'Thank you for requesting a virtual consultation with Dr. Rifai. Please download the <a target="_blank" href="https://thevirtualnephrologist.com/wp-content/uploads/2021/07/TVN-New-Patient-Packet-Complete.pdf">New Patient Packet file</a>. Once downloaded, please fill it out and fax to (850)914-3004 and then keep them safe as your copy. Once we receive your fax Dr. Rifai will contact you to set up your appointment.';
    public    $formFileName        = 'consult-form';
    
    public    $fromName            = 'TVN Website';
    public    $fromEmail           = 'notifications@mg.thevirtualnephrologist.com';

    public    $subjectLine         = 'Virtual consultation request submitted on website';
    public    $emailHeadline       = 'You have received a new virtual consultation request';
    public    $emailText           = '<p style="font-size:18px; color:black;">A patient has requested a virtual consultation and has been provided with the "New Patient Packet" which contains the following forms:</p><ol><li>Comprehensive Medical History</li><li>TVN HIPAA &amp; Privacy Notice</li><li>TVN HIPAA &amp; Privacy Notice Receipt</li></ol><p style="font-size:18px; color:black;">The patient has also been provided with your office fax number and instructions to fax the completed packet to you.</p><p style="font-size:18px; color:black;">Please check your fax machine for their completed paperwork and reach out to set up their virtual consultation appointment.</p><p style="font-size:18px; color:black;">The Prospective Patient Details are below:</p>';

    public    $receiptSubjectLine  = 'Thank you for your virtual consultation request';
    public    $receiptHeadline     = 'Your virtual consultation request has been received';
    public    $receiptText         = '<p style="font-size:18px; color:black;>Thank you for requesting a virtual consultation with Dr. Rifai.</p><h2>Next Steps:</h2><p style="font-size:18px; color:black;">Please download the <a target="_blank" href="https://thevirtualnephrologist.com/wp-content/uploads/2021/07/TVN-New-Patient-Packet-Complete.pdf">New Patient Packet pdf file</a>. Once downloaded, fill out the information as complete as possible and fax to (850) 914-3004. Be sure to keep your copies safe for use as a reference/record.</p> <h2>What To Expect:</h2><p style="font-size:18px; color:black;">Once we receive your New Patient Packet via fax, we will review the information you have provided and contact you to schedule a consultation at a time convenient for you.</p><p style="font-size:18px; color:black;">The information you provided to us is:</p>';

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
            $this->errors[] = 'Email address is required';

        } elseif (!filter_var($dataSubmitted['email_address'], FILTER_VALIDATE_EMAIL) && !preg_match('/@.+\./',
                $dataSubmitted['email_address'])) {
            $passCheck = false;
            $this->errors[] = 'Email address is not formatted correctly';
        }
        if ($dataSubmitted['full_name'] == '') {
            $passCheck = false;
            $this->errors[] = 'Both first name and last name are required';
        }

        if ($dataSubmitted['terms'] != 'Terms Accepted') {
            $passCheck = false;
            $this->errors[] = 'The Terms of Services checkbox was not checked';
        }
        
        if (function_exists('akismet_verify_key')){
            $passCheck = $this->checkSpam($dataSubmitted);
        }

        return $passCheck;
    }
}