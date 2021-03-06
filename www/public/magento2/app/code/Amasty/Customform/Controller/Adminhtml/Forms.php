<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Customform
 */


namespace Amasty\Customform\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

abstract class Forms extends \Magento\Backend\App\Action
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE_FORMS = 'Amasty_Customform::forms';

    const ADMIN_RESOURCE_PAGE = 'Amasty_Customform::forms';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Amasty\Customform\Model\FormFactory
     */
    protected $formFactory;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Amasty\Customform\Model\FormRepository
     */
    protected $formRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $mathRandom;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Amasty\Customform\Model\FormFactory $formFactory,
        \Amasty\Customform\Model\FormRepository $formRepository,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Math\Random $mathRandom
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $registry;
        $this->formFactory = $formFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->formRepository = $formRepository;
        $this->logger = $logger;
        $this->mathRandom = $mathRandom;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE_FORMS);
    }
}
