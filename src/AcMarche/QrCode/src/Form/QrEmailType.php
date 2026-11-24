<?php

namespace AcMarche\QrCode\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QrEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'label' => 'Adresse mail',
                    'attr' => ['autocomplete' => 'off'],
                ],
            )
            ->add(
                'subject',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Sujet',
                    'help' => 'Non requis',
                    'attr' => ['autocomplete' => 'off'],
                ],
            )
            ->add(
                'message',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Message',
                    'help' => 'Non requis',
                    'attr' => ['autocomplete' => 'off'],
                ],
            );
    }

    public function getParent(): string
    {
        return QrCodeType::class;
    }
}