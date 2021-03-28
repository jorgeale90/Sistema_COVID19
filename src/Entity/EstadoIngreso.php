<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\DoctrineAuditBundle\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstadoIngresoRepository")
 * @UniqueEntity(fields={"nombre"}, message="Ya existe este Estado de Paciente al Ingreso.")
 * @Audit\Auditable
 */

class EstadoIngreso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombreestadoin", type="string",  nullable=false, length=100, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÑñÓÚáéÍÁÉíóúü ]*$/",
     *     message="Debe de contener solo letras"
     * )
     * @Assert\Length(min=2, max=100, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Personal", mappedBy="estadoingreso")
     */
    protected $personal;

    public function __construct()
    {
        $this->personal = new ArrayCollection();
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
    public function getPersonal(): Collection
    {
        return $this->personal;
    }

    public function addPersonal(Personal $personal): self
    {
        if (!$this->personal->contains($personal)) {
            $this->personal[] = $personal;
            $personal->setEstadoingreso($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personal->contains($personal)) {
            $this->personal->removeElement($personal);
            // set the owning side to null (unless already changed)
            if ($personal->getEstadoingreso() === $this) {
                $personal->setEstadoingreso(null);
            }
        }

        return $this;
    }

}
