<?php
/**
 * @copyright  Stephan Gieb 2017 <http://yupdesign.de>
 * @author     Stephan Gieb
 * @license    LGPL-3.0+
 *
 */

namespace yupdesign\CUABundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
/**
 * @author Stephan Gieb
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('yupdesign\CUABundle\yupdesignCUABundle')
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
                ->setReplace(['cua']),
        ];
    }
}