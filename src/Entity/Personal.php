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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @ORM\Column(name="ci", type="string",  nullable=false, length=80, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Length(min=11, max=11, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $ci;

    /**
     * @var string
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @ORM\Column(name="noregistro", type="string",  nullable=false, length=80)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Length(min=2, max=30, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $noregistro;

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
     * @ORM\Column(type = "text", nullable=true)
     * @Assert\Length(min=1, max=1000, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $direccionparticular;

    /**
     * @var integer
     * @ORM\Column(name="telefonofijo", type="integer",  nullable=true)
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @Assert\Length(min=6, max=10, minMessage="Debe contener al menos {{ limit }} números", maxMessage="Debe contener a lo sumo {{ limit }} números")
     */
    private $telefonofijo;

    /**
     * @var integer
     * @ORM\Column(name="movil", type="integer",  nullable=true)
     * @Assert\Regex(pattern="/\w/", match=true, message="Debe contener solo números")
     * @Assert\Length(min=8, max=8, minMessage="Debe contener al menos {{ limit }} números", maxMessage="Debe contener a lo sumo {{ limit }} números")
     */
    private $movil;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type = "text", nullable=true)
     * @Assert\Length(min=1, max=1000, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $autobliografia;

    /**
     * @ORM\ManyToMany(targetEntity="OrganizacionPolitica")
     * @ORM\JoinTable(name="organizacionp_personal",
     *           joinColumns={@ORM\JoinColumn(name="personal_id", referencedColumnName="id")},
     *           inverseJoinColumns={@ORM\JoinColumn(name="organizacionp_id",referencedColumnName="id")})
     * @Assert\Count(min=1, max=10, minMessage="Debe seleccionar al menos {{ limit }} Organización", maxMessage="Debe  seleccionar a lo sumo {{ limit }} Organizaciones")*
     */
    protected $organizacionpolitica;

    /**
     * @ORM\ManyToOne(targetEntity = "Sexo", inversedBy = "personal")
     * @ORM\JoinColumn(name="sexo_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar un Sexo")
     */
    protected $sexo;

    /**
     * @ORM\ManyToOne(targetEntity = "Cargo", inversedBy = "personal")
     * @ORM\JoinColumn(name="cargo_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar un Cargo")
     */
    protected $cargo;

    /**
     * @ORM\ManyToOne(targetEntity = "Nacionalidad", inversedBy = "personal")
     * @ORM\JoinColumn(name="nacionalidad_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Nacionalidad")
     */
    protected $nacionalidad;

    /**
     * @ORM\ManyToOne(targetEntity = "Especialidad", inversedBy = "personal")
     * @ORM\JoinColumn(name="especialidad_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Especialidad")
     */
    protected $especialidad;

    /**
     * @ORM\ManyToOne(targetEntity = "CategoriaDocente", inversedBy = "personal")
     * @ORM\JoinColumn(name="categoriadocente_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Categoría Docente")
     */
    protected $categoriadocente;

    /**
     * @ORM\ManyToOne(targetEntity = "CategoriaCientifica", inversedBy = "personal")
     * @ORM\JoinColumn(name="categoriacientifica_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Categoría Cientifica")
     */
    protected $categoriacientifica;

    /**
     * @var string $mision
     * @ORM\Column(name="mision", type="string", nullable=false, length=30)
     * @Assert\Choice(choices={"Si","No","None"},  message="Debe seleccionar una Opción")
     */
    protected $mision;

    /**
     * @ORM\OneToMany(targetEntity="SistemaContable", mappedBy="personal")
     */
    protected $sistemacontable;

    /**
     * @ORM\OneToMany(targetEntity="MedioTecnologico", mappedBy="personal1")
     */
    protected $mediotecnologico1;

    /**
     * @ORM\OneToMany(targetEntity="MedioTecnologico", mappedBy="personal2")
     */
    protected $mediotecnologico2;

    /**
     * @ORM\OneToMany(targetEntity="ContratoCorreo", mappedBy="personal1")
     */
    protected $contratocorreo1;

    /**
     * @ORM\OneToMany(targetEntity="ContratoCorreo", mappedBy="personal2")
     */
    protected $contratocorreo2;

    /**
     * @ORM\OneToMany(targetEntity="ContratoAnclaje", mappedBy="personal")
     */
    protected $contratoanclaje;

    /**
     * @ORM\OneToMany(targetEntity="ContratoAnclaje", mappedBy="personal2")
     */
    protected $contratoanclaje2;

    /**
     * @ORM\OneToMany(targetEntity="ContratoInternet", mappedBy="personal1")
     */
    protected $contratointernet1;

    /**
     * @ORM\OneToMany(targetEntity="ContratoInternet", mappedBy="personal2")
     */
    protected $contratointernet2;

    public function __construct()
    {
        $this->organizacionpolitica = new ArrayCollection();
        $this->sistemacontable = new ArrayCollection();
        $this->mediotecnologico1 = new ArrayCollection();
        $this->mediotecnologico2 = new ArrayCollection();
        $this->contratocorreo1 = new ArrayCollection();
        $this->contratocorreo2 = new ArrayCollection();
        $this->contratoanclaje = new ArrayCollection();
        $this->contratointernet1 = new ArrayCollection();
        $this->contratoanclaje2 = new ArrayCollection();
        $this->contratointernet2 = new ArrayCollection();
    }

    public function getNombreCompleto() {

        return $this->getNombre() . " " . $this->getApellidos();

    }

    public function __toString() {

        return $this->getNombreCompleto();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCi(): ?string
    {
        return $this->ci;
    }

    public function setCi(string $ci): self
    {
        $this->ci = $ci;

        return $this;
    }

    public function getNoregistro(): ?string
    {
        return $this->noregistro;
    }

    public function setNoregistro(string $noregistro): self
    {
        $this->noregistro = $noregistro;

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

    public function getDireccionparticular(): ?string
    {
        return $this->direccionparticular;
    }

    public function setDireccionparticular(?string $direccionparticular): self
    {
        $this->direccionparticular = $direccionparticular;

        return $this;
    }

    public function getTelefonofijo(): ?int
    {
        return $this->telefonofijo;
    }

    public function setTelefonofijo(?int $telefonofijo): self
    {
        $this->telefonofijo = $telefonofijo;

        return $this;
    }

    public function getMovil(): ?int
    {
        return $this->movil;
    }

    public function setMovil(?int $movil): self
    {
        $this->movil = $movil;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAutobliografia(): ?string
    {
        return $this->autobliografia;
    }

    public function setAutobliografia(?string $autobliografia): self
    {
        $this->autobliografia = $autobliografia;

        return $this;
    }

    public function getMision(): ?string
    {
        return $this->mision;
    }

    public function setMision(string $mision): self
    {
        $this->mision = $mision;

        return $this;
    }

    /**
     * @return Collection|OrganizacionPolitica[]
     */
    public function getOrganizacionpolitica(): Collection
    {
        return $this->organizacionpolitica;
    }

    public function addOrganizacionpolitica(OrganizacionPolitica $organizacionpolitica): self
    {
        if (!$this->organizacionpolitica->contains($organizacionpolitica)) {
            $this->organizacionpolitica[] = $organizacionpolitica;
        }

        return $this;
    }

    public function removeOrganizacionpolitica(OrganizacionPolitica $organizacionpolitica): self
    {
        if ($this->organizacionpolitica->contains($organizacionpolitica)) {
            $this->organizacionpolitica->removeElement($organizacionpolitica);
        }

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

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

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

    public function getEspecialidad(): ?Especialidad
    {
        return $this->especialidad;
    }

    public function setEspecialidad(?Especialidad $especialidad): self
    {
        $this->especialidad = $especialidad;

        return $this;
    }

    public function getCategoriadocente(): ?CategoriaDocente
    {
        return $this->categoriadocente;
    }

    public function setCategoriadocente(?CategoriaDocente $categoriadocente): self
    {
        $this->categoriadocente = $categoriadocente;

        return $this;
    }

    public function getCategoriacientifica(): ?CategoriaCientifica
    {
        return $this->categoriacientifica;
    }

    public function setCategoriacientifica(?CategoriaCientifica $categoriacientifica): self
    {
        $this->categoriacientifica = $categoriacientifica;

        return $this;
    }

    /**
     * @return Collection|SistemaContable[]
     */
    public function getSistemacontable(): Collection
    {
        return $this->sistemacontable;
    }

    public function addSistemacontable(SistemaContable $sistemacontable): self
    {
        if (!$this->sistemacontable->contains($sistemacontable)) {
            $this->sistemacontable[] = $sistemacontable;
            $sistemacontable->setPersonal($this);
        }

        return $this;
    }

    public function removeSistemacontable(SistemaContable $sistemacontable): self
    {
        if ($this->sistemacontable->contains($sistemacontable)) {
            $this->sistemacontable->removeElement($sistemacontable);
            // set the owning side to null (unless already changed)
            if ($sistemacontable->getPersonal() === $this) {
                $sistemacontable->setPersonal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedioTecnologico[]
     */
    public function getMediotecnologico1(): Collection
    {
        return $this->mediotecnologico1;
    }

    public function addMediotecnologico1(MedioTecnologico $mediotecnologico1): self
    {
        if (!$this->mediotecnologico1->contains($mediotecnologico1)) {
            $this->mediotecnologico1[] = $mediotecnologico1;
            $mediotecnologico1->setPersonal1($this);
        }

        return $this;
    }

    public function removeMediotecnologico1(MedioTecnologico $mediotecnologico1): self
    {
        if ($this->mediotecnologico1->contains($mediotecnologico1)) {
            $this->mediotecnologico1->removeElement($mediotecnologico1);
            // set the owning side to null (unless already changed)
            if ($mediotecnologico1->getPersonal1() === $this) {
                $mediotecnologico1->setPersonal1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedioTecnologico[]
     */
    public function getMediotecnologico2(): Collection
    {
        return $this->mediotecnologico2;
    }

    public function addMediotecnologico2(MedioTecnologico $mediotecnologico2): self
    {
        if (!$this->mediotecnologico2->contains($mediotecnologico2)) {
            $this->mediotecnologico2[] = $mediotecnologico2;
            $mediotecnologico2->setPersonal2($this);
        }

        return $this;
    }

    public function removeMediotecnologico2(MedioTecnologico $mediotecnologico2): self
    {
        if ($this->mediotecnologico2->contains($mediotecnologico2)) {
            $this->mediotecnologico2->removeElement($mediotecnologico2);
            // set the owning side to null (unless already changed)
            if ($mediotecnologico2->getPersonal2() === $this) {
                $mediotecnologico2->setPersonal2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoCorreo[]
     */
    public function getContratocorreo1(): Collection
    {
        return $this->contratocorreo1;
    }

    public function addContratocorreo1(ContratoCorreo $contratocorreo1): self
    {
        if (!$this->contratocorreo1->contains($contratocorreo1)) {
            $this->contratocorreo1[] = $contratocorreo1;
            $contratocorreo1->setPersonal1($this);
        }

        return $this;
    }

    public function removeContratocorreo1(ContratoCorreo $contratocorreo1): self
    {
        if ($this->contratocorreo1->contains($contratocorreo1)) {
            $this->contratocorreo1->removeElement($contratocorreo1);
            // set the owning side to null (unless already changed)
            if ($contratocorreo1->getPersonal1() === $this) {
                $contratocorreo1->setPersonal1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoCorreo[]
     */
    public function getContratocorreo2(): Collection
    {
        return $this->contratocorreo2;
    }

    public function addContratocorreo2(ContratoCorreo $contratocorreo2): self
    {
        if (!$this->contratocorreo2->contains($contratocorreo2)) {
            $this->contratocorreo2[] = $contratocorreo2;
            $contratocorreo2->setPersonal2($this);
        }

        return $this;
    }

    public function removeContratocorreo2(ContratoCorreo $contratocorreo2): self
    {
        if ($this->contratocorreo2->contains($contratocorreo2)) {
            $this->contratocorreo2->removeElement($contratocorreo2);
            // set the owning side to null (unless already changed)
            if ($contratocorreo2->getPersonal2() === $this) {
                $contratocorreo2->setPersonal2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoAnclaje[]
     */
    public function getContratoanclaje(): Collection
    {
        return $this->contratoanclaje;
    }

    public function addContratoanclaje(ContratoAnclaje $contratoanclaje): self
    {
        if (!$this->contratoanclaje->contains($contratoanclaje)) {
            $this->contratoanclaje[] = $contratoanclaje;
            $contratoanclaje->setPersonal($this);
        }

        return $this;
    }

    public function removeContratoanclaje(ContratoAnclaje $contratoanclaje): self
    {
        if ($this->contratoanclaje->contains($contratoanclaje)) {
            $this->contratoanclaje->removeElement($contratoanclaje);
            // set the owning side to null (unless already changed)
            if ($contratoanclaje->getPersonal() === $this) {
                $contratoanclaje->setPersonal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoInternet[]
     */
    public function getContratointernet1(): Collection
    {
        return $this->contratointernet1;
    }

    public function addContratointernet1(ContratoInternet $contratointernet1): self
    {
        if (!$this->contratointernet1->contains($contratointernet1)) {
            $this->contratointernet1[] = $contratointernet1;
            $contratointernet1->setPersonal1($this);
        }

        return $this;
    }

    public function removeContratointernet1(ContratoInternet $contratointernet1): self
    {
        if ($this->contratointernet1->contains($contratointernet1)) {
            $this->contratointernet1->removeElement($contratointernet1);
            // set the owning side to null (unless already changed)
            if ($contratointernet1->getPersonal1() === $this) {
                $contratointernet1->setPersonal1(null);
            }
        }

        return $this;
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

    /**
     * @return Collection|ContratoAnclaje[]
     */
    public function getContratoanclaje2(): Collection
    {
        return $this->contratoanclaje2;
    }

    public function addContratoanclaje2(ContratoAnclaje $contratoanclaje2): self
    {
        if (!$this->contratoanclaje2->contains($contratoanclaje2)) {
            $this->contratoanclaje2[] = $contratoanclaje2;
            $contratoanclaje2->setPersonal2($this);
        }

        return $this;
    }

    public function removeContratoanclaje2(ContratoAnclaje $contratoanclaje2): self
    {
        if ($this->contratoanclaje2->contains($contratoanclaje2)) {
            $this->contratoanclaje2->removeElement($contratoanclaje2);
            // set the owning side to null (unless already changed)
            if ($contratoanclaje2->getPersonal2() === $this) {
                $contratoanclaje2->setPersonal2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContratoInternet[]
     */
    public function getContratointernet2(): Collection
    {
        return $this->contratointernet2;
    }

    public function addContratointernet2(ContratoInternet $contratointernet2): self
    {
        if (!$this->contratointernet2->contains($contratointernet2)) {
            $this->contratointernet2[] = $contratointernet2;
            $contratointernet2->setPersonal2($this);
        }

        return $this;
    }

    public function removeContratointernet2(ContratoInternet $contratointernet2): self
    {
        if ($this->contratointernet2->contains($contratointernet2)) {
            $this->contratointernet2->removeElement($contratointernet2);
            // set the owning side to null (unless already changed)
            if ($contratointernet2->getPersonal2() === $this) {
                $contratointernet2->setPersonal2(null);
            }
        }

        return $this;
    }

}
