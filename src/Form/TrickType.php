<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('publicationStatusTrick')
          /*  ->add('Category')
            ->add('picture', FileType::class, [
                'label' => "Add Pictures :",
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid jpeg image',
                    ])
                ],
            ])*/
              ->add('picture', CollectionType::class,[
              'entry_type' => PictureType::class,
              'by_reference' => false,
              "allow_add" => true,
              "allow_delete" => true
          ])
            ->add('video',CollectionType::class,[
              'entry_type' => VideoType::class,
                'by_reference' => false,
              'allow_add' => true,
              'allow_delete'=> true
          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
