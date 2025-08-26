<?php

namespace App\Console\Commands\Search;

use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Advert\Value;
use App\UseCases\Adverts\AdvertService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class InitCommand extends Command
{
    protected $signature = 'search:init';

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): bool
    {
        try {
            $this->client->indices()->delete(['index' => 'app']);
        } catch (ClientResponseException $e) {
            if ($e->getCode() === 400) {
                throw $e;
            }
        }

        $this->client->indices()->create([
            'index' => 'app',
            'body' => [
                'mappings' => [
                    'adverts' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ],
                        'published_at' => [
                            'type' => 'date',
                        ],
                        'title' => [
                            'type' => 'string',
                        ],
                        'content' => [
                            'type' => 'text',
                        ],
                        'price' => [
                            'type' => 'integer',
                        ],
                        'status' => [
                            'type' => 'keyword',
                        ],
                        'categories' => [
                            'type' => 'integer',
                        ],
                        'regions' => [
                            'type' => 'integer',
                        ],
                        'values' => [
                            'type' => 'nested',
                            'properties' => [
                                'attribute' => [
                                    'type' => 'integer',
                                ],
                                'value_string' => [
                                    'type' => 'keyword',
                                ],
                                'value_int' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                    ],
                ],
                'settings' => [
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and'
                                ],
                            ]
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ]
                        ],
                        'trigrams' => [
                            'type' => 'ngram',
                            'min_gram' => 4,
                            'max_gram' => 6,
                        ]
                    ],
                    'analyzer' => [
                        'default' => [
                            'type' => 'custom',
                            'char_filter' => [
                                'html_strip',
                                'replace',
                            ],
                            'tokenizer' => 'whitespace',
                            'filter' => [
                                'lowercase',
                                'word_delimiter',
                            ],
                        ]
                    ]
                ]
            ],
        ]);

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {

            $regions = [];
            if ($region = $advert->region) {
                do {
                    $regions[] = $region->id;
                } while ($region = $region->parent);
            }

            $this->client->index([
                'index' => 'app',
                'id' => $advert->id,
                'body' => [
                    'id' => $advert->id,
                    'published_at' => $advert->published_at ? $advert->published_at->format(DATE_ATOM) : null,
                    'title' => $advert->title,
                    'content' => $advert->content,
                    'price' => $advert->price,
                    'status' => $advert->status,
                    'categories' => array_merge([$advert->category->id],
                        $advert->category->ancestors()->pluck('id')->toArray()
                    ),
                    'regions' => $regions,
                    'values' => array_map(function (Value $value) {
                        return [
                          'attribute' => $value->attribute_id,
                          'value_string' => (string)$value->value,
                          'value_int' => (int)$value->value,
                        ];
                    }, $advert->values->getModels()),
                ]
            ]);
        }

        return true;
    }
}
