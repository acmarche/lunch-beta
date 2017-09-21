<?php

namespace AcMarche\LunchBundle\Form;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/08/17
 * Time: 15:11
 */

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Nom'],
                ]
            )
            ->add(
                'prenom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Prénom'],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => ['placeholder' => 'Email'],
                ]
            )
            ->add(
                'rue',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Rue et numéro'],
                ]
            )
            ->add(
                'code_postal',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Code postal'],
                ]
            )
            ->add(
                'telephone',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Téléphone'],
                ]
            )
            ->add(
                'localite',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Localité'],
                ]
            )
            ->add(
                'sms_numero',
                TextType::class,
                [
                    'attr' => [
                        'help' => 'Format: 32476662615',
                    ],
                ]
            );
    }

    public function getParent()
    {
        return RegistrationFormType::class;
    }
}