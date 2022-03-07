<?php

namespace Includes\Modules\Leads;

class ManualSubscribe extends Leads
{

    public    $errors              = [];
    public    $postType            = 'Subscription';
    // public    $adminEmail          = 'bryan@kerigan.com';
    public    $adminEmail          = 'thevirtualnephrologist@gmail.com';
    public    $domain              = 'thevirtualnephrologist.com';
    public    $ccEmail             = '';
    public    $bccEmail            = 'support@kerigan.com';
    public    $additionalFields    = [];
    public    $shortcode           = 'subscribe_form';
    public    $successMessage      = 'Thank you for subscribing!';
    public    $formFileName        = 'subscribe-form';
    
    public    $fromName            = 'TVN Website';
    public    $fromEmail           = 'notifications@mg.thevirtualnephrologist.com';

    public    $subjectLine         = 'New E-Library Subscription Received';
    public    $emailHeadline       = 'You have a new e-library subscription';
    public    $emailText           = '<p style="font-size:18px; color:black;" >Someone has subscribed to the e-library on the website. You will need to add them to your marketing lists manually. Details are below:</p>';

    public    $receiptSubjectLine  = 'Thank you for subscribing to the TVN e-Library';
    public    $receiptHeadline     = 'Thanks for Subscribing';
    public    $receiptText         = '<p style="font-size:18px; color:black;" >We\'ll review your submission and add you to our list.</p>';

}