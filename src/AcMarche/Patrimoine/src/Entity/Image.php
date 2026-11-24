<?php

namespace AcMarche\Patrimoine\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\Patrimoine\Repository\ImageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[Vich\Uploadable]
#[ORM\Table(name: 'patrimoine_image')]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 80)]
    public ?string $mime = null;

    #[Vich\UploadableField(mapping: 'patrimoine', fileNameProperty: 'fileName', size: 'fileSize')]
    public ?File $file = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public ?string $fileName = null;

    #[ORM\Column(type: Types::INTEGER)]
    public ?int $fileSize = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $imageFile
     *
     * @throws Exception
     */
    public function setFile(?File $file = null): void
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

    public function __construct(#[ORM\ManyToOne(targetEntity: Patrimoine::class, inversedBy: 'images')]
    public ?Patrimoine $patrimoine)
    {
    }
}
