<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SintomasIngresoRepository")
 * @UniqueEntity(fields={"nombre"}, message="Ya existe este Síntoma de Ingreso.")
 * @Audit\Auditable
 */

class SintomasIngreso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombresintomain", type="string",  nullable=false, length=100, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÑñÓÚáéÍÁÉíóúü0-9 ]*$/",
     *     message="Debe de contener solo letras"
     * )
     * @Assert\Length(min=2, max=100, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="Personal", mappedBy="sintomaingreso")
     */
    protected $personales;

    public function __construct()
    {
        $this->personales = new ArrayCollection();
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
     * @return Collection|Personal[]
     */
    public function getPersonales(): Collection
    {
        return $this->personales;
    }

    public function addPersonale(Personal $personale): self
    {
        if (!$this->personales->contains($personale)) {
            $this->personales[] = $personale;
            $personale->addSintomaingreso($this);
        }

        return $this;
    }

    public function removePersonale(Personal $personale): self
    {
        if ($this->personales->contains($personale)) {
            $this->personales->removeElement($personale);
            $personale->removeSintomaingreso($this);
        }

        return $this;
    }
}
