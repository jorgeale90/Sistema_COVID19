<?php

namespace App\Twig;
use Doctrine\ORM\EntityManagerInterface;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGlobals()
    {
        return array(
            'usuario' => $this->em->getRepository('App:User')->findAll(),
            'personales' => $this->em->getRepository('App:Personal')->findAll(),
            'sexo' => $this->em->getRepository('App:Sexo')->findAll(),
            'pais' => $this->em->getRepository('App:Pais')->findAll(),
            'provincia' => $this->em->getRepository('App:Provincia')->findAll(),
            'municipio' => $this->em->getRepository('App:Municipio')->findAll(),
            'nacionalidad' => $this->em->getRepository('App:Nacionalidad')->findAll(),
            'hospital' => $this->em->getRepository('App:HospitalIngreso')->findAll(),
            'areasalud' => $this->em->getRepository('App:AreaSalud')->findAll(),
            'categoriaviajero' => $this->em->getRepository('App:CategoriaViajero')->findAll(),
            'categoriapaciente' => $this->em->getRepository('App:CategoriaPaciente')->findAll(),
            'consejopopular' => $this->em->getRepository('App:ConsejoPopular')->findAll(),
            'estadoingreso' => $this->em->getRepository('App:EstadoIngreso')->findAll(),
            'sintomasingreso' => $this->em->getRepository('App:SintomasIngreso')->findAll(),
            'resultado' => $this->em->getRepository('App:Resultado')->findAll(),
            'tipomuestra' => $this->em->getRepository('App:TipoMuestra')->findAll()
        );
    }

    public function getName()
    {
        return 'app_extension';
    }
}