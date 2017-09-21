<?php

namespace AcMarche\LunchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactCommerceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
                'email',
                EmailType::class,
                [
                    'attr' => ['placeholder' => 'Email'],
                    'constraints' => array(new Email())
                ]
            )
            ->add(
                'website',
                UrlType::class,
                [
                    'required' => false,
                    'attr' => ['placeholder' => 'Site web'],
                ]
            )
            ->add(
                'commentaire',
                TextareaType::class,
                [
                    'attr' => ['placeholder' => 'Commentaire', 'rows' => 3],
                    'constraints' => array(new Length(array('min' => 10)))
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

}
