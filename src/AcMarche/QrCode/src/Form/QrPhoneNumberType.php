<?php

namespace AcMarche\QrCode\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QrPhoneNumberType extends AbstractType
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
                    'help' => 'Sans point, ni espace. Format:+32475662488',
                    'attr' => ['autocomplete' => 'off'],
                ],
            );
    }

    public function getParent(): string
    {
        return QrCodeType::class;
    }
}