<?php

namespace App\Form;

use App\Entity\Listings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class ListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', TextType::class, [
                'label' => 'Company Name',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full']
            ])
            ->add('title', TextType::class, [
                'label' => 'Job Title',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full']
            ])
            ->add('location', TextType::class, [
                'label' => 'Job Location',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full']
            ])
            ->add('email', TextType::class, [
                'label' => 'Contact Email',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full']
            ])
            ->add('website', TextType::class, [
                'label' => 'Website/Application URL',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full']
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags (Comma Separated)',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full']
            ])
            ->add('logo', FileType::class, [
                'label' => 'Company Logo',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full'],
                'mapped' => false, // This will not be mapped directly to the entity.
                'required' => false,
                'constraints'=>[
                    new Image()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Job Description',
                'attr' => ['class' => 'border border-gray-200 rounded p-2 w-full', 'rows' => 10],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Listings::class,
        ]);
    }
}
