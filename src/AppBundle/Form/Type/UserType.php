<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text',array(
                'label'=>'Usuario'
                ))
            ->add('fname','text',array(
                'label'=>'Nombre'
                ))
            ->add('lname','text',array(
                'label'=>'Apellido',
                ))
            ->add('password','password',array(
                'label'=>'Contraseña',
                'empty_data'=>false,
                'attr'=>array('data-toggle'=>"password")
                //'disabled'=>true
                )
                //data-toggle="password" autocomplete="off"
            )
            ->add('email','email',array(
                'label'=>'Correo electrónico',
                ))
            //->add('salt') //No necesitamos que salt sea mostrado ---------------
            
            ->add('user_roles', 'entity', array(
                'class' => 'AppBundle:Role',
                'multiple' => true, 
                'choice_label' => 'name',
            ))
        
            ->add('submit', 'submit', array('label' => 'Guardar'));
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}