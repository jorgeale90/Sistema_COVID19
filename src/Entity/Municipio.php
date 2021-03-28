<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MunicipioRepository")
 * @UniqueEntity(fields={"nombre"}, message="Ya existe este Municipio.")
 * @Audit\Auditable
 */

class Municipio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombremunicipio", type="string",  nullable=false, length=80, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÑñÓÚáéÍÁÉíóúü ]*$/",
     *     message="Debe de contener solo letras"
     * )
     * @Assert\Length(min=2, max=80, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity = "Provincia", inversedBy = "municipio")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Provincia")
     */
    protected $provincia;

    /**
     * @ORM\OneToMany(targetEntity="HospitalIngreso", mappedBy="municipio")
     */
    protected $hospitalingreso;

    /**
     * @ORM\OneToMany(targetEntity="AreaSalud", mappedBy="municipio")
     */
    protected $area_salud;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="municipio")
     */
    protected $personal;

    /**
     * @ORM\OneToMany(targetEntity="ConsejoPopular", mappedBy="municipio")
     */
    protected $consejopopular;

    public function __construct()
    {
        $this->hospitalingreso = new ArrayCollection();
        $this->area_salud = new ArrayCollection();
        $this->personal = new ArrayCollection();
        $this->consejopopular = new ArrayCollection();
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

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincia $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * @return Collection|HospitalIngreso[]
     */
    public function getHospitalingreso(): Collection
    {
        return $this->hospitalingreso;
    }

    public function addHospitalingreso(HospitalIngreso $hospitalingreso): self
    {
        if (!$this->hospitalingreso->contains($hospitalingreso)) {
            $this->hospitalingreso[] = $hospitalingreso;
            $hospitalingreso->setMunicipio($this);
        }

        return $this;
    }

    public function removeHospitalingreso(HospitalIngreso $hospitalingreso): self
    {
        if ($this->hospitalingreso->contains($hospitalingreso)) {
            $this->hospitalingreso->removeElement($hospitalingreso);
            // set the owning side to null (unless already changed)
            if ($hospitalingreso->getMunicipio() === $this) {
                $hospitalingreso->setMunicipio(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AreaSalud[]
     */
    public function getAreaSalud(): Collection
    {
        return $this->area_salud;
    }

    public function addAreaSalud(AreaSalud $areaSalud): self
    {
        if (!$this->area_salud->contains($areaSalud)) {
            $this->area_salud[] = $areaSalud;
            $areaSalud->setMunicipio($this);
        }

        return $this;
    }

    public function removeAreaSalud(AreaSalud $areaSalud): self
    {
        if ($this->area_salud->contains($areaSalud)) {
            $this->area_salud->removeElement($areaSalud);
            // set the owning side to null (unless already changed)
            if ($areaSalud->getMunicipio() === $this) {
                $areaSalud->setMunicipio(null);
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
            $personal->setMunicipio($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personal->contains($personal)) {
            $this->personal->removeElement($personal);
            // set the owning side to null (unless already changed)
            if ($personal->getMunicipio() === $this) {
                $personal->setMunicipio(null);
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
            $consejopopular->setMunicipio($this);
        }

        return $this;
    }

    public function removeConsejopopular(ConsejoPopular $consejopopular): self
    {
        if ($this->consejopopular->contains($consejopopular)) {
            $this->consejopopular->removeElement($consejopopular);
            // set the owning side to null (unless already changed)
            if ($consejopopular->getMunicipio() === $this) {
                $consejopopular->setMunicipio(null);
            }
        }

        return $this;
    }
}
