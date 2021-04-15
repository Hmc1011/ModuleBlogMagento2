<?php
namespace HoangCong\Blog\Model;
class Cron {
/** @var \Psr\Log\LoggerInterface $logger */
protected $logger;
/** @var \Magento\Framework\ObjectManagerInterface */
protected $objectManager;
public function __construct(
\Psr\Log\LoggerInterface $logger,
\Magento\Framework\ObjectManagerInterface $objectManager
) {
$this->logger = $logger;
$this->objectManager = $objectManager;
}
public function sendMailToAdminWhenAppearNewComment() {
$this->logger->debug("Test Cron Job");
}
}