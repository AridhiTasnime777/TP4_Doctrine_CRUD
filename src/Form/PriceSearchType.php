<?php
namespace App\Form;

use App\Entity\PriceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minPrice', IntegerType::class, [
                'label'    => 'Prix min',
                'required' => false,
                'attr'     => ['class' => 'form-control', 'placeholder' => 'Prix minimum']
            ])
            ->add('maxPrice', IntegerType::class, [
                'label'    => 'Prix max',
                'required' => false,
                'attr'     => ['class' => 'form-control', 'placeholder' => 'Prix maximum']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => PriceSearch::class]);
    }
}
