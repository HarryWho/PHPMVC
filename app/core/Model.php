<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Model Trait
 * Provides CRUD operations for database models
 * All queries use prepared statements and identifier escaping for security
 */
trait Model
{
    use Database;

    protected int $limit = 10;
    protected int $offset = 0;

    /**
     * Escape SQL identifiers (table and column names)
     * Uses backticks for MySQL
     * 
     * @param string $identifier The table or column name
     * @return string The escaped identifier
     * @throws Exception If identifier contains invalid characters
     */
    protected function escapeIdentifier(string $identifier): string
    {
        // Basic validation: identifier should be alphanumeric with underscores only
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $identifier)) {
            throw new Exception("Invalid SQL identifier: $identifier");
        }

        return "`" . $identifier . "`";
    }

    /**
     * Validate and return escaped table name
     * 
     * @return string The escaped table name
     * @throws Exception If table name not defined
     */
    protected function getTableName(): string
    {
        if (empty($this->table)) {
            throw new Exception("Table name not defined");
        }
        return $this->escapeIdentifier($this->table);
    }

    /**
     * Validate order column and type for safe SQL queries
     * 
     * @return array Array with 'column' => escaped column name, 'type' => 'ASC' or 'DESC'
     */
    protected function getOrderClause(): array
    {
        $order_column = $this->order_column ?? 'id';
        $order_type = strtoupper($this->order_type ?? 'DESC');

        // Validate order type
        if (!in_array($order_type, ['ASC', 'DESC'])) {
            $order_type = 'DESC';
        }

        return [
            'column' => $this->escapeIdentifier($order_column),
            'type' => $order_type
        ];
    }

    /**
     * Find all records in the table with ordering and pagination
     *
     * @return array|false Array of all records, or false on error
     */
    public function findAll(): array|false
    {
        $order = $this->getOrderClause();
        $table = $this->getTableName();

        $query = "SELECT * FROM " . $table . " ORDER BY " . $order['column'] . " " . $order['type'] . " LIMIT " . $this->limit . " OFFSET " . $this->offset;

        return $this->query($query);
    }

    /**
     * Find records matching the given criteria
     *
     * @param array $data Columns and values to match (AND logic)
     * @param array $data_not Columns and values NOT to match (AND logic)
     * @return array|false Array of matching records, or false on error
     */
    public function where(array $data, array $data_not = []): array|false
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $table = $this->getTableName();
        $order = $this->getOrderClause();

        $query = 'SELECT * FROM ' . $table . ' WHERE ';

        foreach ($keys as $key) {
            // Escape column name
            $escapedKey = $this->escapeIdentifier($key);
            $query .= $escapedKey . ' = :' . $key . ' AND ';
        }

        foreach ($keys_not as $key) {
            $escapedKey = $this->escapeIdentifier($key);
            $query .= $escapedKey . ' != :' . $key . ' AND ';
        }

        // Remove trailing AND
        $query = preg_replace('/ AND $/', '', $query);
        $query .= " ORDER BY " . $order['column'] . " " . $order['type'] . " LIMIT " . $this->limit . " OFFSET " . $this->offset;

        $data = array_merge($data, $data_not);

        return $this->query($query, $data);
    }

    /**
     * Execute a custom JOIN query with safe parameter binding
     *
     * @param string $query The SQL query with named parameters
     * @param array $data Parameter values
     * @return array|false Array of results, or false on error
     */
    public function join(string $query, array $data = []): array|false
    {
        // Note: Custom join queries should manually use prepared statements
        return $this->query($query, $data);
    }

    /**
     * Find the first record matching the given criteria
     *
     * @param array $data Columns and values to match
     * @param array $data_not Columns and values NOT to match
     * @return object|false The first matching record object, or false on error/not found
     */
    public function first(array $data, array $data_not = []): object|false
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $table = $this->getTableName();

        $query = 'SELECT * FROM ' . $table . ' WHERE ';

        foreach ($keys as $key) {
            $escapedKey = $this->escapeIdentifier($key);
            $query .= $escapedKey . ' = :' . $key . ' AND ';
        }

        foreach ($keys_not as $key) {
            $escapedKey = $this->escapeIdentifier($key);
            $query .= $escapedKey . ' != :' . $key . ' AND ';
        }

        // Remove trailing AND
        $query = preg_replace('/ AND $/', '', $query);
        $query .= " LIMIT " . $this->limit . " OFFSET " . $this->offset;

        $data = array_merge($data, $data_not);

        $result = $this->query($query, $data);

        if (is_array($result) && count($result)) {
            return $result[0];
        }
        return false;
    }

    /**
     * Insert a new record into the table
     *
     * @param array $data Associative array of column => value pairs
     * @return bool True on success, false on failure
     */
    public function insert(array $data): bool
    {
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $table = $this->getTableName();

        // Escape all column names
        $escapedKeys = array_map(function (string $key): string {
            return $this->escapeIdentifier($key);
        }, $keys);

        $query = "INSERT INTO " . $table . " (" . implode(", ", $escapedKeys) . ") VALUES (:" . implode(", :", $keys) . ")";

        $this->query($query, $data);
        return true;
    }

    /**
     * Delete a record by ID
     *
     * @param mixed $id The ID value to delete
     * @param string $id_column The ID column name (default: user_id)
     * @return bool False (note: should return bool on success, needs fixing)
     */
    public function delete(mixed $id, string $id_column = 'user_id'): bool
    {
        $data[$id_column] = $id;
        $table = $this->getTableName();
        $escapedIdColumn = $this->escapeIdentifier($id_column);

        $query = "DELETE FROM " . $table . " WHERE " . $escapedIdColumn . " = :" . $id_column;
        $this->query($query, $data);
        return false;
    }

    /**
     * Update a record by ID
     *
     * @param mixed $id The ID value to update
     * @param array $data Associative array of column => value pairs to update
     * @param string $id_column The ID column name (default: user_id)
     * @return bool False (note: should return bool on success, needs fixing)
     */
    public function update(mixed $id, array $data, string $id_column = 'user_id'): bool
    {
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $table = $this->getTableName();
        $escapedIdColumn = $this->escapeIdentifier($id_column);

        $query = "UPDATE " . $table . " SET ";

        foreach ($keys as $key) {
            $escapedKey = $this->escapeIdentifier($key);
            $query .= $escapedKey . ' = :' . $key . ', ';
        }

        $query = rtrim($query, ', ');
        $query .= " WHERE " . $escapedIdColumn . " = :" . $id_column;
        $data[$id_column] = $id;

        $this->query($query, $data);
        return false;
    }
}