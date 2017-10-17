<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/17/17
 * Time: 5:20 PM
 */

namespace App\Packages\Human;


class HumanHydrator
{
    /**
     * @param Human $human
     * @return array
     */
    public function mapForDbCreation(Human $human)
    {
        $fields = $human->toArray();
        unset($fields[HumanGatewayConstants::ID]);
        return $fields;
    }

    /**
     * @param array $data
     * @return Human
     */
    public function generateHumanFromArray(array $data)
    {
        return (new Human())
            ->setId($data[HumanGatewayConstants::ID])
            ->setFirstName($data[HumanGatewayConstants::FIRST_NAME])
            ->setLastName($data[HumanGatewayConstants::LAST_NAME])
            ->setAge($data[HumanGatewayConstants::AGE])
            ->setGender($data[HumanGatewayConstants::GENDER]);
    }
}
