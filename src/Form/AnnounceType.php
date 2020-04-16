<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnounceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,$this->getConfiguration('Titre',"Titre de l'annonce"))
            ->add('coverImage',UrlType::class,$this->getConfiguration('Image de couverture',"Insérez une image"))
            ->add('description',TextType::class,$this->getConfiguration('Résumé',"Présentez votre bien"))
            ->add('content',TextareaType::class,$this->getConfiguration('Description détaillée',"Décrivez vos services"))
            ->add('rooms',IntegerType::class,$this->getConfiguration('Nombre de chambres',"Nombre de chambres"))
            ->add('price',MoneyType::class,$this->getConfiguration('Prix',"Prix des chambres / nuit"))
            ->add('images',CollectionType::class,['entry_type'=>ImageType::class,'allow_add'=>true,'allow_delete'=>true])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
