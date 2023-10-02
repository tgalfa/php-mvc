<?php

namespace App\Core;

use AllowDynamicProperties;

#[AllowDynamicProperties]
abstract class DbModel extends Model
{
    /**
     * Return the table attributes.
     */
    abstract public function attributes(): array;

    /**
     * Return the table name.
     */
    abstract public static function tableName(): string;

    /**
     * Get the primary key for the model.
     */
    public static function primaryKey(): string
    {
        return 'id';
    }

    /**
    * Prepare the SQL statement.
    */
    public static function prepare(string $sql): \PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    /**
     * Find one record from the database.
     */
    public static function findOne(array $where): static|bool
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode('AND ', array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetchObject(static::class);
    }

    /**
     * Find all records from the database.
     */
    public static function findAll(array $select = ['*'], array $where = []): array
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $condition = implode('AND ', array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $sql = 'SELECT ' . implode(',', $select) . " FROM $tableName";

        if (!empty($where)) {
            $sql .= " WHERE $condition";
        }

        $statement = self::prepare($sql);

        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Save the model to the database.
     */
    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ') 
            VALUES (' . implode(',', $params) . ')');

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        return $statement->execute();
    }

    /**
     * Update a record in the database.
     */
    public function update(array $where): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => "$attr = :$attr", $attributes);
        $sql = implode(',', $params);
        $condition = implode('AND ', array_map(fn ($attr) => "$attr = :$attr", array_keys($where)));
        $statement = self::prepare("UPDATE $tableName SET $sql WHERE $condition");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }

    /**
     * Delete a record from the database.
     */
    public function delete(array $where = []): bool
    {
        $tableName = $this->tableName();

        if (empty($where)) {
            $where = [$this->primaryKey() => $this->{$this->primaryKey()}];
        }

        $condition = implode('AND ', array_map(fn ($attr) => "$attr = :$attr", array_keys($where)));
        $statement = self::prepare("DELETE FROM $tableName WHERE $condition");

        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }
}
