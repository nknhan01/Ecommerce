<?php

namespace Platform\Impersonate\Http\Controllers;

use Platform\ACL\Repositories\Interfaces\UserInterface;
use Platform\Base\Http\Controllers\BaseController;
use Platform\Base\Supports\Helper;
use Illuminate\Http\Request;

class ImpersonateController extends BaseController
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * ImpersonateController constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function getImpersonate($id, Request $request)
    {
        $user = $this->userRepository->findOrFail($id);
        $request->user()->impersonate($user);

        Helper::executeCommand('cache:clear');

        return redirect()->route('dashboard.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leaveImpersonation(Request $request)
    {
        $request->user()->leaveImpersonation();

        return redirect()->route('dashboard.index');
    }
}
