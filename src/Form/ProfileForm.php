<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\File;

class ProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('POST');
        $builder
            
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'veuillez importer une image (JPEG, JFIF, PNG, or GIF)',
                    ])
                ]
            ])

            // ->add('pseudo', TextType::class, [
            //     'label' => 'Votre pseudo',
            //     'attr' => ['placeholder' => 'Entrez votre pseudo'],
            //     'constraints' => [
            //         new Constraints\NotBlank(['message' => 'Le pseudo est obligatoire']),
            //         new Constraints\Length([
            //             'min' => 3,
            //             'max' => 50,
            //             'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères',
            //             'maxMessage' => 'Le pseudo doit contenir au plus {{ limit }} caractères',
            //         ]),
            //     ],
            // ])

            ->add('emploi', TextType::class, [
                'label' => 'Emploi',
                'required' => true,
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'L\'emploi est obligatoire']),
                    new Constraints\Length(['min' => 6, 'max' => 50, 'minMessage' => 'L\'emploi doit contenir au moins {{ limit }} caractères', 'maxMessage' => 'L\'emploi doit contenir au plus {{ limit }} caractères']),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                    new Constraints\Length(['max' => 500]),
                ]
            ])
            
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'bouton'],
            ]);
    }
}
