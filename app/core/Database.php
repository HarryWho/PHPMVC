<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Database Trait
 * Provides database connectivity and query execution
 * Uses singleton pattern for connection pooling
 */
trait Database
{
    /**
     * Static connection instance (singleton pattern)
     * This ensures only ONE database connection is created per request
     */
    private static ?PDO $instance = null;

    /**
     * Get or create database connection (singleton)
     * 
     * @return PDO|null The database connection
     */
    private static function getInstance(): ?PDO
    {
        if (self::$instance === null) {
            try {
                $constring = "mysql:host=" . DB_HOST . ":3306;dbname=" . DB_NAME;
                self::$instance = new PDO($constring, DB_USER, DB_PASS);

                // Set error mode to throw exceptions
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // For debugging in development
                if (defined('DEBUG') && DEBUG) {
                    logError("Database connection established", [], 'debug');
                }
            } catch (PDOException $e) {
                // Log the error securely
                logError(
                    "Database Connection Failed",
                    [
                        'host' => DB_HOST,
                        'database' => DB_NAME,
                        'error_code' => $e->getCode()
                    ],
                    'error'
                );

                // Show generic message to user
                if (defined('DEBUG') && DEBUG) {
                    die("Database connection error: " . $e->getMessage());
                } else {
                    die("Database connection error. Please contact support.");
                }
            }
        }

        return self::$instance;
    }

    /**
     * Connect to database (returns singleton instance)
     *
     * @return PDO|null The database connection
     */
    private function connect(): ?PDO
    {
        return self::getInstance();
    }

    /**
     * Execute a SELECT query and return all results
     *
     * @param string $query The SQL query with named parameters (e.g., :id)
     * @param array $data Associative array of parameter values
     * @return array|false Array of result objects, or false on failure
     */
    public function query(string $query, array $data = []): array|false
    {
        try {
            $con = $this->connect();

            if (!$con) {
                logError("Database connection returned null", [], 'error');
                return false;
            }

            $stmt = $con->prepare($query);

            if (!$stmt) {
                logError("Query prepare failed", ['query' => $query], 'error');
                return false;
            }

            $check = $stmt->execute($data);

            if ($check) {
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                if (is_array($result) && count($result)) {
                    return $result;
                }
            } else {
                logError("Query execute failed", ['query' => $query, 'error' => $stmt->errorInfo()], 'error');
            }
        } catch (Exception $e) {
            logError("Query exception", ['query' => $query, 'error' => $e->getMessage()], 'error');
        }

        return false;
    }

    /**
     * Execute a SELECT query and return the first result
     *
     * @param string $query The SQL query with named parameters
     * @param array $data Associative array of parameter values
     * @return object|false The first result object, or false on failure/no results
     */
    public function get_row(string $query, array $data = []): object|false
    {
        try {
            $con = $this->connect();

            if (!$con) {
                logError("Database connection returned null", [], 'error');
                return false;
            }

            $stmt = $con->prepare($query);

            if (!$stmt) {
                logError("Query prepare failed", ['query' => $query], 'error');
                return false;
            }

            $check = $stmt->execute($data);

            if ($check) {
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                if (is_array($result) && count($result)) {
                    return $result[0];
                }
            } else {
                logError("Query execute failed", ['query' => $query, 'error' => $stmt->errorInfo()], 'error');
            }
        } catch (Exception $e) {
            logError("Query exception", ['query' => $query, 'error' => $e->getMessage()], 'error');
        }

        return false;
    }

    /**
     * Execute a query strictly - throws exceptions on error
     * Use this when you want explicit error handling
     *
     * @param string $query The SQL query with named parameters
     * @param array $data Parameter values
     * @return array Array of objects (empty array if no results)
     * @throws DatabaseException If error occurs
     */
    public function queryStrict(string $query, array $data = []): array
    {
        try {
            $con = $this->connect();
            
            if (!$con) {
                throw new DatabaseException("Database connection failed", 0, $query, $data);
            }
            
            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                throw new DatabaseException("Query prepare failed", 0, $query, $data);
            }
            
            $check = $stmt->execute($data);
            
            if (!$check) {
                $errorInfo = $stmt->errorInfo();
                throw new DatabaseException(
                    "Query execution failed: " . ($errorInfo[2] ?? 'Unknown error'),
                    (int)($errorInfo[0] ?? 0),
                    $query,
                    $data
                );
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return is_array($result) ? $result : [];
            
        } catch (DatabaseException $e) {
            logError("Database query error", $e->getDetails(), 'error');
            throw $e;
        } catch (Exception $e) {
            logError("Unexpected query error", ['error' => $e->getMessage(), 'query' => $query], 'error');
            throw new DatabaseException($e->getMessage(), 0, $query, $data);
        }
    }

    /**
     * Get a single row strictly - throws exceptions on error
     *
     * @param string $query The SQL query with named parameters
     * @param array $data Parameter values
     * @return object|false The first result object, or false if no results
     * @throws DatabaseException If error occurs
     */
    public function get_row_strict(string $query, array $data = []): object|false
    {
        $results = $this->queryStrict($query, $data);
        return !empty($results) ? $results[0] : false;
    }
}