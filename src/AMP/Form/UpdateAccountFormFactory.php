<?php
namespace AMP\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateAccountFormFactory extends BaseFormFactory
{

    public function __construct(FormFactory $formService)
    {
        $this->form = $formService->createBuilder('form')
            ->add('username', 'text', array(
                'constraints' => new Assert\NotBlank(),
                'label_attr' => array('class' => 'formLabel'),
                'attr' => array('placeholder' => "Your username"),
            ))
            ->add('newPassword', 'text', array(
                'constraints' => new Assert\NotBlank(),
                'label_attr' => array('class' => 'formLabel'),
                'attr' => array(
                    'class' => 'passwordText',
                    'placeholder' => "New Password",
                ),
            ))
            ->add('submit', 'submit')
            ->getForm();
    }
}
