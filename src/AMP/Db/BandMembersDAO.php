<?php
namespace AMP\Db;

use \AMP\Exception\DbException;

class BandMembersDAO extends AbstractDAO
{
    public function getTableName()
    {
        return 'band_members';
    }

    public function add(array $data)
    {
        try {
            $sql = 'INSERT INTO ' . $this->getTableName() . ' (first_name, last_name, roles, photo_filename, bio)
                    VALUES (:first_name, :last_name, :roles, :photo_filename, :bio)';
            $stmt = $this->getDb()->prepare($sql);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':roles', $data['roles']);
            $stmt->bindParam(':photo_filename', $filename);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = :id';
            $stmt = $this->getDb()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(0);
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $sql = 'SELECT * FROM ' . $this->getTableName();
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        try {
            $sql = 'UPDATE ' . $this->getTableName() . '
                    SET first_name = :first_name,
                        last_name = :last_name,
                        roles = :roles,
                        photo_filename = :photo_filename,
                        bio = :bio
                    WHERE id = :id';
            $stmt = $this->getDb()->prepare($sql);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':roles', $data['roles']);
            $stmt->bindParam(':photo_filename', $filename);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $sql = 'DELETE from ' . $this->getTableName() . ' WHERE id=:id';
            $stmt = $this->getDb()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }
}
