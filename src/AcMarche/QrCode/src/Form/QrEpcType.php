<?php

namespace AcMarche\QrCode\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Iban;

class QrEpcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'iban',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Numéro de compte bancaire',
                    'help' => 'Sans espace, exemple:BE87105068822094',
                    'attr' => ['autocomplete' => 'off'],
                    'constraints' => [new Iban()],
                ],
            )
            ->add(
                'amount',
                MoneyType::class,
                [
                    'required' => true,
                    'label' => 'Montant',
                    'help' => 'Uniquement les chiffres, exemple: 100.50',
                    'attr' => ['autocomplete' => 'off'],
                ],
            )
            ->add(
                'recipient',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'A l\'attention de',
                    'help' => 'Destinataire',
                    'attr' => ['autocomplete' => 'off'],
                ],
            )
            ->add(
                'message',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Communication',
                    'help' => 'Structurée ou non',
                    'attr' => ['autocomplete' => 'off'],
                ],
            );
    }

    public function getParent(): string
    {
        return QrCodeType::class;
    }
}