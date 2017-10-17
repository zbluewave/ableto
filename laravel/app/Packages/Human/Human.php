<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/15/17
 * Time: 8:09 PM
 */

namespace App\Packages\Human;

use Illuminate\Contracts\Support\Arrayable;

 /**
 * 		@SWG\Definition(
 * 			definition="human",
 *          type="object",
 * 			required={"id", "first_name", "last_name", "age", "gender"},
 * 		),
 */

class Human implements Arrayable
{
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * @SWG\Property()
     * @var int
     */
    private $id;

    /**
     * @SWG\Property(property="first_name")
     * @var string
     */
    private $firstName;

    /**
     * @SWG\Property(property="last_name"),
     * @var string
     */
    private $lastName;

    /**
     * @SWG\Property(property="gender", ref="#/definitions/gender")
     * @var string
     */
    private $gender;

    /**
     * @SWG\Property(type="string")
     * @var int
     */
    private $age;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Human
     */
    public function setId(int $id): Human
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Human
     */
    public function setFirstName(string $firstName): Human
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Human
     */
    public function setLastName(string $lastName): Human
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return Human
     */
    public function setGender(string $gender): Human
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return Human
     */
    public function setAge(int $age): Human
    {
        $this->age = $age;
        return $this;
    }

    public function toArray()
    {
        return [
            HumanGatewayConstants::ID => $this->getId(),
            HumanGatewayConstants::FIRST_NAME => $this->getFirstName(),
            HumanGatewayConstants::LAST_NAME => $this->getLastName(),
            HumanGatewayConstants::AGE => $this->getAge(),
            HumanGatewayConstants::GENDER => $this->getGender()
        ];
    }
}
