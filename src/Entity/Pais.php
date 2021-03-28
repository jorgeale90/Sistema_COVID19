<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaisRepository")
 * @UniqueEntity(fields={"nombre"}, message="Ya existe este Pais.")
 * @Audit\Auditable
 */

class Pais
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombrepais", type="string",  nullable=false, length=100, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÑñÓÚáéÍÁÉíóúü ]*$/",
     *     message="Debe de contener solo letras"
     * )
     * @Assert\Length(min=2, max=100, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Provincia", mappedBy="pais")
     */
    protected $provincia;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="paisprocedencia")
     */
    protected $personalprecedencia;

    public function __construct()
    {
        $this->provincia = new ArrayCollection();
        $this->personalprecedencia = new ArrayCollection();
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

    /**
     * @return Collection|Provincia[]
     */
    public function getProvincia(): Collection
    {
        return $this->provincia;
    }

    public function addProvincium(Provincia $provincium): self
    {
        if (!$this->provincia->contains($provincium)) {
            $this->provincia[] = $provincium;
            $provincium->setPais($this);
        }

        return $this;
    }

    public function removeProvincium(Provincia $provincium): self
    {
        if ($this->provincia->contains($provincium)) {
            $this->provincia->removeElement($provincium);
            // set the owning side to null (unless already changed)
            if ($provincium->getPais() === $this) {
                $provincium->setPais(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personal[]
     */
    public function getPersonalprecedencia(): Collection
    {
        return $this->personalprecedencia;
    }

    public function addPersonalprecedencium(Personal $personalprecedencium): self
    {
        if (!$this->personalprecedencia->contains($personalprecedencium)) {
            $this->personalprecedencia[] = $personalprecedencium;
            $personalprecedencium->setPaisprocedencia($this);
        }

        return $this;
    }

    public function removePersonalprecedencium(Personal $personalprecedencium): self
    {
        if ($this->personalprecedencia->contains($personalprecedencium)) {
            $this->personalprecedencia->removeElement($personalprecedencium);
            // set the owning side to null (unless already changed)
            if ($personalprecedencium->getPaisprocedencia() === $this) {
                $personalprecedencium->setPaisprocedencia(null);
            }
        }

        return $this;
    }
}
