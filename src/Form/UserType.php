<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = array(
            'Usuario'        => 'ROLE_USER',
            'Administrador'     => 'ROLE_ADMIN',
            'Super Admin'     => 'ROLE_SUPER_ADMIN'
        );

        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('password')
            ->add('roles', ChoiceType::class, array(
                'label'   => 'Role',
                'choices' => $permissions,
                'multiple' => true,
                #'expanded' => true
            ))
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
