<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Input Validation Framework
 * 
 * Provides chainable validation API for form data validation
 * Supports built-in validators: required, email, password, username, min_length, max_length, matches
 * Allows custom validation rules
 * 
 * Usage:
 *   $errors = Validator::validate($data)
 *       ->required('user_name', 'Username')
 *       ->email('user_email', 'Email')
 *       ->password('user_password', 'Password')
 *       ->getErrors();
 * 
 * @package PHPMVC
 */
class Validator
{
    /**
     * @var array Array of field names being validated
     */
    private array $data = [];

    /**
     * @var array Array of validation errors collected
     */
    private array $errors = [];

    /**
     * @var array Array of validation rules to apply
     */
    private array $rules = [];

    /**
     * Private constructor - use static validate() factory method
     * 
     * @param array $data The data to validate
     */
    private function __construct(array $data = [])
    {
        $this->data = $data;
        $this->errors = [];
        $this->rules = [];
    }

    /**
     * Factory method to create a new Validator instance
     * 
     * @param array $data The data to validate
     * @return self
     */
    public static function validate(array $data = []): self
    {
        return new self($data);
    }

    /**
     * Validate that a field is not empty
     * 
     * @param string $field The field name
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        
        $value = $this->data[$field] ?? '';
        
        if (empty(trim($value))) {
            $this->errors[$field . '_error'] = "$label is required.";
        }
        
        return $this;
    }

    /**
     * Validate that a field is a valid email format
     * 
     * @param string $field The field name
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field . '_error'] = "Invalid $label format.";
        }
        
        return $this;
    }

    /**
     * Validate minimum string length
     * 
     * @param string $field The field name
     * @param int $min Minimum length required
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function minLength(string $field, int $min = 1, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field . '_error'] = "$label must be at least $min characters.";
        }
        
        return $this;
    }

    /**
     * Validate maximum string length
     * 
     * @param string $field The field name
     * @param int $max Maximum length allowed
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function maxLength(string $field, int $max = 255, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field . '_error'] = "$label cannot exceed $max characters.";
        }
        
        return $this;
    }

    /**
     * Validate that password meets minimum complexity requirements
     * Minimum 8 characters with at least one uppercase, one lowercase, and one digit
     * 
     * @param string $field The field name
     * @param int $minLength Minimum password length (default 8)
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function password(string $field, int $minLength = 8, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (empty($value)) {
            // Don't validate empty passwords - let required() handle it
            return $this;
        }
        
        if (strlen($value) < $minLength) {
            $this->errors[$field . '_error'] = "$label must be at least $minLength characters.";
            return $this;
        }
        
        // Check for complexity: at least one uppercase, one lowercase, one digit
        $hasUpper = preg_match('/[A-Z]/', $value);
        $hasLower = preg_match('/[a-z]/', $value);
        $hasDigit = preg_match('/[0-9]/', $value);
        
        if (!($hasUpper && $hasLower && $hasDigit)) {
            $this->errors[$field . '_error'] = "$label must contain uppercase, lowercase, and numbers.";
        }
        
        return $this;
    }

    /**
     * Validate that two fields match (useful for password confirmation)
     * 
     * @param string $field The field to validate
     * @param string $matchField The field to match against
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function matches(string $field, string $matchField, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        
        $value = $this->data[$field] ?? '';
        $matchValue = $this->data[$matchField] ?? '';
        
        if ($value !== $matchValue) {
            $this->errors[$field . '_error'] = "$label does not match.";
        }
        
        return $this;
    }

    /**
     * Validate that a username contains only alphanumeric characters and underscores
     * 
     * @param string $field The field name
     * @param int $minLength Minimum length required (default 3)
     * @param int $maxLength Maximum length allowed (default 20)
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function username(string $field, int $minLength = 3, int $maxLength = 20, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (empty($value)) {
            return $this; // Let required() handle empty
        }
        
        if (strlen($value) < $minLength || strlen($value) > $maxLength) {
            $this->errors[$field . '_error'] = "$label must be $minLength-$maxLength characters.";
            return $this;
        }
        
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
            $this->errors[$field . '_error'] = "$label can only contain letters, numbers, and underscores.";
        }
        
        return $this;
    }

    /**
     * Validate that a field matches a regex pattern
     * 
     * @param string $field The field name
     * @param string $pattern The regex pattern (without delimiters)
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function regex(string $field, string $pattern, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !preg_match("/$pattern/", $value)) {
            $this->errors[$field . '_error'] = "$label format is invalid.";
        }
        
        return $this;
    }

    /**
     * Validate that a field is numeric
     * 
     * @param string $field The field name
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field . '_error'] = "$label must be numeric.";
        }
        
        return $this;
    }

    /**
     * Validate that a field is an integer
     * 
     * @param string $field The field name
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function integer(string $field, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field . '_error'] = "$label must be an integer.";
        }
        
        return $this;
    }

    /**
     * Validate that a field value is within a set of allowed values
     * 
     * @param string $field The field name
     * @param array $allowed Array of allowed values
     * @param string $label Human-readable label for error messages
     * @return self For method chaining
     */
    public function inArray(string $field, array $allowed, string $label = ''): self
    {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        $value = $this->data[$field] ?? '';
        
        if (!empty($value) && !in_array($value, $allowed, true)) {
            $this->errors[$field . '_error'] = "$label is not a valid choice.";
        }
        
        return $this;
    }

    /**
     * Add a custom validation rule using a callback function
     * 
     * @param string $field The field name
     * @param callable $callback Function that returns true if valid, false otherwise
     * @param string $errorMessage Custom error message if validation fails
     * @return self For method chaining
     */
    public function custom(string $field, callable $callback, string $errorMessage = ''): self
    {
        $value = $this->data[$field] ?? '';
        
        if (!call_user_func($callback, $value)) {
            $errorMessage = $errorMessage ?: ucfirst(str_replace('_', ' ', $field)) . ' is invalid.';
            $this->errors[$field . '_error'] = $errorMessage;
        }
        
        return $this;
    }

    /**
     * Set a custom error message for a field
     * Useful for adding conditional errors from outside validation rules
     * 
     * @param string $field The field name
     * @param string $message The error message
     * @return self For method chaining
     */
    public function addError(string $field, string $message): self
    {
        $fieldKey = strpos($field, '_error') !== false ? $field : $field . '_error';
        $this->errors[$fieldKey] = $message;
        return $this;
    }

    /**
     * Get all collected validation errors
     * 
     * @return array Array of errors indexed by field_error key
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if there are any validation errors
     * 
     * @return bool True if there are errors, false if valid
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Check if validation passed (no errors)
     * 
     * @return bool True if valid, false if there are errors
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get a single field's error message
     * 
     * @param string $field The field name to get error for
     * @return string|null The error message or null if no error
     */
    public function getError(string $field): ?string
    {
        $fieldKey = strpos($field, '_error') !== false ? $field : $field . '_error';
        return $this->errors[$fieldKey] ?? null;
    }
}
