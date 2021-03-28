<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProvinciaRepository")
 * @UniqueEntity(fields={"nombre"}, message="Ya existe esta Provincia.")
 * @Audit\Auditable
 */

class Provincia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombreprovincia", type="string",  nullable=false, length=80, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÑñÓÚáéÍÁÉíóúü ]*$/",
     *     message="Debe de contener solo letras"
     * )
     * @Assert\Length(min=2, max=80, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity = "Pais", inversedBy = "provincia")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar un País")
     */
    protected $pais;

    /**
     * @ORM\OneToMany(targetEntity="Municipio", mappedBy="provincia")
     */
    protected $municipio;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="provincia")
     */
    protected $personal;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="provinciaentrada")
     */
    protected $personalprovinciaentrada;

    /**
     * @ORM\OneToMany(targetEntity="HospitalIngreso", mappedBy="provincia")
     */
    protected $hospital;

    /**
     * @ORM\OneToMany(targetEntity="AreaSalud", mappedBy="provincia")
     */
    protected $areasalud;

    /**
     * @ORM\OneToMany(targetEntity="ConsejoPopular", mappedBy="provincia")
     */
    protected $consejopopular;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="provinciaprocedenmuestra")
     */
    protected $personalprocedenmuestra;

    public function __construct()
    {
        $this->municipio = new ArrayCollection();
        $this->personal = new ArrayCollection();
        $this->personalprovinciaentrada = new ArrayCollection();
        $this->hospital = new ArrayCollection();
        $this->areasalud = new ArrayCollection();
        $this->consejopopular = new ArrayCollection();
        $this->personalprocedenmuestra = new ArrayCollection();
    }

    public function __toString() {

        return $this->getNombre();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * @return Collection|Municipio[]
     */
    public function getMunicipio(): Collection
    {
        return $this->municipio;
    }

    public function addMunicipio(Municipio $municipio): self
    {
        if (!$this->municipio->contains($municipio)) {
            $this->municipio[] = $municipio;
            $municipio->setProvincia($this);
        }

        return $this;
    }

    public function removeMunicipio(Municipio $municipio): self
    {
        if ($this->municipio->contains($municipio)) {
            $this->municipio->removeElement($municipio);
            // set the owning side to null (unless already changed)
            if ($municipio->getProvincia() === $this) {
                $municipio->setProvincia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personal[]
     */
    public function getPersonal(): Collection
    {
        return $this->personal;
    }

    public function addPersonal(Personal $personal): self
    {
        if (!$this->personal->contains($personal)) {
            $this->personal[] = $personal;
            $personal->setProvincia($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personal->contains($personal)) {
            $this->personal->removeElement($personal);
            // set the owning side to null (unless already changed)
            if ($personal->getProvincia() === $this) {
                $personal->setProvincia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personal[]
     */
    public function getPersonalprovinciaentrada(): Collection
    {
        return $this->personalprovinciaentrada;
    }

    public function addPersonalprovinciaentrada(Personal $personalprovinciaentrada): self
    {
        if (!$this->personalprovinciaentrada->contains($personalprovinciaentrada)) {
            $this->personalprovinciaentrada[] = $personalprovinciaentrada;
            $personalprovinciaentrada->setProvinciaentrada($this);
        }

        return $this;
    }

    public function removePersonalprovinciaentrada(Personal $personalprovinciaentrada): self
    {
        if ($this->personalprovinciaentrada->contains($personalprovinciaentrada)) {
            $this->personalprovinciaentrada->removeElement($personalprovinciaentrada);
            // set the owning side to null (unless already changed)
            if ($personalprovinciaentrada->getProvinciaentrada() === $this) {
                $personalprovinciaentrada->setProvinciaentrada(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HospitalIngreso[]
     */
    public function getHospital(): Collection
    {
        return $this->hospital;
    }

    public function addHospital(HospitalIngreso $hospital): self
    {
        if (!$this->hospital->contains($hospital)) {
            $this->hospital[] = $hospital;
            $hospital->setProvincia($this);
        }

        return $this;
    }

    public function removeHospital(HospitalIngreso $hospital): self
    {
        if ($this->hospital->contains($hospital)) {
            $this->hospital->removeElement($hospital);
            // set the owning side to null (unless already changed)
            if ($hospital->getProvincia() === $this) {
                $hospital->setProvincia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AreaSalud[]
     */
    public function getAreasalud(): Collection
    {
        return $this->areasalud;
    }

    public function addAreasalud(AreaSalud $areasalud): self
    {
        if (!$this->areasalud->contains($areasalud)) {
            $this->areasalud[] = $areasalud;
            $areasalud->setProvincia($this);
        }

        return $this;
    }

    public function removeAreasalud(AreaSalud $areasalud): self
    {
        if ($this->areasalud->contains($areasalud)) {
            $this->areasalud->removeElement($areasalud);
            // set the owning side to null (unless already changed)
            if ($areasalud->getProvincia() === $this) {
                $areasalud->setProvincia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsejoPopular[]
     */
    public function getConsejopopular(): Collection
    {
        return $this->consejopopular;
    }

    public function addConsejopopular(ConsejoPopular $consejopopular): self
    {
        if (!$this->consejopopular->contains($consejopopular)) {
            $this->consejopopular[] = $consejopopular;
            $consejopopular->setProvincia($this);
        }

        return $this;
    }

    public function removeConsejopopular(ConsejoPopular $consejopopular): self
    {
        if ($this->consejopopular->contains($consejopopular)) {
            $this->consejopopular->removeElement($consejopopular);
            // set the owning side to null (unless already changed)
            if ($consejopopular->getProvincia() === $this) {
                $consejopopular->setProvincia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personal[]
     */
    public function getPersonalprocedenmuestra(): Collection
    {
        return $this->personalprocedenmuestra;
    }

    public function addPersonalprocedenmuestra(Personal $personalprocedenmuestra): self
    {
        if (!$this->personalprocedenmuestra->contains($personalprocedenmuestra)) {
            $this->personalprocedenmuestra[] = $personalprocedenmuestra;
            $personalprocedenmuestra->setProvinciaprocedenmuestra($this);
        }

        return $this;
    }

    public function removePersonalprocedenmuestra(Personal $personalprocedenmuestra): self
    {
        if ($this->personalprocedenmuestra->contains($personalprocedenmuestra)) {
            $this->personalprocedenmuestra->removeElement($personalprocedenmuestra);
            // set the owning side to null (unless already changed)
            if ($personalprocedenmuestra->getProvinciaprocedenmuestra() === $this) {
                $personalprocedenmuestra->setProvinciaprocedenmuestra(null);
            }
        }

        return $this;
    }
}
