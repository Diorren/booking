<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrToDatetimeTransformer implements DataTransformerInterface
{
    // Transform les données originelles pour qu'elles puissent s'afficher dans un formulaire
    public function transform($date)
    {
        if($date === null){
            return '';
        }
        // Retourne une date en fr
        return $date->format('d/m/Y');
    }

    // C'est l'inverse, elle prend la donnée qui arrive du formulaire et la remet dans le format voulue
    public function reverseTransform($dateFr)
    {
        // Date en fr 21/03/2020
        if($dateFr === null){

            // Exception
            throw new TransformationFailedException("Fournir une date");
        } 

        $date = \DateTime::createFromFormat('d/m/Y',$dateFr);
        // Date en fr 21/03/2020
        if($date === null){

            // Exception
            throw new TransformationFailedException("Le format de la date n'est pas correct !");
        }
        return $date;
    }
}