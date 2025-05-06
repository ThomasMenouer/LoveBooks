<?php

namespace App\Presentation\Web\Form;


use App\Domain\UserBooks\Enum\Status;
use Symfony\Component\Form\AbstractType;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

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
                'choices' => Status::cases(),
                'choice_label' => fn (Status $status) => $status->label(),
                'choice_value' => fn (Status $status) => $status->value,

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
            ->add('isPreferred', ChoiceType::class, [
                'label' => 'Livre préféré',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'choice_attr' => [
                    'class' => 'form-check-input'
                ],

                'row_attr' => [
                    'class' => 'form-check-inline'
                ],
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'data' => false,
            ])
            ->add('userRating', ChoiceType::class, [
                'label' => 'Votre note',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'placeholder' => false,
                'label_attr' => ['class' => 'd-none'], // on cache les labels générés
                'attr' => ['class' => 'star-rating'],
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
            'data_class' => UserBooks::class,
        ]);
    }
}
