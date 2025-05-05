<?php

namespace App\Presentation\Web\Form\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('avatar', FileType::class, [
            'label' => 'Choisir un avatar',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                    'mimeTypesMessage' => 'Merci d’uploader une image valide (JPEG, PNG, WEBP)',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Mettre à jour l\'avatar',
            'attr' => ['class' => 'btn btn-outline-color-blue'],
        ]);
    }
}
