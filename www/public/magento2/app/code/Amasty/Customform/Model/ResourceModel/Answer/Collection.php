<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Customform
 */


namespace Amasty\Customform\Model\ResourceModel\Answer;

class Collection extends \Magento\Cms\Model\ResourceModel\Page\Collection
{
    protected $_idFieldName = 'answer_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Amasty\Customform\Model\Answer::class,
            \Amasty\Customform\Model\ResourceModel\Answer::class
        );
    }
}
