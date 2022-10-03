<?php


namespace Devvly\OnBoarding\Http\Controllers;

use Webkul\Admin\Http\Controllers\ConfigurationController as BaseConfigController;

class ConfigurationController extends BaseConfigController
{
    const URLS_FOR_MENU = [
        '/admin/configuration/general/design',
        '/admin/configuration/megaheaderfooter/general',
        '/admin/configuration/general/general',
        '/admin/configuration/emails/general',
        '/admin/configuration/megasmsnotifications/general',
        '/admin/configuration/catalog/inventory',
        '/admin/configuration/catalog/products',
        '/admin/configuration/sales/rma',
        '/admin/configuration/sales/paymentmethods',
        '/admin/configuration/sales/orderSettings',
        '/admin/configuration/customer/settings',
        '/admin/configuration/megaPhoneLogin/general',
        '/admin/configuration/sales/shipping',
        '/admin/configuration/general/general',
        '/admin/configuration/sales/carriers',
        '/admin/configuration/pos/configuration',
        '/admin/configuration/pwa/settings',
        '/admin/configuration/sales/ffl'
    ];

    public function index()
    {
        $slugs = $this->getDefaultConfigSlugs();
        $payment_processing = request()->get('payment_processing');
        if (count($slugs)) {
            return redirect()->route('admin.configuration.index', $slugs);
        }
        $pathInfo = request()->getPathInfo();
        $flag = in_array($pathInfo, self::URLS_FOR_MENU) ? 1 : 0;

        if ($payment_processing === 'clearent') {
            return view('OnBoarding::OnBoarding.edit-add', ['config' => $this->configTree]);
        }
        return view($this->_config['view'], ['config' => $this->configTree, 'flag' => $flag, 'pathInfo' => $pathInfo]);
    }
}