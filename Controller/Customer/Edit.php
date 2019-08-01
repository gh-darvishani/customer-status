<?php
    
    namespace WebGate\CustomerStatus\Controller\Customer;
    
    use Magento\Framework\App\Action\Action;
    use Magento\Framework\App\Action\Context;
    use Magento\Framework\View\Result\Page;
    use Magento\Framework\View\Result\PageFactory;

    class Edit extends Action
    {
        
        /** @var  Page */
        protected $resultPageFactory;
        
        /**     * @param Context $context */
        public function __construct(Context $context , PageFactory $resultPageFactory)
        {
            $this->resultPageFactory = $resultPageFactory;
            
            parent::__construct($context);
        }
        
        /**
         * Blog Index, shows a list of recent blog posts.
         *
         * @return PageFactory
         */
        public function execute()
        {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addDefaultHandle()->addHandle('status_customer_index');
    
            if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
                $navigationBlock->setActive('customer-status/edit');
            }
       
            $resultPage->getConfig()->getTitle()->set(__('Customer Status'));
            
            return $resultPage;
        }
    }
