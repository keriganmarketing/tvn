<?php

namespace Includes\Modules\Leads;

use Includes\Modules\Agents\Agents;

class HomeValuation extends Leads
{
    public function __construct ()
    {
        parent::__construct ();
        parent::set('postType','Home Valuation');
        parent::assembleLeadData(
            [
                'phone_number'       => 'Phone Number',
                'selected_agent'     => 'Selected Agent',
                'property_address'   => 'Property Address',
                'property_type'      => 'Property Type',
                'property_details'   => 'Property Details'
            ]
        );
    }

    public function handleLead ($dataSubmitted = [])
    {
        $dataSubmitted['full_name'] = (isset($dataSubmitted['full_name']) ? $dataSubmitted['full_name'] :
            (isset($dataSubmitted['first_name']) ? $dataSubmitted['first_name'] . ' ' . $dataSubmitted['last_name'] : '')
        );

        $dataSubmitted['property_address'] = parent::toFullAddress(
            $dataSubmitted['listing_address'], $dataSubmitted['listing_address_2'],
            $dataSubmitted['listing_city'], $dataSubmitted['listing_state'], $dataSubmitted['listing_zip']
        );

        $agent = new Agents();
        $agentInfo = $agent->assembleAgentData($dataSubmitted['selected_agent']);
        parent::set('adminEmail', ($agentInfo['email_address'] != '' ? $agentInfo['email_address'] : $this->adminEmail));

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