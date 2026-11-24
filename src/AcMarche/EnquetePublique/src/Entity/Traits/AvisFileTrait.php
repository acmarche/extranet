<?php

namespace AcMarche\EnquetePublique\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

trait AvisFileTrait
{
    #[Vich\UploadableField(mapping: 'avis_file', fileNameProperty: 'avisName', size: 'avisSize')]
    #[Assert\File(maxSize: '16384k', mimeTypes: [
        'application/pdf',
        'application/x-pdf',
        'image/*',
    ], mimeTypesMessage: 'Uniquement des PDF ou images')]
    private ?File $avisFile = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $avisName = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $avisSize = 0;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     */
    public function setAvisFile(?File $avisFile = null): void
    {
        $this->avisFile = $avisFile;

        if ($avisFile instanceof File) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime();
        }
    }

    public function getAvisFile(): ?File
    {
        return $this->avisFile;
    }

    public function setAvisName(?string $avisName): void
    {
        $this->avisName = $avisName;
    }

    public function getAvisName(): ?string
    {
        return $this->avisName;
    }

    public function setAvisSize(?int $avisSize): void
    {
        $this->avisSize = $avisSize;
    }

    public function getAvisSize(): ?int
    {
        return $this->avisSize;
    }
}
