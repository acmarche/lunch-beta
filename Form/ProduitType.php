<?php

namespace AcMarche\LunchBundle\Form;

use AcMarche\LunchBundle\Entity\Categorie;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use AcMarche\LunchBundle\Form\Type\CommerceHiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add(
                'indisponible',
                CheckboxType::class,
                [
                    'attr' => ['data-help' => 'Le produit ne sera plus visible sur le site'],
                    'required' => false,
                ]
            )
            ->add(
                'prixHtva',
                MoneyType::class
            )
            ->add(
                'quantiteStock',
                IntegerType::class,
                [
                    'label' => 'Stock disponible',
                    'attr' => ['data-help' => 'Mettre -1 pour non limités'],
                ]
            )
            ->add(
                'tvaApplicable',
                PercentType::class,
                [
                    'scale' => 2,
                    'type' => 'integer',
                    'required' => false,
                    'attr' => ['data-help' => 'Si différente de celle définie sur le commerce'],
                ]
            )
            ->add(
                'imageFile',
                VichFileType::class,
                [
                    'label' => 'Image',
                    'required' => false,
                ]
            )
            ->add('commerce', CommerceHiddenType::class)
            ->add(
                'categorie',
                EntityType::class,
                [
                    'required' => true,
                    'class' => Categorie::class,
                    'placeholder' => 'Sélectionnez une catégorie',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                //  'data_class' => Produit::class,
                'requiredImage' => true,
            )
        );

        $resolver->setRequired('commerce');
        $resolver->setRequired('requiredImage');
        $resolver->setAllowedTypes('commerce', CommerceInterface::class);
    }

}
