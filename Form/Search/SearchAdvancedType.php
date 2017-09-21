<?php

namespace AcMarche\LunchBundle\Form\Search;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\SecurityBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAdvancedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $commerces = $em->getRepository(Commerce::class)->getForSearch();

        $builder
            ->add('motclef', SearchType::class, [
                    'label' => 'Mot clef',
                    'required' => false]
            )
            ->add('commerce', ChoiceType::class, [
                'choices' => $commerces,
                'required' => false,
                'placeholder' => 'Choisissez un commerce',
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Rechercher'
            ])
            ->add('raz', SubmitType::class, [
                'label'=>'Reset'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes('em', ObjectManager::class);
    }

}
