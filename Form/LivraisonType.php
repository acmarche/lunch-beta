<?php

namespace AcMarche\LunchBundle\Form;

use AcMarche\LunchBundle\Entity\LieuLivraison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'dateLivraison',
            DateType::class,
            [
                'label' => 'Date de livraison souhaitée',
                'widget' => 'choice',
            ]
        )
            ->add(
                'lieuLivraison',
                EntityType::class,
                [
                    'class' => LieuLivraison::class,
                    'required' => true,
                    'placeholder' => 'Choisissez un lieu de livraison',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(//   'data_class' => Commande::class,
            )
        );
    }

}
