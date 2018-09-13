<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaGroupInterface;

class GroupActions extends Column
{
    const URL_PATH_EDIT = 'questionnaire/group/edit';
    const URL_PATH_DELETE = 'questionnaire/group/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data       = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[SchemaGroupInterface::ID_FIELD])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    SchemaGroupInterface::ID_FIELD => $item[SchemaGroupInterface::ID_FIELD]
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    SchemaGroupInterface::ID_FIELD => $item[SchemaGroupInterface::ID_FIELD]
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title'   => __('Delete "${ $.$data.question }"'),
                                'message' => __('Are you sure you want to delete the Data: "${ $.$data.question }"?')
                            ]
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
