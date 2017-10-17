<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/17/17
 * Time: 1:18 PM
 */

namespace App\Packages\Human;


class HumanElasticSearchRequest
{
    private $request;

    private $conditions = [];
    private $searchAfter = -1;

    public function __construct()
    {
        $this->request = [
            'index' => HumanGatewayConstants::TABLE,
            'type' => HumanGatewayConstants::TABLE,
            'body' => [
                'size' => 1000,
                'query' => [
                    'bool' => [
                        'must' => [
                            ['wildcard' => [HumanGatewayConstants::FIRST_NAME => ['value' => '*a*']]],
                            ['wildcard' => [HumanGatewayConstants::LAST_NAME => ['value' => '*ac*']]],
                            ['term' => [HumanGatewayConstants::AGE => 60]],
                            ['match' => [HumanGatewayConstants::GENDER => 'male']]

                        ],
                    ]
                ],
                'search_after' => [HumanGatewayConstants::TABLE . '#' . 1024073],
                'sort' => [
                    ['_uid' => 'asc']
                ]
            ]
        ];
    }

    public function addFirstNameKeywords(string $firstNameKeyWords)
    {
        if ($firstNameKeyWords) {
            $firstNameKeyWords = '*' . $firstNameKeyWords . '*';
            $this->conditions[] = ['wildcard' => [HumanGatewayConstants::FIRST_NAME => ['value' => $firstNameKeyWords]]];
        }
        return $this;
    }

    public function addLastNameKeywords($lastNameKeyWords)
    {
        if ($lastNameKeyWords) {
            $lastNameKeyWords = '*' . $lastNameKeyWords . '*';
            $this->conditions[] = ['wildcard' => [HumanGatewayConstants::LAST_NAME => ['value' => $lastNameKeyWords]]];
        }
        return $this;
    }

    public function addAge(int $age)
    {
        if ($age >= 0) {
            $this->conditions[] = ['term' => [HumanGatewayConstants::AGE => $age]];
        }
        return $this;
    }

    public function addGender(string $gender)
    {
        if ($gender) {
            ['match' => [HumanGatewayConstants::GENDER => $gender]];
        }
        return $this;
    }

    public function addSearchAfter(int $id)
    {
        if ($id > 0) {
            $this->searchAfter = $id;
        }
        return $this;
    }

    public function getRequest()
    {
        $request = [
            'index' => HumanGatewayConstants::TABLE,
            'type' => HumanGatewayConstants::TABLE,
            'body' => [
                'size' => 1000,
                'sort' => [
                    ['id' => 'asc']
                ]
            ]
        ];
        if (count($this->conditions) > 0) {
            $request['body']['query'] = [
                'bool' => [
                    'must' => $this->conditions
                ]
            ];
        }
        if ($this->searchAfter > 0) {
            $request['body']['search_after'] = [$this->searchAfter];
        }
        return $request;
    }
}
