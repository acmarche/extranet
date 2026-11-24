<?php

namespace AcMarche\QrCode\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QrGeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'latitude',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Latitude',
                    'help' => 'Format:',
                    'attr' => ['autocomplete' => 'off'],
                ],
            )
            ->add(
                'longitude',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Longitude',
                    'attr' => ['autocomplete' => 'off'],
                ],
            );
    }

    public function getParent(): string
    {
        return QrCodeType::class;
    }
}