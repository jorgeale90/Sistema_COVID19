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
            'institucion' => $this->em->getRepository('App:Institucion')->findAll(),
            'cargo' => $this->em->getRepository('App:Cargo')->findAll(),
            'especialidad' => $this->em->getRepository('App:Especialidad')->findAll(),
            'politica' => $this->em->getRepository('App:OrganizacionPolitica')->findAll(),
            'docente' => $this->em->getRepository('App:CategoriaDocente')->findAll(),
            'cientifica' => $this->em->getRepository('App:CategoriaCientifica')->findAll(),
            'contable' => $this->em->getRepository('App:SistemaContable')->findAll(),
            'modulo' => $this->em->getRepository('App:SistemaModulo')->findAll(),
            'marca' => $this->em->getRepository('App:Marca')->findAll(),
            'modelo' => $this->em->getRepository('App:Modelo')->findAll(),
            'tipomedio' => $this->em->getRepository('App:TipoMedio')->findAll(),
            'contratocorreo' => $this->em->getRepository('App:ContratoCorreo')->findAll(),
            'contratoanclaje' => $this->em->getRepository('App:ContratoAnclaje')->findAll(),
            'contratointernet' => $this->em->getRepository('App:ContratoInternet')->findAll(),
            'contratotecnologia' => $this->em->getRepository('App:MedioTecnologico')->findAll()
        );
    }

    public function getName()
    {
        return 'app_extension';
    }
}