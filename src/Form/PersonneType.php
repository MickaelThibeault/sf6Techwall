<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Personne;
use App\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options['data']->getProfile()->getRs());
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('profile', ProfileType::class, [
                'label'=> 'Réseau social',
                'required'=> false,
            ])
            ->add('hobbies', EntityType::class, [
                'class'=> Hobby::class,
                'required'=> false,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.designation', 'ASC');
                },
                'choice_label'=> 'designation',
                'expanded'=> false,
                'multiple'=> true,
                'attr'=> ['class'=> 'select2']
            ])
            ->add('job', EntityType::class, [
                'class'=> Job::class,
                'required'=> false,
                'placeholder'=> 'Sélectionnez une option',
                'choice_label'=> 'designation',
                'attr'=> ['class'=> 'select2']
            ])
            ->add('photo', FileType::class, [
                'label'=> 'Votre image de profil (Des fichiers images uniquement)',
                'mapped'=> false,
                'required'=> false,
                'constraints'=> [
                    new File([
                        'maxSize' => '5072k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please uploads a valid Image',
                    ])
                ]
            ])
            ->add('editer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
