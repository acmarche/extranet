<?php

namespace AcMarche\EnquetePublique\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ImageDropZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('file', DropzoneType::class, [
            'attr' => [
                'placeholder' => 'Cliquez ici pour sélectioner les images',
            ],
            'label' => false,
            'multiple' => false,

        ]);
    }
}
