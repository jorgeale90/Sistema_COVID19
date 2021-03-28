<?php

namespace App\Form;

use App\Entity\Personal;
use FOS\CKEditorBundle\FOSCKEditorBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class PersonalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ci')
            ->add('noregistro')
            ->add('nombre')
            ->add('apellidos')
            ->add('direccionparticular',CKEditorType::class)
            ->add('telefonofijo')
            ->add('movil')
            ->add('email')
            ->add('autobliografia',CKEditorType::class)
            ->add('organizacionpolitica')
            ->add('sexo', EntityType::class, array(
                'label' => 'Sexo:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Sexo',
                'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;')
            ))
            ->add('cargo', EntityType::class, array(
                'label' => 'Cargo:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Cargo',
                'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;')
            ))
            ->add('nacionalidad', EntityType::class, array(
                'label' => 'Nacionalidad:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Nacionalidad',
                'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;')
            ))
            ->add('especialidad', EntityType::class, array(
                'label' => 'Especialidad:',
                'placeholder' => 'Seleccione un Cargo primero',
                'class' => 'App\Entity\Especialidad',
                'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;')
            ))
            ->add('categoriadocente')
            ->add('categoriacientifica')
            ->add('mision', ChoiceType::class, [
                'placeholder' => 'Seleccione una opción',
                'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;'),
                'choices'  => [
                    'SI' => 'Si',
                    'NO' => 'No',
                    'NONE' => 'None',
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_label' => 'Descargar',
                'download_uri' => true,
                'image_uri' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personal::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_personal';
    }
}