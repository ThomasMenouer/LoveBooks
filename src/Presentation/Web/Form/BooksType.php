<?php

namespace App\Presentation\Web\Form;


use App\Domain\Books\Entity\Books;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
                'row_attr' => [
                    'class' => 'mb-3'
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
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'choices' => [
                    'Lu' => 'Lu',
                    'En cours de lecture' => 'En cours de lecture',
                    'Non lu' => 'Non lu',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'required' => true,
            ])
            ->add('pagesRead', IntegerType::class, [
                'label' => 'Pages lues',
                'attr' => [
                    'min' => 0,
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'row_attr' => [
                    'class' => 'd-flex justify-content-center'
                ],
                'attr' => [
                    'class' => 'btn btn-outline-custom-yellow'
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
