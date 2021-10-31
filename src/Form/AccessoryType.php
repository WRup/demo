<?php

namespace App\Form;

use App\Entity\Accessory;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class AccessoryType extends AbstractType
{
    private $slugger;

    // Form types are services, so you can inject other services in them if needed
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
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
            ])
            // form events let you modify information or fields at different steps
            // of the form handling process.
            // See https://symfony.com/doc/current/form/events.html
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Accessory */
                $accessory = $event->getData();
//                if (null !== $accessoryName = $accessory->getName()) {
//                    $accessory->setSlug($this->slugger->slug($accessoryName)->lower());
//                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accessory::class,
        ]);
        $resolver->setRequired('amountOfLoans');
        $resolver->setAllowedTypes('amountOfLoans', array('int'));
    }
}