<?php

namespace App\Form;

use App\Entity\Books;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('authors')
            ->add('publisher')
            ->add('description')
            ->add('pageCount')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Lu' => 'Lu',
                    'En cours de lecture' => 'En cours de lecture',
                    'Non lu' => 'Non lu',
                ],
                'required' => true,
            ])
            ->add('publishedDate', null, [
                'widget' => 'single_text',
            ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
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
