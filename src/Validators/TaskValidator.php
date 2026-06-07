<?php

declare(strict_types=1); 

namespace App\Validators;

/**
 * Validator for task form data.
 */
Class TaskValidator {
    /**
     * Validate task form data.
     *
     * @param array $data Form data containing title and description
     * @return array List of validation errors, empty if valid
     */
    public function validate(array $data) {
        $errors = [];
    
        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Заголовок обязателен';
        }
    
        if (strlen($data['title'] ?? '') > 255) {
            $errors[] = 'Заголовок не должен превышать 255 символов';
        }
    
        return $errors;
    }
}