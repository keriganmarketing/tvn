<?php

namespace Includes\Modules\Leads;

use KeriganSolutions\CPT\CustomPostType;

class Leads
{
    public    $postType            = 'Lead';
    public    $adminEmail          = 'bryan@kerigan.com';
    public    $domain              = 'thevirtualnephrologist.com';
    public    $ccEmail             = '';
    public    $bccEmail            = 'support@kerigan.com';
    public    $additionalFields    = [];
    public    $shortcode           = 'contact-form';
    public    $successMessage      = 'Success';
    public    $formFileName        = 'contact-form';
    
    public    $fromName            = 'TVN Website';
    public    $fromEmail           = 'notifications@mg.thevirtualnephrologist.com';

    public    $subjectLine         = 'New lead submitted on website';
    public    $emailHeadline       = 'You have a new lead from the website';
    public    $emailText           = '<p style="font-size:18px; color:black;" >A lead was received from the website. Details are below:</p>';

    public    $receiptSubjectLine  = 'Thank you for contacting The Virtual Nephrologist';
    public    $receiptHeadline     = 'Your website submission has been received';
    public    $receiptText         = '<p style="font-size:18px; color:black;" >We\'ll review the information you\'ve provided and get back with you as soon as we can.</p>';

    /**
     * Leads constructor.
     * configure any options here
     */
    public function __construct ()
    {
        date_default_timezone_set('America/Chicago');

        //use this to merge in additional fields
        $this->assembleLeadData();
    }

    protected function uglify($var){
        return str_replace(' ', '_', strtolower($var));
    }

    /**
     * Creates custom post type and backend view
     * @instructions run once from functions.php
     */
    public function setupAdmin ()
    {
        $this->createPostType();
        $this->createAdminColumns();
    }

    public function setupShortcode()
    {
        add_shortcode( $this->shortcode, function( $atts ){
            return $this->showForm();
        } );
    }

    protected function showForm()
    {
        $form = file_get_contents(locate_template('template-parts/forms/'.$this->formFileName.'.php'));
        $form = str_replace('{{user-agent}}', $_SERVER['HTTP_USER_AGENT'], $form);
		$form = str_replace('{{ip-address}}', $this->getIP(), $form);
        if(isset($_SERVER['HTTP_REFERER'])){
            $form = str_replace('{{referrer}}', $_SERVER['HTTP_REFERER'], $form);
        }
        
        $formSubmitted = (isset($_POST['sec-validation-feild']) && $_POST['sec-validation-feild'] == '' ? true : false );

        ob_start();

        if(!$formSubmitted){
            echo $form;
            return ob_get_clean();
        }

        if($this->handleLead($_POST)){
            echo '<message title="Success" class="is-success">'.$this->successMessage.'</message>';
        }else{
            echo '<message title="Error" class="is-danger">There was an error with your submission. Please correct the following issues:<br><ul>';
                foreach($this->errors as $error){
                    echo '<li>'.$error.'</li>';
                }
            echo '</ul></message>';
            echo $form;
        }

        return ob_get_clean();
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

        if(!$this->validateSubmission($dataSubmitted)){ 
            return false; 
        }

        $this->addToDashboard($dataSubmitted);
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
        
        if (function_exists('akismet_verify_key')){
            $passCheck = $this->checkSpam($dataSubmitted);
        }

        return $passCheck;

    }

