<?php

namespace AcMarche\EnquetePublique\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\EnquetePublique\Repository\DocumentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Stringable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document implements TimestampableInterface, Stringable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    protected ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $description = null;

    #[Vich\UploadableField(mapping: 'enquete_document', fileNameProperty: 'fileName', size: 'fileSize')]
    #[Assert\File(maxSize: '16384k', mimeTypes: [
        'application/pdf',
        'application/x-pdf',
        'image/*',
    ], mimeTypesMessage: 'Uniquement des PDF ou images')]
    private ?File $file = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $fileName = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $fileSize = null;

    #[ORM\Column(name: 'mime_type', type: Types::STRING, nullable: true)]
    protected ?string $mimeType = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     */
    public function setDocFile(?File $file = null): void
    {
        $this->file = $file;

        if ($file instanceof File) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Enquete::class, inversedBy: 'documents')] protected ?Enquete $enquete
    ) {
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getEnquete(): ?Enquete
    {
        return $this->enquete;
    }

    public function setEnquete(?Enquete $enquete): self
    {
        $this->enquete = $enquete;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}
