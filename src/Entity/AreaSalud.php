<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AreaSaludRepository")
 * @UniqueEntity(fields={"nombre"}, message="Ya existe este Area de Salud.")
 * @Audit\Auditable
 */

class AreaSalud
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombrearea", type="string",  nullable=false, length=80, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Length(min=2, max=80, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity = "Provincia", inversedBy = "areasalud")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Provincia")
     */
    protected $provincia;

    /**
     * @ORM\ManyToOne(targetEntity = "Municipio", inversedBy = "area_salud")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar un Municipio")
     */
    protected $municipio;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="areasalud")
     */
    protected $personal;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="centroprocemuestra")
     */
    protected $personalcentromuestra;

    public function __construct()
    {
        $this->personal = new ArrayCollection();
        $this->personalcentromuestra = new ArrayCollection();
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

    public function getMunicipio(): ?Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(?Municipio $municipio): self
    {
        $this->municipio = $municipio;

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
            $personal->setAreasalud($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personal->contains($personal)) {
            $this->personal->removeElement($personal);
            // set the owning side to null (unless already changed)
            if ($personal->getAreasalud() === $this) {
                $personal->setAreasalud(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personal[]
     */
    public function getPersonalcentromuestra(): Collection
    {
        return $this->personalcentromuestra;
    }

    public function addPersonalcentromuestra(Personal $personalcentromuestra): self
    {
        if (!$this->personalcentromuestra->contains($personalcentromuestra)) {
            $this->personalcentromuestra[] = $personalcentromuestra;
            $personalcentromuestra->setCentroprocemuestra($this);
        }

        return $this;
    }

    public function removePersonalcentromuestra(Personal $personalcentromuestra): self
    {
        if ($this->personalcentromuestra->contains($personalcentromuestra)) {
            $this->personalcentromuestra->removeElement($personalcentromuestra);
            // set the owning side to null (unless already changed)
            if ($personalcentromuestra->getCentroprocemuestra() === $this) {
                $personalcentromuestra->setCentroprocemuestra(null);
            }
        }

        return $this;
    }

}
