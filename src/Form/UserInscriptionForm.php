<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class UserInscriptionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('POST');
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez votre email'],
                'constraints' => [
                    new Constraints\NotBlank(['message' => "L'email est obligatoire"]),
                    new Constraints\Email(['message' => 'Email invalide']),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Votre pseudo',
                'attr' => ['placeholder' => 'Entrez votre pseudo'],
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Le pseudo est obligatoire']),
                    new Constraints\Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le pseudo doit contenir au plus {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Le mot de passe est obligatoire']),
                    new Constraints\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            // ->add('confirm_password', PasswordType::class, [
            //     'label' => 'Confirmer le mot de passe',
            //     'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
            //     'constraints' => [
            //         new Constraints\NotBlank(['message' => 'La confirmation du mot de passe est obligatoire']),
            //         new Constraints\Length([
            //             'min' => 6,
            //             'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
            //         ]),
            //     ],
            // ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'bouton'],
            ]);
    }
}
