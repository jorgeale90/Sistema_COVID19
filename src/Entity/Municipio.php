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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombre", type="string",  nullable=false, length=30, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z ]*$/",
     *     message="Debe de contener solo letras"
     * )
     * @Assert\Length(min=2, max=30, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity = "Provincia", inversedBy = "municipio")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una Provincia")
     */
    protected $provincia;

    /**
     * @ORM\OneToMany(targetEntity="Institucion", mappedBy="municipio")
     */
    protected $institucion;

    public function __construct()
    {
        $this->institucion = new ArrayCollection();
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
     * @return Collection|Institucion[]
     */
    public function getInstitucion(): Collection
    {
        return $this->institucion;
    }

    public function addInstitucion(Institucion $institucion): self
    {
        if (!$this->institucion->contains($institucion)) {
            $this->institucion[] = $institucion;
            $institucion->setMunicipio($this);
        }

        return $this;
    }

    public function removeInstitucion(Institucion $institucion): self
    {
        if ($this->institucion->contains($institucion)) {
            $this->institucion->removeElement($institucion);
            // set the owning side to null (unless already changed)
            if ($institucion->getMunicipio() === $this) {
                $institucion->setMunicipio(null);
            }
        }

        return $this;
    }
}
