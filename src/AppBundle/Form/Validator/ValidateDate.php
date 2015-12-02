<?php
namespace AppBundle\Form\Validator;

use Symfony\Component\Validator\Constraint;

class UniqueEventDate extends Constraint
{
    public function validatedBy()
    {
        return 'unique_event_date';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

