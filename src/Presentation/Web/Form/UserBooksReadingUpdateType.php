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

class UserBooksReadingUpdateType extends AbstractType
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
        ->add('submit', SubmitType::class, [
            'label' => 'Mettre à jour',
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
