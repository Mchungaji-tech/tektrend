<?php
/**
 * Base Model
 * Provides common database operations for all models
 */

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Find by ID
     */
    public function find($id) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1",
            [$id]
        );
    }

    /**
     * Find by column
     */
    public function findBy($column, $value) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1",
            [$value]
        );
    }

    /**
     * Get all records
     */
    public function all($orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql);
    }

    /**
     * Get with pagination
     */
    public function paginate($page = 1, $perPage = null, $where = null, $params = []) {
        $perPage = $perPage ?: ITEMS_PER_PAGE;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM {$this->table}";
        $countSql = "SELECT COUNT(*) FROM {$this->table}";

        if ($where) {
            $sql .= " WHERE {$where}";
            $countSql .= " WHERE {$where}";
        }

        $total = $this->db->fetchColumn($countSql, $params);
        $sql .= " ORDER BY {$this->primaryKey} DESC LIMIT {$perPage} OFFSET {$offset}";

        $data = $this->db->fetchAll($sql, $params);

        return [
            'data'  => $data,
            'total' => $total,
            'page'  => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Insert record
     */
    public function create($data) {
        $data = $this->filterFillable($data);
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($values) - 1) . '?';

        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") VALUES ({$placeholders})";
        $id = $this->db->insert($sql, $values);

        return $id;
    }

    /**
     * Update record
     */
    public function update($id, $data) {
        $data = $this->filterFillable($data);
        if (empty($data)) {
            return 0;
        }

        $setParts = [];
        $values = [];
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, $values);
    }

    /**
     * Delete record
     */
    public function delete($id) {
        return $this->db->execute(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Count records
     */
    public function count($where = null, $params = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        return $this->db->fetchColumn($sql, $params);
    }

    /**
     * Raw query
     */
    public function raw($sql, $params = []) {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Raw query single
     */
    public function rawFirst($sql, $params = []) {
        return $this->db->fetch($sql, $params);
    }

    /**
     * Filter to fillable columns only
     */
    protected function filterFillable($data) {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Apply casts to data
     */
    protected function applyCasts($data) {
        foreach ($this->casts as $column => $type) {
            if (isset($data[$column])) {
                switch ($type) {
                    case 'int':
                    case 'integer':
                        $data[$column] = (int)$data[$column];
                        break;
                    case 'float':
                    case 'decimal':
                        $data[$column] = (float)$data[$column];
                        break;
                    case 'bool':
                    case 'boolean':
                        $data[$column] = (bool)$data[$column];
                        break;
                    case 'array':
                    case 'json':
                        $data[$column] = json_decode($data[$column], true);
                        break;
                }
            }
        }
        return $data;
    }
}
