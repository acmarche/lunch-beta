<?php

namespace AcMarche\LunchBundle\Form;

use AcMarche\LunchBundle\Entity\Ingredient;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Entity\Supplement;
use AcMarche\LunchBundle\Repository\IngredientRepository;
use AcMarche\LunchBundle\Repository\SupplementRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitLunchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'ingredients',
            EntityType::class,
            [
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $er) use ($options) {
                    return $er->getCommerceForForm($options['commerce']);
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]
        )
            ->add(
                'supplements',
                EntityType::class,
                [
                    'class' => Supplement::class,
                    'query_builder' => function (SupplementRepository $er) use ($options) {
                        return $er->getCommerceForForm($options['commerce']);
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
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
                'data_class' => Produit::class,
            )
        );
    }

    public function getParent()
    {
        return ProduitType::class;
    }


}
