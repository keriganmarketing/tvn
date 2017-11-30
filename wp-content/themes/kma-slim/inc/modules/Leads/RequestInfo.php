<?php

namespace Includes\Modules\Leads;

use Includes\Modules\Agents\Agents;

class RequestInfo extends Leads
{
    public function __construct ()
    {
        parent::__construct ();
        parent::assembleLeadData(
            [
                'phone_number'       => 'Phone Number',
                'reason_for_contact' => 'Reason for Contact',
                'selected_agent'     => 'Selected Agent',
                'mls_number'         => 'MLS Number',
                'message'            => 'Message'
            ]
        );
    }

    public function handleLead ($dataSubmitted = [])
    {
        $dataSubmitted['full_name'] = (isset($dataSubmitted['full_name']) ? $dataSubmitted['full_name'] :
            (isset($dataSubmitted['first_name']) ? $dataSubmitted['first_name'] . ' ' . $dataSubmitted['last_name'] : '')
        );

        $agent = new Agents();
        $agentInfo = $agent->assembleAgentData($dataSubmitted['selected_agent']);
        parent::set('adminEmail', (isset($agentInfo['email_address']) && $agentInfo['email_address'] != '' ? $agentInfo['email_address'] : $this->adminEmail));

        //parent::set($this->adminEmail,'bbaird85@gmail.com'); //temp
        parent::addToDashboard($dataSubmitted);
        if(parent::validateSubmission($dataSubmitted)){
            echo '<div class="alert alert-success" role="alert">
            <strong>Your request has been received. We will review your submission and get back with you soon.</strong>
            </div>';
        }else{
            echo '<div class="alert alert-danger" role="alert">
            <strong>Errors were found. Please correct the indicated fields below.</strong>
            </div>';
            return;
        }
        parent::sendNotifications($dataSubmitted);

    }


}