<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    protected $params;

    public function __construct ($params = null)
    {
        $this->params = $params;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id','hidden')
            
            ->add('password','password',array(
                'label'=>'Contraseña',
                'required'=> $this->params['mode'] == 'edit'?false:true,
                'attr'=>array('data-toggle'=>"password",
                    'help' => $this->params['mode'] == 'edit'? 'Complete si quiere generar una contraseña nueva ':false,
                    ),
                //'disabled'=>true
                )
                //data-toggle="password" autocomplete="off"
                
            )
            ->add(
                'sendmail',
                'checkbox',
                array('property_path' => 'sendMail')
            )
            
            //->add('salt') //No necesitamos que salt sea mostrado ---------------
            
            
        
            ->add('submit', 'submit', array('label' => 'Guardar'))
            ->add('submitundnew', 'submit',array('label'  => 'Guardar y Crear nuevo',
                'attr'=>array('class'=>'btn-warning')));
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