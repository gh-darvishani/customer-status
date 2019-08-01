<?php
    
    namespace WebGate\CustomerStatus\Setup;
    
    use Magento\Customer\Model\Customer;
    use Magento\Customer\Setup\CustomerSetupFactory;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\Setup\ModuleDataSetupInterface;
    use Magento\Framework\Setup\UpgradeDataInterface;
    
    class UpgradeData implements UpgradeDataInterface
    {
        
        private $customerSetupFactory;
        
        /**
         * Constructor
         *
         * @param CustomerSetupFactory $customerSetupFactory
         */
        public function __construct(CustomerSetupFactory $customerSetupFactory)
        {
            $this->customerSetupFactory = $customerSetupFactory;
        }
        
        /**
         * {@inheritdoc}
         */
        public function upgrade(ModuleDataSetupInterface $setup , ModuleContextInterface $context)
        {
            $customerSetup = $this->customerSetupFactory->create([ 'setup' => $setup ]);
            
            if(version_compare($context->getVersion() , "1.0.1" , "<"))
            {
                
                $customerSetup->addAttribute(Customer::ENTITY , 'customer_status' , [
                    'type' => 'varchar' ,
                    'label' => 'Customer Status' ,
                    'input' => 'text' ,
                    'source' => '' ,
                    'required' => false ,
                    'visible' => true ,
                    'position' => 333 ,
                    'system' => false ,
                    'backend' => '' ,
                ]);
                
                $attribute = $customerSetup->getEavConfig()
                    ->getAttribute('customer' , 'customer_status')
                    ->addData([ 'used_in_forms' => [ 'adminhtml_customer' ] ]);
                
                $attribute->save();
            }
        }
    }