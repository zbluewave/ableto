<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/15/17
 * Time: 9:09 PM
 */

namespace App\Packages\Human;

use App\Core\DatabaseFactory;

class HumanGateway
{
    private $humanHydrator;
    private $dbFactory;

    public function __construct(DatabaseFactory $dbFactory, HumanHydrator $humanHydrator)
    {
        $this->dbFactory = $dbFactory;
        $this->humanHydrator = $humanHydrator;
    }

    /**
     * @param Human $human
     * @return Human
     */
    public function insertHuman(Human $human)
    {

        $id = $this->dbFactory->getDbConnection()->table(HumanGatewayConstants::TABLE)
            ->insertGetId($this->humanHydrator->mapForDbCreation($human));
        $human->setId($id);
        return $human;
    }
}
