<?php
namespace App\Packages\Human;

use Faker\Factory;
use Faker\Generator;

class HumanGeneration
{
    private $generator;
    private $humanGateway;
    private $humanElasticGateway;

    public function __construct(HumanGateway $humanGateway, HumanElasticGateway $humanElasticGateway)
    {
        $this->generator = Factory::create(Factory::DEFAULT_LOCALE);
        $this->humanGateway = $humanGateway;
        $this->humanElasticGateway = $humanElasticGateway;
    }

    public function persistHumans()
    {
        //$this->humanElasticGateway->deleteIndex();
        //$this->humanElasticGateway->buildIndex();
        $i = 0;
        $humans = [];
        foreach ($this->generationHumans() as $human) {
            $humans[] = $human;
            $i++;
            if ($i % 10000 == 0) {
                $this->humanElasticGateway->indexHumans($humans);
                $humans = [];
            }
        }
        if (count($humans) > 0) {
            $this->humanElasticGateway->indexHumans($humans);
        }

    }

    /**
     * @return Human[]
     */
    public function generationHumans()
    {
        for ($i = 0; $i < 1000000; $i++) {
            if ($i % 2 == 0) {
                $human = $this->generateMale($i);
            } else {
                $human = $this->generateFemale($i);
            }
            yield $human;
        }
    }

    private function generateMale(int $id)
    {
        return (new Human())
            ->setId($id)
            ->setAge($this->generator->numberBetween(0, 99))
            ->setFirstName($this->generator->firstNameMale)
            ->setLastName($this->generator->lastName)
            ->setGender(Human::GENDER_MALE);
    }

    private function generateFemale(int $id)
    {
        return (new Human())
            ->setId($id)
            ->setAge($this->generator->numberBetween(0, 99))
            ->setFirstName($this->generator->firstNameFemale)
            ->setLastName($this->generator->lastName)
            ->setGender(Human::GENDER_FEMALE);
    }
}
