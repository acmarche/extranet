<?php

namespace AcMarche\EnquetePublique\Entity;

use Doctrine\DBAL\Types\Types;
use DateTimeImmutable;
use AcMarche\EnquetePublique\Entity\Traits\AvisFileTrait;
use AcMarche\EnquetePublique\Entity\Traits\DatesDiffusionTrait;
use AcMarche\EnquetePublique\Entity\Traits\LocationTrait;
use AcMarche\EnquetePublique\Location\LocationAbleInterface;
use AcMarche\EnquetePublique\Repository\EnqueteRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Stringable;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: EnqueteRepository::class)]
class Enquete implements TimestampableInterface, LocationAbleInterface, Stringable
{
    use LocationTrait;
    use DatesDiffusionTrait;
    use TimestampableTrait;
    use AvisFileTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private ?string $intitule = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private ?string $demandeur = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'enquetes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'enquete', cascade: ['persist', 'remove'])]
    private iterable $documents;

    #[ORM\ManyToOne(targetEntity: CategorieWp::class, inversedBy: 'enquetes')]
    #[ORM\JoinColumn(nullable: true)]
    public ?CategorieWp $categorie_wp = null;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->code_postal = 6900;
        $this->date_debut = new DateTimeImmutable();
        $this->date_fin = new DateTimeImmutable('+1 month');
    }

    public function __toString(): string
    {
        return (string) $this->demandeur;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDemandeur(): ?string
    {
        return $this->demandeur;
    }

    public function setDemandeur(string $demandeur): self
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): iterable
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setEnquete($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        // set the owning side to null (unless already changed)
        if ($this->documents->removeElement($document) && $document->getEnquete() === $this) {
            $document->setEnquete(null);
        }

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }
}
