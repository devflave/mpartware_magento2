<?php
/**
 * Created by:  Milan Simek
 * Company:     Plugin Company
 *
 * LICENSE: http://plugin.company/docs/magento-extensions/magento-extension-license-agreement
 *
 * YOU WILL ALSO FIND A PDF COPY OF THE LICENSE IN THE DOWNLOADED ZIP FILE
 *
 * FOR QUESTIONS AND SUPPORT
 * PLEASE DON'T HESITATE TO CONTACT US AT:
 *
 * SUPPORT@PLUGIN.COMPANY
 */


namespace PluginCompany\CustomerGroupSwitching\Api\Data;

interface RuleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Rule list.
     * @return \PluginCompany\CustomerGroupSwitching\Api\Data\RuleInterface[]
     */
    
    public function getItems();

    /**
     * Set rule_id list.
     * @param \PluginCompany\CustomerGroupSwitching\Api\Data\RuleInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
