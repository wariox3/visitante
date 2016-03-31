<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('clienteRel', EntityType::class, array(
                'class' => 'AppBundle:Cliente',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.codigoClientePk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                  
            ->add('username', TextType::class, array('required' => true)) 
            ->add('password', TextType::class, array('required' => true)) 
            ->add('email', TextType::class, array('required' => true)) 
            ->add('guardar', SubmitType::class);
                    
    }

    public function getName()
    {
        return 'form';
    }
}

