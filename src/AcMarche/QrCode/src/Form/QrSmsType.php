<?php

namespace AcMarche\QrCode\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QrSmsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Numéro de téléphone',
                    'help' => 'Format:',
                    'attr' => ['autocomplete' => 'off'],
                ],
            )
            ->add(
                'message',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Message',
                    'help' => 'Message pré-enregistré. Non requis',
                    'attr' => ['autocomplete' => 'off'],
                ],
            );
    }

    public function getParent(): string
    {
        return QrCodeType::class;
    }
}