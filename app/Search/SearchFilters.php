<?php
namespace App\Search;

class SearchFilters
{
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function addMust($data)
    {
        if (count($this->params['body']['query']['bool']['must']) === 1) {
            $this->params['body']['query']['bool']['must'][] = ['bool' => ['should' => []]];
        }

        $pointer = null;
        foreach ($this->params['body']['query']['bool']['must'] as $key => $value) {
            foreach (array_keys($value) as $delta) {
                if ($delta === 'bool') {
                    $pointer = $key;
                }
            }
        }

        $this->params['body']['query']['bool']['must'][$pointer]['bool']['should'] = array_merge($this->params['body']['query']['bool']['must'][$pointer]['bool']['should'], [$data]);
    }

    public function pagesFilter()
    {
        $this->addMust(['match' => ['model' => "Page"]]);
    }

    public function articlesFilter()
    {
        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                    ['bool' => [
                        'should' => [
                            ['match' => ['revision.article_types.stub' => "news"]],
                            ['match' => ['revision.article_types.stub' => "articles"]],
                        ],
                    ]],

                ],
            ],
        ]);
    }

    public function eventsFilter()
    {
        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                    ['match' => ['revision.article_types.stub' => "events"]],
                ],
            ],
        ]);
    }

    public function pressFilter()
    {
        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                    ['match' => ['revision.article_types.stub' => "press"]],
                ],
            ],
        ]);
    }

    public function videosFilter()
    {
        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                    ['match' => ['revision.article_types.stub' => "videos"]],
                ],
            ],
        ]);
    }

    public function resourcesFilter()
    {
        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                    ['match' => ['revision.article_types.stub' => "resources"]],
                ],
            ],
        ]);
    }

    public function stubAny()
    {
        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                ],
            ],
        ]);
    }

    public function stubFilter($stub = null)
    {
        if (empty($stub)) {
            return;
        }

        $this->addMust([
            'bool' => [
                'must' => [
                    ['match' => ['model' => "Article"]],
                    ['match' => ['revision.article_types.stub' => $stub]],
                ],
            ],
        ]);
    }
}