    public function getIP()
    {
        $Ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '')
        $Ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
        $Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '')
        $Ip = $_SERVER['REMOTE_ADDR'];
        if (($CommaPos = strpos($Ip, ',')) > 0)
        $Ip = substr($Ip, 0, ($CommaPos - 1));

        return $Ip;
    }

    public function checkSpam($dataSubmitted)
    {
        global $akismet_api_host, $akismet_api_port;

        // data package to be delivered to Akismet
        $data = array(
            'comment_author_email'  => $dataSubmitted['email_address'],
            'blog'                  => site_url(),
            'blog_lang'             => 'en_US',
            'blog_charset'          => 'UTF-8',
            'is_test'               => TRUE,
        );

        if(isset($dataSubmitted['ip_address'])){
            $data['comment_author'] = $dataSubmitted['ip_address'];
        }

        if(isset($dataSubmitted['user_agent'])){
            $data['comment_author'] = $dataSubmitted['user_agent'];
        }

        if(isset($dataSubmitted['referrer'])){
            $data['comment_author'] = $dataSubmitted['referrer'];
        }

        if(isset($dataSubmitted['full_name'])){
            $data['comment_author'] = $dataSubmitted['full_name'];
        }

        if(isset($dataSubmitted['message'])){
            $data['comment_content'] = $dataSubmitted['message'];
        }

        // construct the query string
        $query_string = http_build_query( $data );
        // post it to Akismet
        $response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );

        // the result is the second item in the array, boolean
        return $response[1] == 'true' ? false : true;

    }

    /**
     * adds a lead (post) to WP admin dashboard (database)
     * @param array $leadInfo
     */
    protected function addToDashboard ($leadInfo)
    {

        $fieldArray = [];
        foreach($this->additionalFields as $name => $label){
            $fieldArray['lead_info_' . $name] = (isset($leadInfo[$name]) ? $leadInfo[$name] :  null);
        }

        wp_insert_post(
            [
                'post_content'   => '',
                'post_status'    => 'publish',
                'post_type'      => $this->uglify($this->postType),
                'post_title'     => $leadInfo['full_name'],
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'meta_input'     => $fieldArray
            ],
            true
        );
    }

    /**
     * Returns a properly formatted address
     * @param  $street
     * @param  $street2
     * @param  $city
     * @param  $state
     * @param  $zip
     *
     * @return string
     */
    protected function toFullAddress ($street, $street2, $city, $state, $zip)
    {
        return $street . ' ' . $street2 . ' ' . $city . ', ' . $state . '  ' . $zip;
    }

    /*
     * Used to manage data retrieved by lead. Used by pretty much everything.
     * Can pass in additional inputs. Arrays are merged to keep from duplicating fields.
     * @param $input
     */
    protected function assembleLeadData ($input = [])
    {
        $default = [
            'full_name'     => 'Name',
            'email_address' => 'Email Address'
        ];

        $this->additionalFields = array_merge($default, $input);
    }

    public function getLeads($args = []){
        $request = [
            'posts_per_page' => - 1,
            'offset'         => 0,
            'post_type'      => $this->uglify($this->postType),
            'post_status'    => 'publish',
        ];

        $args = array_merge( $request, $args );
        $results = get_posts( $args );

        $resultArray = [];
        foreach ( $results as $item ){
            $meta = get_post_meta($item->ID);
            $resultArray[] = [
                'object' => $item,
                'meta'   => $meta
            ];
        }

        return $resultArray;
    }

    /*
     * Sends notification email(s)
     * @param array $leadInfo
     */
    protected function sendNotifications ($leadInfo)
    {
        $emailAddress = (isset($leadInfo['email_address']) ? $leadInfo['email_address'] : null);
        $fullName     = (isset($leadInfo['full_name']) ? $leadInfo['full_name'] : null);

        $tableData = '';
        foreach ($this->additionalFields as $key => $var) {
            if(isset($leadInfo[$key])) {
                $tableData .= '<tr><td class="label"><strong>' . $var . '</strong></td><td>' . $leadInfo[$key] . '</td>';
            }
        }

        $this->sendEmail(
            [
                'to'        => $this->adminEmail,
                'from'      => $this->fromName . ' <'. $this->fromEmail . '>',
                'subject'   => $this->subjectLine,
                'cc'        => $this->ccEmail,
                'bcc'       => $this->bccEmail,
                'replyto'   => $fullName . '<' . $emailAddress . '>',
                'headline'  => $this->emailHeadline,
                'introcopy' => $this->emailText,
                'leadData'  => $tableData
            ]
        );

        $this->sendEmail(
            [
                'to'        => $fullName . '<' . $emailAddress . '>',
                'from'      => $this->fromName . ' <'. $this->fromEmail . '>',
                'subject'   => $this->receiptSubjectLine,
                'bcc'       => $this->bccEmail,
                'headline'  => $this->receiptHeadline,
                'introcopy' => $this->receiptText,
                'leadData'  => $tableData
            ]
        );

    }

    /**
     * Creates a custom post type and meta boxes (now dynamic)
     */
    protected function createPostType ()
    {

        $leads = new CustomPostType(
            $this->postType,
            [
                'supports'           => ['title'],
                'menu_icon'          => 'dashicons-star-empty',
                'has_archive'        => false,
                'menu_position'      => null,
                'public'             => false,
                'publicly_queryable' => false,
            ]
        );

        $fieldArray = [];
        foreach($this->additionalFields as $name => $label){
            $fieldArray[$label] = 'locked';
        }

        $leads->addMetaBox(
            'Lead Info', $fieldArray
        );
    }

    /*
     * Creates columns and data in admin panel
     */
    protected function createAdminColumns ()
    {

        //Adds Column labels. Can be enabled/disabled using screen options.
        add_filter('manage_' . $this->uglify($this->postType) . '_posts_columns', function () {

            $additionalLabels = [];
            foreach($this->additionalFields as $name => $label) {
                if($name != 'first_name' && $name != 'last_name' && $name != 'full_name') {
                    $additionalLabels[$name] = $label;
                }
            }

            $defaults = array_merge(
                [
                    'cb'            => '<input type="checkbox" />',
                    'title'         => 'Name',
                    'email_address' => 'Email',
                    'phone_number'  => 'Phone Number',
                ], $additionalLabels
            );

            $defaults['date'] = 'Date Posted'; //always last

            return $defaults;
        }, 0);

        //Assigns values to columns
        add_action('manage_' . $this->uglify($this->postType) . '_posts_custom_column', function ($column_name, $post_ID) {
            if($column_name != 'title' && $column_name != 'date'){

                $value = get_post_meta($post_ID, 'lead_info_' . $column_name, true);

                switch ($column_name) {
                    case 'email_address':
                        echo isset($value) ? '<a href="mailto:' . $value . '" >' . $value . '</a>' : null;
                        break;

                    case 'phone_number':
                        echo isset($value) ? '<a href="tel:' . $value . '" >' . $value . '</a>' : null;
                        break;

                    default:
                        echo $value;
                }
            }
        }, 0, 2);
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

    /*
     * actually send an email
     * TODO: Add handling for attachments
     */
    public function sendEmail ( $emailData = [] ) {
        $eol           = "\r\n";
        $emailTemplate = $this->createEmailTemplate($emailData);
        $headers       = 'From: ' . $emailData['from'] . $eol;
        $headers       .= (isset($emailData['cc']) ? 'Cc: ' . $emailData['cc'] . $eol : '');
        $headers       .= (isset($emailData['bcc']) ? 'Bcc: ' . $emailData['bcc'] . $eol : '');
        $headers       .= (isset($emailData['replyto']) ? 'Reply-To: ' . $emailData['replyto'] . $eol : '');
        $headers       .= 'MIME-Version: 1.0' . $eol;
        $headers       .= 'Content-type: text/html; charset=utf-8' . $eol;

        wp_mail($emailData['to'], $emailData['subject'], $emailTemplate, $headers);
    }
}
