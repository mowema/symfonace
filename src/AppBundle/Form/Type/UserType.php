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
            ->add('isactive','checkbox',array(
                'required' => false,
                'label'  => 'Estado del usuario',
                //'attr'=>array('class'=>'well')
                ))
            ->add('username','text',array(
                'label'=>'Usuario'
                ))
            ->add('fname','text',array(
                'label'=>'Nombre',
                'required' => false
                ))
            ->add('lname','text',array(
                'label'=>'Apellido',
                'required' => false
                ));
            if($this->params['mode'] != 'edit') {
            $builder->add('password','password',array(
                'label'=>'Contraseña',
                'required'=> true,
                'attr'=>array(
                    'data-toggle'=>"password",
                    'help' => false,
                    ),
                //'disabled'=>true
                )
                //data-toggle="password" autocomplete="off"
            ); 
            
            }
            $builder->add('email','email',array(
                'label'=>'Correo electrónico',
                ))
            //->add('salt') //No necesitamos que salt sea mostrado ---------------
            
            ->add('user_roles', 'entity', array(
                'class' => 'AppBundle:Role',
                'multiple' => true, 
                'choice_label' => 'name',
                'attr'=>array('help' => 'Puede seleccionar mas de un rol, tenga presionada la tecla <control> ',
                    ),
            ))
        
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