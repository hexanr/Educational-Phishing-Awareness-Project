<?php
/**
 * Database Utility Class
 * 
 * Handles all database operations safely
 */

class Database {
    
    private static $connection = null;
    
    /**
     * Get database connection
     * 
     * @return mysqli Database connection
     * @throws Exception If connection fails
     */
    public static function connect(): mysqli {
        if (self::$connection !== null) {
            return self::$connection;
        }
        
        try {
            self::$connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME
            );
            
            // Check connection
            if (self::$connection->connect_error) {
                throw new Exception('Database connection failed: ' . self::$connection->connect_error);
            }
            
            // Set charset
            self::$connection->set_charset(DB_CHARSET);
            
            Logger::info('Database connected successfully');
            
        } catch (Exception $e) {
            Logger::error('Database connection error', ['error' => $e->getMessage()]);
            throw $e;
        }
        
        return self::$connection;
    }
    
    /**
     * Close database connection
     */
    public static function disconnect(): void {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
            Logger::info('Database connection closed');
        }
    }
    
    /**
     * Prepare a statement
     * 
     * @param string $query SQL query
     * @return mysqli_stmt Prepared statement
     * @throws Exception If prepare fails
     */
    public static function prepare(string $query): mysqli_stmt {
        $conn = self::connect();
        
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception('Statement preparation failed: ' . $conn->error);
        }
        
        return $stmt;
    }
    
    /**
     * Execute a query
     * 
     * @param string $query SQL query
     * @param array $params Parameters to bind
     * @return mixed Query result
     * @throws Exception If query fails
     */
    public static function query(string $query, array $params = []) {
        $stmt = self::prepare($query);
        
        // Bind parameters if provided
        if (!empty($params)) {
            $types = '';
            $values = [];
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }
            
            $stmt->bind_param($types, ...$values);
        }
        
        // Execute statement
        if (!$stmt->execute()) {
            throw new Exception('Query execution failed: ' . $stmt->error);
        }
        
        return $stmt;
    }
    
    /**
     * Get single result
     * 
     * @param string $query SQL query
     * @param array $params Parameters
     * @return array|null Result row
     * @throws Exception If query fails
     */
    public static function getOne(string $query, array $params = []): ?array {
        $stmt = self::query($query, $params);
        $result = $stmt->get_result();
        
        if ($result === false) {
            throw new Exception('Failed to get result: ' . $stmt->error);
        }
        
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row;
    }
    
    /**
     * Get all results
     * 
     * @param string $query SQL query
     * @param array $params Parameters
     * @return array Results
     * @throws Exception If query fails
     */
    public static function getAll(string $query, array $params = []): array {
        $stmt = self::query($query, $params);
        $result = $stmt->get_result();
        
        if ($result === false) {
            throw new Exception('Failed to get result: ' . $stmt->error);
        }
        
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $rows;
    }
    
    /**
     * Insert record
     * 
     * @param string $table Table name
     * @param array $data Data to insert
     * @return int Inserted ID
     * @throws Exception If insert fails
     */
    public static function insert(string $table, array $data): int {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $query = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(',', $columns),
            implode(',', $placeholders)
        );
        
        $stmt = self::query($query, $values);
        $insertId = $stmt->insert_id;
        $stmt->close();
        
        Logger::info('Record inserted', ['table' => $table, 'id' => $insertId]);
        
        return $insertId;
    }
    
    /**
     * Update record
     * 
     * @param string $table Table name
     * @param array $data Data to update
     * @param string $where Where clause
     * @param array $whereParams Where parameters
     * @return int Affected rows
     * @throws Exception If update fails
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int {
        $sets = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $sets[] = "$column = ?";
            $values[] = $value;
        }
        
        $values = array_merge($values, $whereParams);
        
        $query = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $sets),
            $where
        );
        
        $stmt = self::query($query, $values);
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        Logger::info('Records updated', ['table' => $table, 'affected' => $affectedRows]);
        
        return $affectedRows;
    }
    
    /**
     * Delete record
     * 
     * @param string $table Table name
     * @param string $where Where clause
     * @param array $whereParams Where parameters
     * @return int Affected rows
     * @throws Exception If delete fails
     */
    public static function delete(string $table, string $where, array $whereParams = []): int {
        $query = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        
        $stmt = self::query($query, $whereParams);
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        Logger::info('Records deleted', ['table' => $table, 'affected' => $affectedRows]);
        
        return $affectedRows;
    }
    
    /**
     * Count records
     * 
     * @param string $table Table name
     * @param string $where Where clause
     * @param array $whereParams Where parameters
     * @return int Record count
     * @throws Exception If query fails
     */
    public static function count(string $table, string $where = '', array $whereParams = []): int {
        $query = "SELECT COUNT(*) as count FROM $table";
        
        if (!empty($where)) {
            $query .= " WHERE $where";
        }
        
        $result = self::getOne($query, $whereParams);
        
        return $result['count'] ?? 0;
    }
    
    /**
     * Check if record exists
     * 
     * @param string $table Table name
     * @param string $where Where clause
     * @param array $whereParams Where parameters
     * @return bool True if exists
     * @throws Exception If query fails
     */
    public static function exists(string $table, string $where, array $whereParams = []): bool {
        return self::count($table, $where, $whereParams) > 0;
    }
}

?>
