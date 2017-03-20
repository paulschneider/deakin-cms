<?php
namespace App\Search;

class SearchParams
{
    public function create($page, $perPage, $query)
    {
        $query = $query ? urlencode($query) : '*';

        $params = [
            'index' => config('scout.elasticsearch.index'),

            'body' => [
                'query' => [
                    'bool'      => [
                        'must' => [
                            ['query_string' => ['query' => $query, 'fuzziness' => 2]],
                        ],

                        'should' => [
                            ['match' => ['title' => ['query' => $query, 'boost' => 2, 'fuzziness' => 2]]],
                            ['match' => ['body' => ['query' => $query]]],
                        ],
                    ],
                ],
                'highlight' => [
                    'fields'    => [
                        '*' => (object) [
                            'number_of_fragments' => 0,
                        ],
                    ],
                    "pre_tags"  => ["<mark>"],
                    "post_tags" => ["</mark>"],
                ],
                'from'      => (($page * $perPage) - $perPage),
                'size'      => $perPage,
            ],
        ];

        if ($query == '*') {
            $params['body']['sort'] = [
                'created_at'  => ['order' => 'desc'],
                'is_featured' => ['order' => 'desc', 'ignore_unmapped' => true],
            ];
        }

        return $params;
    }
}
