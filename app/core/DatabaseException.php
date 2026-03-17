<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Custom exception for database operations
 * Used for query execution failures, connection errors, etc.
 */
class DatabaseException extends Exception
{
    /**
     * The SQL query that caused the error (if applicable)
     */
    private string $query = '';
    
    /**
     * The query parameters
     */
    private array $params = [];
    
    /**
     * Constructor
     *
     * @param string $message Error message
     * @param int $code Error code
     * @param string $query The SQL query (optional)
     * @param array $params Query parameters (optional)
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        string $query = "",
        array $params = []
    ) {
        parent::__construct($message, $code);
        $this->query = $query;
        $this->params = $params;
    }
    
    /**
     * Get the query that caused the error
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
    
    /**
     * Get the query parameters
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
    
    /**
     * Get formatted error details for logging
     *
     * @return array
     */
    public function getDetails(): array
    {
        return [
            'message' => $this->message,
            'query' => $this->query,
            'params' => $this->params,
            'code' => $this->code,
            'file' => $this->file,
            'line' => $this->line
        ];
    }
}
