<?php
namespace AMP\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateAccountFormFactory extends BaseFormFactory
{

    public function __construct(FormFactory $formService)
    {
        $this->form = $formService->createBuilder('form')
            ->add('newPassword', 'password', array('constraints' => new Assert\NotBlank(),
                                                   'label' => 'New Password:',
                                                   'label_attr' => array('class' => 'formLabel'),))
            ->add('update', 'submit')
            ->getForm();
    }
}
