<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class PostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez votre titre'],
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Le titre est obligatoire']),
                    new Constraints\Length([
                        'min' => 3,
                        'max' => 100,
                    ]),
                ],
            ])

            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez votre description'],
                'constraints' => [
                    new Constraints\Length([
                        'max' => 1000,
                    ]),
                ],
            ])
            ->add('isPublic', CheckboxType::class, [
                'label' => 'Public',
                'required' => false,
            ])

            ->add('image', FileType::class, [
                'label' => 'image',
                'required' => false,
                'mapped' => false,
                'data_class' => null,
            ])

            ->add('envoyer', SubmitType::class, [
                'label' => 'ajouter le post',
                'attr' => ['class' => 'connexion'],
            ]);
    }
}
