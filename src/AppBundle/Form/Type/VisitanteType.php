<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
class VisitanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('grupoRel', EntityType::class, array(
                'class' => 'AppBundle:Grupo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('g')
                    ->orderBy('g.codigoGrupoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                              
            ->add('numeroIdentificacion', TextType::class, array('required' => true))             
            ->add('nombre', TextType::class, array('required' => true)) 
            ->add('codigo', TextType::class, array('required' => true))                             
            ->add('fechaArl', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                            
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
                    
    }

    public function getName()
    {
        return 'form';
    }
}

