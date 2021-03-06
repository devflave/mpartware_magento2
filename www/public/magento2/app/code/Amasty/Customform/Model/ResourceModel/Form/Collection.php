<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Customform
 */


namespace Amasty\Customform\Model\ResourceModel\Form;

class Collection extends \Magento\Cms\Model\ResourceModel\Page\Collection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Amasty\Customform\Model\Form::class, \Amasty\Customform\Model\ResourceModel\Form::class);
        $this->_setIdFieldName('form_id');
    }
}
