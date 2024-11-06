<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class ConnexionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez votre email'],
                'constraints' => [
                    new Constraints\NotBlank(['message' => "L'email est obligatoire"]),
                    new Constraints\Email(['message' => 'Email invalide']),
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
            ->add('envoyer', SubmitType::class, [
                'label' => 'Se connecter',
                'attr' => ['class' => 'connexion'],
            ]);
    }
}

