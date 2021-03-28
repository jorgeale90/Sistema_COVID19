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
     * @ORM\OneToMany(targetEntity="Provincia", mappedBy="pais")
     */
    protected $provincia;

    public function __construct()
    {
        $this->provincia = new ArrayCollection();
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

}
