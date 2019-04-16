<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *Sport event manager
 *
 */
class EventManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'evenement';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Get one row from database by ID. Override Abstract Method
     *
     * @param  int $id
     *
     * @return array
     */
    public function selectOneEventToComeById(int $id):array
    {
        $statement = "
        SELECT evenement.id, 
            evenement.title, 
            evenement.descr,
            DATE_FORMAT(evenement.date_begin,'%d/%m/%Y') as date_begin,
            DATE_FORMAT(evenement.date_end,'%d/%m/%Y') as date_end,
            evenement.location, 
            DATE_FORMAT(evenement.date_register,'%d/%m/%Y') as date_register,
            evenement.rulesfile_id,
            evenement.article_id,
            departement.name as dept_name, departement.numdept as dept_num,
            level.name as level,
            gendermix.name as gendermix,
            evtcategory.name as category,
            evttype.name as type
         FROM evenement
        LEFT JOIN departement ON evenement.departement_id = departement.id
        LEFT JOIN level ON evenement.level_id = level.id
        LEFT JOIN gendermix ON evenement.gendermix_id = gendermix.id
        LEFT JOIN evtcategory ON evenement.category_id = evtcategory.id
        LEFT JOIN evttype ON evenement.type_id = evttype.id
        WHERE evenement.id=:id";

        // prepared request
        $statement = $this->pdo->prepare($statement);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
  
    /**
     * Get all row from database - Event Coming soon
     *
     * @return array
     */
    public function selectEventsToCome(): array
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        VALUES (NULL, :title, :descr, :date_begin, :date_begin, :departement_id, :location, 
        :level_id, :category_id, :type_id, :gendermix_id, :date_register, :rulesfile_id, :article_id)");
        $statement->bindValue('title', $events['title'], \PDO::PARAM_STR);
        $statement->bindValue('descr', $events['descr'], \PDO::PARAM_STR);
        $statement->bindValue('date_begin', $events['date_begin'], \PDO::PARAM_STR);
        $statement->bindValue('date_begin', $events['date_begin'], \PDO::PARAM_STR);
        $statement->bindValue('departement_id', $events['departement_id'], \PDO::PARAM_INT);
        $statement->bindValue('location', $events['location'], \PDO::PARAM_STR);
        $statement->bindValue('level_id', $events['level_id'], \PDO::PARAM_INT);
        $statement->bindValue('category_id', $events['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('type_id', $events['type_id'], \PDO::PARAM_INT);
        $statement->bindValue('gendermix_id', $events['gendermix_id'], \PDO::PARAM_INT);
        $statement->bindValue('date_register', $events['date_register'], \PDO::PARAM_STR);
        $statement->bindValue('rulesfile_id', $events['rulesfile_id'], \PDO::PARAM_INT);
        $statement->bindValue('article_id', $events['article_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
