<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Введите ваше имя'])
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new Email(['message' => 'Введите коректный емайл']),
                                    new NotBlank(['message' => 'Введите ваш емайл'])
                ]
            ])
            ->add('subject', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Введите ваше имя'])
            ])
            ->add('message', TextareaType::class, [
                'constraints' => new NotBlank(['message' => 'Введите ваше имя'])
            ])
        ;
    }

}