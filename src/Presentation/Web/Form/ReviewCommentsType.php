<?php

namespace App\Presentation\Web\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Domain\ReviewComments\Entity\ReviewComments;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewCommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Écrivez votre commentaire ici...',
                'rows' => 1,
            ],
            'row_attr' => [
                'class' => 'col-8'
            ],
            'required' => true,
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\NotBlank([
                    'message' => 'Le commentaire ne peut pas être vide.',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Ajouter un commentaire',
            'attr' => [
                'class' => 'btn btn-outline-custom-blue'
            ],
            'row_attr' => [
                'class' => 'col-4'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReviewComments::class,
        ]);
    }
}
