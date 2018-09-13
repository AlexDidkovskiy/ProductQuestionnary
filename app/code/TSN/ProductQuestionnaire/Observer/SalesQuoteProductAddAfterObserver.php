<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class SalesQuoteProductAddAfterObserver implements ObserverInterface
{

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer) {
        $dataPostArray = filter_input(INPUT_POST,'tsn', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if(isset($dataPostArray) || !empty($dataPostArray)) {

            $html = "";
            if (is_array($dataPostArray) || sizeof($dataPostArray)) {

                foreach ($dataPostArray as $question) {
                    if(is_array($question['answer'])){
                        $question['answer'] = implode("<br>", $question['answer']);
                    }
                    $html .= '<div><h4>' . $question['question'] . '</h4><p>' . $question['answer'] . '</p></div>';
                }
            } else {
                $html .= '<div><h4>No date</h4></div>';
            }

            $items = $observer->getItems();

            foreach ($items as $item){
                $item->setQuoteQuestion($html);
            }
        }
    }
}
