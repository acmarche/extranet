<?php

namespace AcMarche\QrCode\Form;

use AcMarche\QrCode\Entity\QrCode;
use AcMarche\QrCode\QrBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QrCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'pixels',
                IntegerType::class,
                [
                    'required' => false,
                    'label' => 'Taille',
                    'help' => 'Taille en pixel (1200 par défaut)',
                ],
            )
            ->add(
                'format',
                ChoiceType::class,
                [
                    'required' => true,
                    'label' => 'Format',
                    'choices' => QrBuilder::$FORMATS,
                ],
            )
            ->add(
                'color',
                ColorType::class,
                [
                    'required' => false,
                    'label' => 'Couleur des traits',
                ],
            )
            ->add(
                'backgroundColor',
                ColorType::class,
                [
                    'required' => false,
                    'label' => 'Couleur de fond',
                ],
            )
            ->add(
                'style',
                ChoiceType::class,
                [
                    'required' => true,
                    'label' => 'Forme',
                    'choices' => QrBuilder::$FORMES,
                ],
            )
            ->add(
                'margin',
                ChoiceType::class,
                [
                    'required' => true,
                    'label' => 'Marge',
                    'choices' => QrBuilder::$MARGINS,
                ],
            )
            ->add(
                'imagePercentage',
                ChoiceType::class,
                [
                    'required' => true,
                    'label' => 'Image pourcentage',
                    'help' => 'Pourcentage de la taille du QrCode',
                    'choices' => array_flip(QrBuilder::$IMAGE_PERCENTAGE),
                ],
            )
            ->add(
                'logo',
                FileType::class,
                [
                    'label' => 'Votre logo',
                    'help' => 'Ajoute un logo au milieu du QrCode',
                    'required' => false,
                ],
            )
            ->add(
                'addDefaultLogo',
                CheckboxType::class,
                [
                    'label' => 'Ajouter le logo de la Ville',
                    'help' => 'Le logo de la Ville sera placé au milieu du QrCode',
                    'required' => false,
                ],
            )
            ->add(
                'name',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Conserver',
                    'help' => 'Indiquez un nom pour archiver et récupérer le QrCode plus tard',
                    'attr' => ['autocomplete' => 'off'],
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => QrCode::class,
            ],
        );
    }
}