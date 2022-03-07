<?php

namespace Includes\Modules\Leads;

class SimpleContact extends Leads
{

    public    $errors              = [];
    public    $postType            = 'Contact Submission';
    // public    $adminEmail          = 'bryan@kerigan.com';
    public    $adminEmail          = 'thevirtualnephrologist@gmail.com';
    public    $domain              = 'thevirtualnephrologist.com';
    public    $ccEmail             = '';
    public    $bccEmail            = 'support@kerigan.com';
    public    $additionalFields    = ['message' => 'Message'];
    public    $shortcode           = 'contact_form';
    public    $successMessage      = 'Thank you for contacting us. Your message has been received.';
    public    $formFileName        = 'contact-form';
    
    public    $fromName            = 'TVN Website';
    public    $fromEmail           = 'notifications@mg.thevirtualnephrologist.com';

    public    $subjectLine         = 'New Message submitted on the TVN website';
    public    $emailHeadline       = 'You have a new message';
    public    $emailText           = '<p style="font-size:18px; color:black;" >A message was submitted on the TVN website. Details are below:</p>';

    public    $receiptSubjectLine  = 'Thank you for contacting The Virtual Nephrologist';
    public    $receiptHeadline     = 'Your website submission has been received';
    public    $receiptText         = '<p style="font-size:18px; color:black;" >We\'ll review the information you\'ve provided and get back with you as soon as we can.</p>';

}