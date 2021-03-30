<?php

namespace Botble\Setting\Http\Controllers;

use Assets;
use Botble\Base\Supports\Core;
use Botble\Base\Supports\EmailHandler;
use Botble\Setting\Http\Requests\EmailTemplateRequest;
use Botble\Setting\Http\Requests\LicenseSettingRequest;
use Botble\Setting\Http\Requests\MediaSettingRequest;
use Botble\Setting\Http\Requests\SendTestEmailRequest;
use Botble\Setting\Repositories\Interfaces\SettingInterface;
use Botble\Setting\Supports\SettingStore;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\File;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SettingController extends BaseController
{
    /**
     * @var SettingInterface
     */
    protected $settingRepository;

    /**
     * @var SettingStore
     */
    protected $settingStore;

    /**
     * SettingController constructor.
     * @param SettingInterface $settingRepository
     * @param SettingStore $settingStore
     */
    public function __construct(SettingInterface $settingRepository, SettingStore $settingStore)
    {
        $this->settingRepository = $settingRepository;
        $this->settingStore = $settingStore;
    }

    /**
     * @return Factory|View
     */
    public function getOptions()
    {
        page_title()->setTitle(trans('core/setting::setting.title'));

        Assets::addScriptsDirectly('vendor/core/js/setting.js');

        return view('core/setting::index');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEdit(Request $request, BaseHttpResponse $response)
    {
        $this->saveSettings($request->except(['_token']));

        return $response
            ->setPreviousUrl(route('settings.options'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param array $data
     */
    protected function saveSettings(array $data)
    {
        foreach ($data as $settingKey => $settingValue) {
            $this->settingStore->set($settingKey, $settingValue);
        }

        $this->settingStore->save();
    }

    /**
     * @return Factory|View
     */
    public function getEmailConfig()
    {
        page_title()->setTitle(trans('core/base::layouts.setting_email'));
        Assets::addScriptsDirectly('vendor/core/js/setting.js');

        return view('core/setting::email');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEditEmailConfig(Request $request, BaseHttpResponse $response)
    {
        $this->saveSettings($request->except(['_token']));

        return $response
            ->setPreviousUrl(route('settings.email'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $template
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return Factory|View
     * @throws FileNotFoundException
     */
    public function getEditEmailTemplate($type, $name, $template)
    {
        $title = trans(config($type . '.' . $name . '.email.templates.' . $template . '.title', ''));
        page_title()->setTitle($title);

        Assets::addStylesDirectly([
            'vendor/core/libraries/codemirror/lib/codemirror.css',
            'vendor/core/libraries/codemirror/addon/hint/show-hint.css',
            'vendor/core/css/setting.css',
        ])
            ->addScriptsDirectly([
                'vendor/core/libraries/codemirror/lib/codemirror.js',
                'vendor/core/libraries/codemirror/lib/css.js',
                'vendor/core/libraries/codemirror/addon/hint/show-hint.js',
                'vendor/core/libraries/codemirror/addon/hint/anyword-hint.js',
                'vendor/core/libraries/codemirror/addon/hint/css-hint.js',
                'vendor/core/js/setting.js',
            ]);


        $emailContent = get_setting_email_template_content($type, $name, $template);
        $emailSubject = get_setting_email_subject($type, $name, $template);
        $pluginData = [
            'type'          => $type,
            'name'          => $name,
            'template_file' => $template,
        ];

        return view('core/setting::email-template-edit', compact('emailContent', 'emailSubject', 'pluginData'));
    }

    /**
     * @param EmailTemplateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postStoreEmailTemplate(EmailTemplateRequest $request, BaseHttpResponse $response)
    {
        if ($request->has('email_subject_key')) {
            $this->settingStore
                ->set($request->input('email_subject_key'), $request->input('email_subject'))
                ->save();
        }

        save_file_data($request->input('template_path'), $request->input('email_content'), false);

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postResetToDefault(Request $request, BaseHttpResponse $response)
    {
        $this->settingRepository->deleteBy(['key' => $request->input('email_subject_key')]);
        File::delete($request->input('template_path'));

        return $response->setMessage(trans('core/setting::setting.email.reset_success'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postChangeEmailStatus(Request $request, BaseHttpResponse $response)
    {
        $this->settingStore
            ->set($request->input('key'), $request->input('value'))
            ->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @param SendTestEmailRequest $request
     * @param EmailHandler $emailHandler
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postSendTestEmail(
        BaseHttpResponse $response,
        SendTestEmailRequest $request,
        EmailHandler $emailHandler
    ) {
        try {
            $emailHandler->send(
                file_get_contents(core_path('setting/resources/email-templates/test.tpl')),
                __('Test title'),
                $request->input('email'),
                [],
                true
            );

            return $response->setMessage(__('Send email successfully!'));
        } catch (Exception $exception) {
            return $response->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @return Factory|View
     */
    public function getMediaSetting()
    {
        page_title()->setTitle(trans('core/setting::setting.media.title'));

        return view('core/setting::media');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEditMediaSetting(MediaSettingRequest $request, BaseHttpResponse $response)
    {
        $this->saveSettings($request->except(['_token']));

        return $response
            ->setPreviousUrl(route('settings.media'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Core $coreApi
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getVerifyLicense(Core $coreApi, BaseHttpResponse $response)
    {
        if (!File::exists(storage_path('.license'))) {
            return $response->setError()->setMessage('Your license is invalid, please contact support.');
        }

        $result = $coreApi->verifyLicense(true);

        if (!$result['status']) {
            return $response->setError()->setMessage($result['message']);
        }

        $activatedAt = Carbon::createFromTimestamp(filectime($coreApi->getLicenseFilePath()));

        $data = [
            'activated_at' => $activatedAt->format('M d Y'),
            'licensed_to'  => $this->settingStore->get('licensed_to'),
        ];

        return $response->setMessage($result['message'])->setData($data);
    }

    /**
     * @param LicenseSettingRequest $request
     * @param BaseHttpResponse $response
     * @param Core $coreApi
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function activateLicense(LicenseSettingRequest $request, BaseHttpResponse $response, Core $coreApi)
    {
        $result = $coreApi->activateLicense($request->input('purchase_code'), $request->input('buyer'));

        if (!$result['status']) {
            return $response->setError()->setMessage($result['message']);
        }

        $this->settingStore
            ->set(['licensed_to' => $request->input('buyer')])
            ->save();

        $activatedAt = Carbon::createFromTimestamp(filectime($coreApi->getLicenseFilePath()));

        $data = [
            'activated_at' => $activatedAt->format('M d Y'),
            'licensed_to'  => $request->input('buyer'),
        ];

        return $response->setMessage($result['message'])->setData($data);
    }

    /**
     * @param BaseHttpResponse $response
     * @param Core $coreApi
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function deactivateLicense(BaseHttpResponse $response, Core $coreApi)
    {
        $result = $coreApi->deactivateLicense();

        if (!$result['status']) {
            return $response->setError()->setMessage($result['message']);
        }

        $this->settingRepository->deleteBy(['key' => 'licensed_to']);

        return $response->setMessage($result['message']);
    }
}
