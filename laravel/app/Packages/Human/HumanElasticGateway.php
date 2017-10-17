<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/15/17
 * Time: 11:46 PM
 */

namespace App\Packages\Human;

use App\Core\ElasticSearchClientFactory;

class HumanElasticGateway
{
    private $elasticSearchClientFactory;
    private $humanHydrator;

    public function __construct(
        ElasticSearchClientFactory $elasticSearchClientFactory,
        HumanHydrator $humanHydrator
    ) {
        $this->elasticSearchClientFactory = $elasticSearchClientFactory;
        $this->humanHydrator = $humanHydrator;
    }

    public function buildIndex()
    {
        $params = [
            'index' => HumanGatewayConstants::TABLE,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2,
                    'analysis'=> [
                        'normalizer' => [
                            'case_insensitive' => [
                                'filter' => [
                                    'lowercase'
                                ]
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    HumanGatewayConstants::TABLE => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            HumanGatewayConstants::ID => [
                                'type' => 'integer'
                            ],
                            HumanGatewayConstants::FIRST_NAME => [
                                'type' => 'keyword',
                                'normalizer' => 'case_insensitive'
                            ],
                            HumanGatewayConstants::LAST_NAME => [
                                'type' => 'keyword',
                                'normalizer' => 'case_insensitive'
                            ],
                            HumanGatewayConstants::AGE => [
                                'type' => 'integer'
                            ],
                            HumanGatewayConstants::GENDER => [
                                'type' => 'keyword'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->elasticSearchClientFactory->getClient()->indices()->create($params);
    }

    public function deleteIndex()
    {
        $params = ['index' => HumanGatewayConstants::TABLE];
        $this->elasticSearchClientFactory->getClient()->indices()->delete($params);
    }

    /**
     * @param Human[] $humans
     */
    public function indexHumans(array $humans)
    {
        $params = [];
        foreach ($humans as $human) {
            $params['body'][] = [
                'index' => [
                    '_index' => HumanGatewayConstants::TABLE,
                    '_type' => HumanGatewayConstants::TABLE,
                    '_id' => $human->getId()
                ]
            ];
            $params['body'][] = $human->toArray();
        }

        $this->elasticSearchClientFactory->getClient()->bulk($params);
    }

    public function searchHumans(HumanElasticSearchRequest $request)
    {
        $result = $this->elasticSearchClientFactory->getClient()->search($request->getRequest());
        $humans = collect($result['hits']['hits'])->map(function(array $data) {
            return $this->humanHydrator->generateHumanFromArray($data['_source']);
        });
        return array(
            'total' => $result['hits']['total'],
            'data_size' => $humans->count(),
            'last_id' => $humans->last() instanceof Human ? $humans->last()->getId() : null,
            'data' => $humans
        );
    }
}
