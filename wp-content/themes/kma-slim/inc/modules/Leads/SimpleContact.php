<?php

namespace Includes\Modules\Leads;

class SimpleContact extends Leads
{
    public function __construct ()
    {
        parent::__construct ();
        parent::assembleLeadData(
            [
                'message' => 'Message'
            ]
        );
    }

}