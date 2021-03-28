<?php

namespace App\Form;

use App\Entity\Personal;
use FOS\CKEditorBundle\FOSCKEditorBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PersonalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class, array(
                'attr' => array('class' => 'validate[required] form-control')
            ))

            ->add('ci', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))

            ->add('nombre', TextType::class, array(
                'attr' => array('class' => 'validate[required] form-control')
            ))

            ->add('apellidos', TextType::class, array(
                'attr' => array('class' => 'validate[required] form-control')
            ))

            ->add('edad')

            ->add('sexo', EntityType::class, array(
                'label' => 'Sexo:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Sexo',
                'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('hc')

            ->add('provincia', EntityType::class, array(
                'label' => 'Provincia:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Provincia',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('categoriaviajero', EntityType::class, array(
                'required' => false,
                'label' => 'Categoría de Viajero:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\CategoriaViajero',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('paisprocedencia', EntityType::class, array(
                'required' => false,
                'label' => 'País de Procedencia:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Pais',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('nacionalidad', EntityType::class, array(
                'required' => false,
                'label' => 'Nacionalidad:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Nacionalidad',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('fechaentrada', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'empty_data' => '',
                'attr' => array('class' => 'form-control datepicker')
            ))

            ->add('provinciaentrada', EntityType::class, array(
                'required' => false,
                'label' => 'Provincia de Entrada:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Provincia',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('observaciones',CKEditorType::class)

            ->add('direccioncarnet',CKEditorType::class)

            ->add('fis', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'form-control datepicker login-input')
            ))

            ->add('fechaconsulta', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'form-control datepicker login-input')
            ))

            ->add('sintomaingreso')

            ->add('fechaingreso', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'form-control datepicker login-input')
            ))

            ->add('estadoingreso', EntityType::class, array(
                'required' => false,
                'label' => 'Estado Ingreso:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\EstadoIngreso',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('fechaalta', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'empty_data' => '',
                'attr' => array('class' => 'form-control datepicker')
            ))

            ->add('hospitalingreso', EntityType::class, array(
                'required' => false,
                'label' => 'Hospital de Ingreso:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\HospitalIngreso',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('categoriapaciente', EntityType::class, array(
                'required' => false,
                'label' => 'Categoría del Paciente:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\CategoriaPaciente',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('fechatomamuestra', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'form-control datepicker login-input')
            ))

            ->add('fechaenviomuestra', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'form-control datepicker login-input')
            ))

            ->add('fecharesultado', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'form-control datepicker login-input')
            ))

            ->add('resultado', EntityType::class, array(
                'required' => false,
                'label' => 'Resultado:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Resultado',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('tipomuestra', EntityType::class, array(
                'required' => true,
                'label' => 'Tipo de Muestra:',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\TipoMuestra',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('provinciaprocedenmuestra', EntityType::class, array(
                'required' => true,
                'label' => 'Provincia de Procedencia de la Muestra :',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\Provincia',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('centroprocemuestra', EntityType::class, array(
                'required' => true,
                'label' => 'Centro de Procedencia de la Muestra :',
                'placeholder' => 'Seleccione una opción',
                'class' => 'App\Entity\AreaSalud',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;')
            ))

            ->add('color_piel', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Seleccione una opción',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;'),
                'choices' => [
                    'Blanca' => 'Blanca',
                    'Mestiza' => 'Mestiza',
                    'Negra' => 'Negra',
                    'Amarilla' => 'Amarilla',
                ],
            ])

            ->add('tiempo', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Seleccione una opción',
                'attr' => array('class' => 'form-control select2', 'style' => 'width: 100%;'),
                'choices' => [
                    'día(s)' => 'día(s)',
                    'mes(es)' => 'mes(es)',
                    'año(s)' => 'año(s)',
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_label' => 'Descargar',
                'download_uri' => true,
                'image_uri' => true,]);

        if (true === $options['editar']) {
            $builder
                ->add('municipio', EntityType::class, array(
                    'label' => 'Municipio:',
                    'placeholder' => 'Seleccione una opción',
                    'class' => 'App\Entity\Municipio',
                    'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;', 'required' => 'true')
                ));
        }
        else
        {
            $builder
                ->add('municipio', EntityType::class, array(
                    'label' => 'Municipio:',
                    'placeholder' => 'Seleccione una opción',
                    'class' => 'App\Entity\Municipio',
                    'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;', 'required' => 'true', 'disabled' => 'true')
                ));
        }

        if (true === $options['editar']) {
            $builder
                ->add('areasalud', EntityType::class, array(
                    'required' => 'false',
                    'label' => 'Area de Salud:',
                    'placeholder' => 'Seleccione una opción',
                    'class' => 'App\Entity\AreaSalud',
                    'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;', 'required' => 'false')
                ));
        }
        else
        {
            $builder
                ->add('areasalud', EntityType::class, array(
                    'required' => 'false',
                    'label' => 'Area de Salud:',
                    'placeholder' => 'Seleccione una opción',
                    'class' => 'App\Entity\AreaSalud',
                    'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;', 'required' => 'false', 'disabled' => 'true')
                ));
        }

        if (true === $options['editar']) {
            $builder
                ->add('consejopopular', EntityType::class, array(
                    'required' => 'false',
                    'label' => 'Consejo Popular:',
                    'placeholder' => 'Seleccione una opción',
                    'class' => 'App\Entity\ConsejoPopular',
                    'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;', 'required' => 'false')
                ));
        }
        else
        {
            $builder
                ->add('consejopopular', EntityType::class, array(
                    'required' => 'false',
                    'label' => 'Consejo Popular:',
                    'placeholder' => 'Seleccione una opción',
                    'class' => 'App\Entity\ConsejoPopular',
                    'attr' => array('class' => 'validate[required] form-control select2', 'style' => 'width: 100%;', 'required' => 'false', 'disabled' => 'true')
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personal::class,
            'editar' => false
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