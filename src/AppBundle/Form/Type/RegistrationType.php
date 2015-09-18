<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UserType());
        $builder->add(
            'terminos',
            'checkbox',
            array('property_path' => 'termsAccepted')
        );
        $builder->add('Registrar', 'submit');
    }

    public function getName()
    {
        return 'registro';
    }
}