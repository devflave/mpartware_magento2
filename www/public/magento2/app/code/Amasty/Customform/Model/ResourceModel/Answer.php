<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Customform
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Customform\Model\ResourceModel;

class Answer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('am_customform_answer', 'answer_id');
    }
}
