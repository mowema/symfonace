<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email','text',array(
                'label'=>'Correo electrónico',
                
                ))
            
            ->add('submit', 'submit', array('label' => 'Buscar'));
        
    }


    public function getName()
    {
        return 'user';
    }
}