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
                ->add('email', 'text',array('label'  => 'Email','required'  => false))
                ->add('fname', 'text',array('label'  => 'Nombre','required'  => false))
                ->add('lname', 'text',array('label'  => 'Apellido','required'  => false))
                ->add('submit', 'submit',array('label'  => 'Buscar'))
                ;
    }

    public function getName()
    {
        return 'filter';
    }
}