<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class Guess extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
      ->add('game',
        HiddenType::class,
        []
      )
			->add('number',
        NumberType::class,
        [
            'label' => ''
        ]
    	)
			->add('save',
        ButtonType::class,
        [
            'label' => 'Guess number'
        ]
    	);
	}
}