<?php
namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'    => 'Nom',
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Rechercher par nom...',
                    'class'       => 'form-control',
                ],
            ])
            ->add('prixMin', NumberType::class, [
                'label'    => 'Prix min (DT)',
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Ex: 100',
                    'class'       => 'form-control',
                ],
            ])
            ->add('prixMax', NumberType::class, [
                'label'    => 'Prix max (DT)',
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Ex: 5000',
                    'class'       => 'form-control',
                ],
            ])
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'titre',
                'label'        => 'Catégorie',
                'placeholder'  => '-- Toutes les catégories --',
                'required'     => false,
                'attr'         => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
