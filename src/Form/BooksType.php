<?php

namespace App\Form;

use App\Entity\Books;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('authors', TextType::class, [
                'label' => 'Auteurs',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Lu' => 'Lu',
                    'En cours de lecture' => 'En cours de lecture',
                    'Non lu' => 'Non lu',
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'row_attr' => [
                    'class' => 'd-flex justify-content-center'
                ],
                'attr' => [
                    'class' => 'btn btn-outline-custom'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);
    }
}
