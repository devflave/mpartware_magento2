<?php
/**
 * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2018 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 *
 * @category	Customweb
 * @package		Customweb_PayPalPlusCw
 *
 */

namespace Customweb\PayPalPlusCw\Block\Adminhtml\Checkout;

class Payment extends \Magento\Backend\Block\Widget\Form\Container
{
	/**
	 * Block group
	 *
	 * @var string
	 */
	protected $_blockGroup = 'Customweb_PayPalPlusCw';

	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry = null;

	/**
	 * @var \Magento\Sales\Model\OrderFactory
	 */
	protected $_orderFactory;

	/**
	 * @var \Customweb\PayPalPlusCw\Model\Authorization\TransactionFactory
	 */
	protected $_transactionFactory;

	/**
	 *
	 * @var \Customweb\PayPalPlusCw\Model\Configuration
	 */
	protected $_configuration;

	/**
	 * @var \Customweb\PayPalPlusCw\Model\Authorization\Transaction
	 */
	protected $transaction;

	/**
	 * @param \Magento\Backend\Block\Widget\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Sales\Model\OrderFactory $orderFactory
	 * @param \Customweb\PayPalPlusCw\Model\Authorization\TransactionFactory $transactionFactory
	 * @param \Customweb\PayPalPlusCw\Model\Configuration $configuration
	 * @param array $data
	 */
	public function __construct(
			\Magento\Backend\Block\Widget\Context $context,
			\Magento\Framework\Registry $registry,
			\Magento\Sales\Model\OrderFactory $orderFactory,
			\Customweb\PayPalPlusCw\Model\Authorization\TransactionFactory $transactionFactory,
			\Customweb\PayPalPlusCw\Model\Configuration $configuration,
			array $data = []
	) {
		$this->_coreRegistry = $registry;
		$this->_orderFactory = $orderFactory;
		$this->_transactionFactory = $transactionFactory;
		$this->_configuration = $configuration;
		parent::__construct($context, $data);
	}

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_objectId = 'transaction_id';
		$this->_controller = 'adminhtml_checkout';
		$this->_mode = 'create';

		parent::_construct();

		$this->buttonList->remove('back');
		$this->buttonList->remove('reset');
		$this->setId('paypalpluscw_checkout_payment');

		$this->buttonList->update('save', 'label', __('Submit Order'));
		$this->buttonList->update('save', 'class', 'primary');
		$this->buttonList->update('save', 'id', 'paypalpluscw-payment-submit-button');

		$this->buttonList->add(
			'order_cancel',
			[
				'label' => __('Cancel'),
				'class' => 'cancel',
				'id' => 'paypalpluscw-payment-cancel-button'
			]
		);

		$this->_configuration->setStore($this->getTransaction()->getStore());
	}

	/**
	 * Retrieve transaction model object
	 *
	 * @return \Customweb\PayPalPlusCw\Model\Authorization\Transaction
	 */
	public function getTransaction()
	{
		if (!($this->transaction instanceof \Customweb\PayPalPlusCw\Model\Authorization\Transaction)) {
			/* @var $transaction \Customweb\PayPalPlusCw\Model\Authorization\Transaction */
			$transaction = $this->_transactionFactory->create()->loadByOrderId($this->getRequest()->getParam('order_id'));
			if (!($transaction instanceof \Customweb\PayPalPlusCw\Model\Authorization\Transaction) || !$transaction->getId()) {
				throw new \Exception('The transaction for order ' . $this->getRequest()->getParam('order_id') . ' has not been found.');
			}
			$this->transaction = $transaction;
		}
		return $this->transaction;
	}

	/**
	 * Get header text
	 *
	 * @return \Magento\Framework\Phrase
	 */
	public function getHeaderText()
	{
		return __('Create New Order - Payment');
	}

	/**
	 * Check permission for passed action
	 *
	 * @param string $resourceId
	 * @return bool
	 */
	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}
}