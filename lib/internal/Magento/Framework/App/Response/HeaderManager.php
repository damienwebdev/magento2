<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\App\Response;

use Magento\Framework\App\Response\HeaderProvider\HeaderProviderInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class HeaderManager
{
    /**
     * @var HeaderProviderInterface[]
     */
    private $headerProviders;

    /**
     * @param HeaderProviderInterface[] $headerProviderList
     * @throws LocalizedException In case one of the header providers is invalid
     */
    public function __construct($headerProviderList)
    {
        foreach ($headerProviderList as $header) {
            if (!($header instanceof HeaderProviderInterface)) {
                throw new LocalizedException(new Phrase('The header provider is invalid. Verify and try again.'));
            }
        }
        $this->headerProviders = $headerProviderList;
    }

    /**
     * Applies provided headers before sending responce
     *
     * @param \Magento\Framework\App\Response\HttpInterface $subject
     * @return void
     */
    public function beforeSendResponse(\Magento\Framework\App\Response\HttpInterface $subject): void
    {
        foreach ($this->headerProviders as $provider) {
            if ($provider->canApply()) {
                $subject->setHeader($provider->getName(), $provider->getValue());
            }
        }
    }
}
