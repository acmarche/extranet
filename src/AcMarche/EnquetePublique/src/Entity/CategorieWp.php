<?php

namespace AcMarche\EnquetePublique\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\EnquetePublique\Repository\CategorieWpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: CategorieWpRepository::class)]
class CategorieWp implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::INTEGER)]
    public int $wpcatid;

    /**
     * @var Collection<int, Enquete>
     */
    #[ORM\OneToMany(targetEntity: Enquete::class, mappedBy: 'categorie_wp', orphanRemoval: true)]
    private iterable $enquetes;

    public function __construct()
    {
        $this->enquetes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Enquete[]
     */
    public function getEnquetes(): iterable
    {
        return $this->enquetes;
    }

    public function addEnquete(Enquete $enquete): self
    {
        if (!$this->enquetes->contains($enquete)) {
            $this->enquetes[] = $enquete;
            $enquete->setCategorie($this);
        }

        return $this;
    }

    public function removeEnquete(Enquete $enquete): self
    {
        // set the owning side to null (unless already changed)
        if ($this->enquetes->removeElement($enquete) && $enquete->getCategorie() === $this) {
            $enquete->setCategorie(null);
        }

        return $this;
    }
}
