<?php

namespace App\Form;

use App\Entity\Accessory;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;

class AccessoryType extends AbstractType
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'label.image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => ['autofocus' => true],
                'label' => 'label.name',
            ])
            ->add('model', TextType::class, [
                'label' => 'label.model',
            ])
            ->add('manufacturer', TextType::class, [
                'label' => 'label.manufacturer',
            ])
            ->add('url', TextType::class, [
                'label' => 'label.url'
            ])
            ->add('content', null, [
                'attr' => ['rows' => 20],
                'label' => 'label.content',
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => array('min' => $options['amountOfLoans']),
                'label' => 'label.quantity',
                'help' => 'help.quantity',
                'help_translation_parameters' => [
                    '%amountOfLoans%' => $options['amountOfLoans'],
                ],
            ])
            ->add('tags', TagsInputType::class, [
                'label' => 'label.tags',
                'required' => false,
                'help' => 'help.tags'
            ]);
//            // form events let you modify information or fields at different steps
//            // of the form handling process.
//            // See https://symfony.com/doc/current/form/events.html
//            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
//                /** @var Accessory */
//                $accessory = $event->getData();
////                if (null !== $accessoryName = $accessory->getName()) {
////                    $accessory->setSlug($this->slugger->slug($accessoryName)->lower());
////                }
//            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accessory::class,
        ]);
        $resolver->setDefault('amountOfLoans', 0);
        $resolver->setAllowedTypes('amountOfLoans', array('int'));
    }
}