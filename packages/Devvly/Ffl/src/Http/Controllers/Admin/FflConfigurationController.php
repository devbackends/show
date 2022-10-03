<?php

namespace Devvly\Ffl\Http\Controllers\Admin;

use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\ConfigurationController;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CoreConfigRepository;

class FflConfigurationController extends ConfigurationController
{
    private $fflRepository;

    public function __construct(CoreConfigRepository $coreConfigRepository, FflRepository $fflRepository)
    {
        parent::__construct($coreConfigRepository);
        $this->fflRepository = $fflRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pathInfo = request()->getPathInfo();
        $flag = in_array($pathInfo, self::URLS_FOR_MENU) ? 1 : 0;

        return view('ffl::admin.form', [
            'config'   => $this->configTree,
            'flag'     => $flag,
            'pathInfo' => $pathInfo,
            'ffl'      => $this->fflRepository->findBy(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function fflDisable(Request $request)
    {
        $status = $request->get('status');
        $channel = core()->getCurrentChannel();
        $locale = core()->getCurrentLocale();
        if (empty($channel) || empty($locale)) {
            abort(404);
        }
        $config = core()->getConfigData(CoreConfig::FFL_DISABLED);
        if (is_null($config)) {
            $config = new CoreConfig([
                'channel_code' => $channel->code,
                'code'         => CoreConfig::FFL_DISABLED,
                'value'        => (int)$status,
                'locale_code'  => $locale->code,
            ]);
            $config->save();
        } else {
            CoreConfig::select()
                ->where('code', '=', CoreConfig::FFL_DISABLED)
                ->update(['value' => (int)$status]);
        }
        return new JsonResponse([]);
    }

    /**
     * @param FflRepository $fflRepository
     * @return JsonResponse
     * @throws \Exception
     */
    public function getFfl(FflRepository $fflRepository)
    {
        return new JsonResponse(
            $fflRepository->findBy()
        );
    }
}
