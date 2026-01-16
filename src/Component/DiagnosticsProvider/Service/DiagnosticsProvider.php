<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\DiagnosticsProvider\Service;

use OxidEsales\Eshop\Application\Model\Diagnostics;
use OxidEsales\Eshop\Core\Di\ContainerFacade; 
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;

class DiagnosticsProvider implements DiagnosticsProviderInterface {
    private Diagnostics $diagnostics;
    public function getDiagnosticsModel(): Diagnostics
    {
        if($this->diagnostics) {
            return $this->diagnostics;
        }
        $this->diagnostics = oxNew(Diagnostics::class);
        return $this->diagnostics;
    }

    public function getModuleList() : array {
        $shopConfiguration = ContainerFacade::get(ShopConfigurationDaoBridgeInterface::class)
            ->get();

        $modules = [];

        foreach ($shopConfiguration->getModuleConfigurations() as $moduleConfiguration) {
            $module = oxNew(Module::class);
            $module->load($moduleConfiguration->getId());
            $modules[$moduleConfiguration->getId()] = $module;
        }

        return $modules;
    }

    public function getDiagnostics () : array{
        $aResults = [];
        $oDiagnostics = $this->getDiagnosticsModel();
        $oSysReq = oxNew(\OxidEsales\Eshop\Core\SystemRequirements::class);

        $oDiagnostics->setShopLink(Registry::getConfig()->getConfigParam('sShopURL'));
        $oDiagnostics->setEdition(Registry::getConfig()->getFullEdition());
        $oDiagnostics->setVersion(
            oxNew(\OxidEsales\Eshop\Core\ShopVersion::class)->getVersion()
        );

        $aResults["aShopDetails"]   = $oDiagnostics->getShopDetails();

        $aResults["aModuleList"] = $this->getModuleList();

        $aResults['aInfo'] = $oSysReq->getSystemInfo();
        $aResults['aCollations'] = $oSysReq->checkCollation();
        
        
        $aResults['aPhpConfigparams'] = $oDiagnostics->getPhpSelection();

        $aResults['sPhpDecoder'] = $oDiagnostics->getPhpDecoder();

        $aResults['aServerInfo'] = $oDiagnostics->getServerInfo();

        return $aResults;
    }
}