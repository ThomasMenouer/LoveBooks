<?php

namespace App\Presentation\Web\Form;


use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UserBooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            // ->add('userRating', ChoiceType::class, [
            //     'label' => 'Votre note',
            //     'choices' => [
            //         '⭐' => 1,
            //         '⭐⭐' => 2,
            //         '⭐⭐⭐' => 3,
            //         '⭐⭐⭐⭐' => 4,
            //         '⭐⭐⭐⭐⭐' => 5,
            //     ],
            //     'expanded' => true,
            //     'multiple' => false,
            //     'required' => false,
            //     'attr' => ['class' => 'star-rating'],
            // ])
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
            'data_class' => UserBooks::class,
        ]);
    }
}
