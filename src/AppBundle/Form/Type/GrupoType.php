<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class GrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                       
            ->add('nit', TextType::class, array('required' => true)) 
            ->add('nombre', TextType::class, array('required' => true)) 
            ->add('direccion', TextType::class, array('required' => true)) 
            ->add('telefono', TextType::class, array('required' => true)) 
            ->add('fechaArl', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                            
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
                    
    }

    public function getName()
    {
        return 'form';
    }
}

