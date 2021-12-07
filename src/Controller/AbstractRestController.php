<?php

namespace App\Controller;

use App\Exception\ResourceValidationException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractRestController extends AbstractFOSRestController
{
    protected function checkViolations(ConstraintViolationListInterface $violations): void
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }
    }
}