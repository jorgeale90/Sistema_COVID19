<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalRepository")
 * @UniqueEntity(fields={"ci"},message="Ya existe este Personal.")
 * @Vich\Uploadable
 * @Audit\Auditable
 */

class Personal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="numero", type="string",  nullable=false, length=80)
     */
    private $numero;

    /**
     * @var string
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @ORM\Column(name="ci", type="string",  nullable=true, length=80, unique=true)
     */
    private $ci;

    /**
     * @var string
     * @Assert\Regex(pattern="/\d/", match=false, message="Debe contener solo letras")
     * @ORM\Column(name="nombre", type="string", nullable=false, length=80)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Length(min=2, max=80, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @var string
     * @Assert\Regex(pattern="/\d/", match=false, message="Debe contener solo letras")
     * @ORM\Column(name="apellidos", type="string", nullable=false, length=80)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Length(min=2, max=80, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="personal_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @ORM\Column(name="edad", type="string",  nullable=true, length=80)
     * @Assert\Length(min=1, max=3, minMessage="Debe contener al menos {{ limit }} dígito", maxMessage="Debe contener a lo sumo {{ limit }} dígitos")
     */
    private $edad;

    /**
     * @ORM\ManyToOne(targetEntity = "Sexo", inversedBy = "personal")
     * @ORM\JoinColumn(name="sexo_id", referencedColumnName="id", onDelete = "CASCADE", nullable=false)
     * @Assert\NotBlank(message="Debe seleccionar un Sexo")
     */
    protected $sexo;

    /**
     * @var string
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @ORM\Column(name="hc", type="string",  nullable=true, length=80)
     * @Assert\Length(min=1, max=11, minMessage="Debe contener al menos {{ limit }} dígito", maxMessage="Debe contener a lo sumo {{ limit }} dígitos")
     */
    private $hc;

    /**
     * @ORM\ManyToOne(targetEntity = "AreaSalud", inversedBy = "personal")
     * @ORM\JoinColumn(name="areasalud_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $areasalud;

    /**
     * @ORM\ManyToOne(targetEntity = "Municipio", inversedBy = "personal")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar un Municipio")
     */
    protected $municipio;

    /**
     * @ORM\ManyToOne(targetEntity = "Provincia", inversedBy = "personal")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Provincia")
     */
    protected $provincia;

    /**
     * @ORM\ManyToOne(targetEntity = "CategoriaViajero", inversedBy = "personal")
     * @ORM\JoinColumn(name="categoriaviajero_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $categoriaviajero;

    /**
     * @ORM\ManyToOne(targetEntity = "Pais", inversedBy = "personalprecedencia")
     * @ORM\JoinColumn(name="paisprocedencia_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $paisprocedencia;

    /**
     * @ORM\ManyToOne(targetEntity = "Nacionalidad", inversedBy = "personal")
     * @ORM\JoinColumn(name="nacionalidad_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $nacionalidad;

    /**
     * @ORM\Column(name="fechaentrada", type="date",  nullable=true)
     */
    private $fechaentrada;

    /**
     * @ORM\ManyToOne(targetEntity = "Provincia", inversedBy = "personalprovinciaentrada")
     * @ORM\JoinColumn(name="provinciaentrada_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $provinciaentrada;

    /**
     * @ORM\Column(type = "text", nullable=true)
     * @Assert\Length(min=1, max=1000, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $observaciones;

    /**
     * @ORM\Column(type = "text", nullable=true)
     * @Assert\Length(min=1, max=1000, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $direccioncarnet;

    /**
     * @ORM\ManyToOne(targetEntity = "ConsejoPopular", inversedBy = "personal")
     * @ORM\JoinColumn(name="consejopopular_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $consejopopular;

    /**
     * @ORM\Column(name="fis", type="date",  nullable=true)
     */
    private $fis;

    /**
     * @ORM\Column(name="fechaconsulta", type="date",  nullable=true)
     */
    private $fechaconsulta;

    /**
     * @ORM\ManyToMany(targetEntity="SintomasIngreso", inversedBy="personales")
     * @ORM\JoinTable(name="sintomasingreso_personal",
     *           joinColumns={@ORM\JoinColumn(name="personal_id", referencedColumnName="id")},
     *           inverseJoinColumns={@ORM\JoinColumn(name="sintomasingreso_id",referencedColumnName="id")})
     * @Assert\Count(min=1, max=30, minMessage="Debe seleccionar al menos {{ limit }} Síntomas", maxMessage="Debe  seleccionar a lo sumo {{ limit }} Síntomas")*
     */
    protected $sintomaingreso;

    /**
     * @ORM\Column(name="fechaingreso", type="date",  nullable=true)
     */
    private $fechaingreso;

    /**
     * @ORM\ManyToOne(targetEntity = "EstadoIngreso", inversedBy = "personal")
     * @ORM\JoinColumn(name="estadoingreso_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    protected $estadoingreso;

    /**
     * @ORM\ManyToOne(targetEntity = "HospitalIngreso", inversedBy = "personal")
     * @ORM\JoinColumn(name="hospitalingreso_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $hospitalingreso;

    /**
     * @ORM\ManyToOne(targetEntity = "CategoriaPaciente", inversedBy = "personal")
     * @ORM\JoinColumn(name="categoriapaciente_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $categoriapaciente;

    /**
     * @ORM\Column(name="fechatomamuestra", type="date",  nullable=true)
     */
    private $fechatomamuestra;

    /**
     * @ORM\Column(name="fechaenviomuestra", type="date",  nullable=true)
     */
    private $fechaenviomuestra;

    /**
     * @ORM\Column(name="fecharesultado", type="date",  nullable=true)
     */
    private $fecharesultado;

    /**
     * @ORM\ManyToOne(targetEntity = "Resultado", inversedBy = "personal")
     * @ORM\JoinColumn(name="resultado_id", referencedColumnName="id", onDelete = "CASCADE", nullable=true)
     */
    protected $resultado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @ORM\Column(name="fechaalta", type="date",  nullable=true)
     */
    private $fechaalta;

    /**
     * @ORM\ManyToOne(targetEntity = "TipoMuestra", inversedBy = "personal")
     * @ORM\JoinColumn(name="tipomuestra_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    protected $tipomuestra;

    /**
     * @ORM\ManyToOne(targetEntity = "Provincia", inversedBy = "personalprocedenmuestra")
     * @ORM\JoinColumn(name="provinciaprocedenmuestra_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    protected $provinciaprocedenmuestra;

    /**
     * @ORM\ManyToOne(targetEntity = "AreaSalud", inversedBy = "personalcentromuestra")
     * @ORM\JoinColumn(name="centroprocemuestra_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    protected $centroprocemuestra;

    /**
     * @var string
     * @ORM\Column(name="color_piel", type="string", nullable=true, length=30)
     * @Assert\Choice(choices={"Blanca","Mestiza","Negra","Amarilla"},  message="Debe seleccionar una Opción")
     */
    protected $color_piel;

    /**
     * @var string
     * @ORM\Column(name="tiempo", type="string", nullable=true, length=30)
     * @Assert\Choice(choices={"día(s)","mes(es)","año(s)"},  message="Debe seleccionar una Opción")
     */
    protected $tiempo;

    public function __construct()
    {
        $this->sintomaingreso = new ArrayCollection();
    }

    public function getNombreCompleto() {

        return $this->getNombre() . " " . $this->getApellidos();

    }

    public function __toString() {

        return $this->getNombreCompleto();

    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCi(): ?string
    {
        return $this->ci;
    }

    public function setCi(?string $ci): self
    {
        $this->ci = $ci;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEdad(): ?string
    {
        return $this->edad;
    }

    public function setEdad(?string $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getHc(): ?string
    {
        return $this->hc;
    }

    public function setHc(?string $hc): self
    {
        $this->hc = $hc;

        return $this;
    }

    public function getFechaentrada(): ?\DateTimeInterface
    {
        return $this->fechaentrada;
    }

    public function setFechaentrada(?\DateTimeInterface $fechaentrada): self
    {
        $this->fechaentrada = $fechaentrada;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getDireccioncarnet(): ?string
    {
        return $this->direccioncarnet;
    }

    public function setDireccioncarnet(?string $direccioncarnet): self
    {
        $this->direccioncarnet = $direccioncarnet;

        return $this;
    }

    public function getFis(): ?\DateTimeInterface
    {
        return $this->fis;
    }

    public function setFis(?\DateTimeInterface $fis): self
    {
        $this->fis = $fis;

        return $this;
    }

    public function getFechaconsulta(): ?\DateTimeInterface
    {
        return $this->fechaconsulta;
    }

    public function setFechaconsulta(?\DateTimeInterface $fechaconsulta): self
    {
        $this->fechaconsulta = $fechaconsulta;

        return $this;
    }

    public function getFechaingreso(): ?\DateTimeInterface
    {
        return $this->fechaingreso;
    }

    public function setFechaingreso(?\DateTimeInterface $fechaingreso): self
    {
        $this->fechaingreso = $fechaingreso;

        return $this;
    }

    public function getFechatomamuestra(): ?\DateTimeInterface
    {
        return $this->fechatomamuestra;
    }

    public function setFechatomamuestra(?\DateTimeInterface $fechatomamuestra): self
    {
        $this->fechatomamuestra = $fechatomamuestra;

        return $this;
    }

    public function getFechaenviomuestra(): ?\DateTimeInterface
    {
        return $this->fechaenviomuestra;
    }

    public function setFechaenviomuestra(?\DateTimeInterface $fechaenviomuestra): self
    {
        $this->fechaenviomuestra = $fechaenviomuestra;

        return $this;
    }

    public function getFecharesultado(): ?\DateTimeInterface
    {
        return $this->fecharesultado;
    }

    public function setFecharesultado(?\DateTimeInterface $fecharesultado): self
    {
        $this->fecharesultado = $fecharesultado;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getFechaalta(): ?\DateTimeInterface
    {
        return $this->fechaalta;
    }

    public function setFechaalta(?\DateTimeInterface $fechaalta): self
    {
        $this->fechaalta = $fechaalta;

        return $this;
    }

    public function getColorPiel(): ?string
    {
        return $this->color_piel;
    }

    public function setColorPiel(?string $color_piel): self
    {
        $this->color_piel = $color_piel;

        return $this;
    }

    public function getSexo(): ?Sexo
    {
        return $this->sexo;
    }

    public function setSexo(?Sexo $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getAreasalud(): ?AreaSalud
    {
        return $this->areasalud;
    }

    public function setAreasalud(?AreaSalud $areasalud): self
    {
        $this->areasalud = $areasalud;

        return $this;
    }

    public function getMunicipio(): ?Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(?Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincia $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getCategoriaviajero(): ?CategoriaViajero
    {
        return $this->categoriaviajero;
    }

    public function setCategoriaviajero(?CategoriaViajero $categoriaviajero): self
    {
        $this->categoriaviajero = $categoriaviajero;

        return $this;
    }

    public function getPaisprocedencia(): ?Pais
    {
        return $this->paisprocedencia;
    }

    public function setPaisprocedencia(?Pais $paisprocedencia): self
    {
        $this->paisprocedencia = $paisprocedencia;

        return $this;
    }

    public function getNacionalidad(): ?Nacionalidad
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(?Nacionalidad $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getProvinciaentrada(): ?Provincia
    {
        return $this->provinciaentrada;
    }

    public function setProvinciaentrada(?Provincia $provinciaentrada): self
    {
        $this->provinciaentrada = $provinciaentrada;

        return $this;
    }

    public function getConsejopopular(): ?ConsejoPopular
    {
        return $this->consejopopular;
    }

    public function setConsejopopular(?ConsejoPopular $consejopopular): self
    {
        $this->consejopopular = $consejopopular;

        return $this;
    }

    /**
     * @return Collection|SintomasIngreso[]
     */
    public function getSintomaingreso(): Collection
    {
        return $this->sintomaingreso;
    }

    public function addSintomaingreso(SintomasIngreso $sintomaingreso): self
    {
        if (!$this->sintomaingreso->contains($sintomaingreso)) {
            $this->sintomaingreso[] = $sintomaingreso;
        }

        return $this;
    }

    public function removeSintomaingreso(SintomasIngreso $sintomaingreso): self
    {
        if ($this->sintomaingreso->contains($sintomaingreso)) {
            $this->sintomaingreso->removeElement($sintomaingreso);
        }

        return $this;
    }

    public function getEstadoingreso(): ?EstadoIngreso
    {
        return $this->estadoingreso;
    }

    public function setEstadoingreso(?EstadoIngreso $estadoingreso): self
    {
        $this->estadoingreso = $estadoingreso;

        return $this;
    }

    public function getHospitalingreso(): ?HospitalIngreso
    {
        return $this->hospitalingreso;
    }

    public function setHospitalingreso(?HospitalIngreso $hospitalingreso): self
    {
        $this->hospitalingreso = $hospitalingreso;

        return $this;
    }

    public function getCategoriapaciente(): ?CategoriaPaciente
    {
        return $this->categoriapaciente;
    }

    public function setCategoriapaciente(?CategoriaPaciente $categoriapaciente): self
    {
        $this->categoriapaciente = $categoriapaciente;

        return $this;
    }

    public function getResultado(): ?Resultado
    {
        return $this->resultado;
    }

    public function setResultado(?Resultado $resultado): self
    {
        $this->resultado = $resultado;

        return $this;
    }

    public function getTipomuestra(): ?TipoMuestra
    {
        return $this->tipomuestra;
    }

    public function setTipomuestra(?TipoMuestra $tipomuestra): self
    {
        $this->tipomuestra = $tipomuestra;

        return $this;
    }

    public function getProvinciaprocedenmuestra(): ?Provincia
    {
        return $this->provinciaprocedenmuestra;
    }

    public function setProvinciaprocedenmuestra(?Provincia $provinciaprocedenmuestra): self
    {
        $this->provinciaprocedenmuestra = $provinciaprocedenmuestra;

        return $this;
    }

    public function getCentroprocemuestra(): ?AreaSalud
    {
        return $this->centroprocemuestra;
    }

    public function setCentroprocemuestra(?AreaSalud $centroprocemuestra): self
    {
        $this->centroprocemuestra = $centroprocemuestra;

        return $this;
    }

    public function getTiempo(): ?string
    {
        return $this->tiempo;
    }

    public function setTiempo(?string $tiempo): self
    {
        $this->tiempo = $tiempo;

        return $this;
    }
}
