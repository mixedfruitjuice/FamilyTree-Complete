<?php

class Stamboom
{
    /**
     * @var PDO
     *
     * Holdst the database connection.
     */
    public $connection;

    /**
     * Stamboom constructor.
     * @param PDO $db_connection
     *
     * Set the database connection. Must be an instance of PDO.
     */
    function __construct(PDO $db_connection)
    {
        $this->connection = $db_connection;
    }

    /**
     * @param $person_id
     * @return array
     *
     * Get a person from the database.
     */
    public function getPerson($person_id)
    {
        $sql = "SELECT * FROM persoon WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$person_id]);
        return $stmt->fetch();
    }

    /**
     * @param $person_id
     * @return bool
     *
     * Checks if a person exists.
     */
    public function personExists($person_id)
    {
        $sql = "SELECT count(*) FROM persoon WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$person_id]);
        if ($stmt->fetch()[0] != 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $person_id
     * @return array
     *
     * Get all stories that are linked to a person.
     */
    public function getStories($person_id)
    {
        $sql = "SELECT * FROM verhaal v, verhaal_persoon_relatie vpr WHERE v.id=vpr.verhaal_id AND vpr.persoon_id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$person_id]);
        return $stmt->fetchAll();
    }

}