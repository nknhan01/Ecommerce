<?php

namespace Botble\DevTool\Commands;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Services\ActivateUserService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create super user for Botble CMS';

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var ActivateUserService
     */
    protected $activateUserService;

    /**
     * UserCreateCommand constructor.
     * @param UserInterface $userRepository
     * @param ActivateUserService $activateUserService
     */
    public function __construct(UserInterface $userRepository, ActivateUserService $activateUserService)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->activateUserService = $activateUserService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->createSuperUser();
    }

    /**
     * Create a superuser.
     *
     * @return bool
     */
    protected function createSuperUser()
    {
        $this->info('Creating a Super User...');

        try {
            $user = $this->userRepository->getModel();
            $user->first_name = $this->askWithValidate('Enter first name', 'required|min:2|max:60');
            $user->last_name = $this->askWithValidate('Enter last name', 'required|min:2|max:60');
            $user->email = $this->askWithValidate('Enter email address', 'required|email|unique:users,email');
            $user->username = $this->askWithValidate('Enter username', 'required|min:4|max:60|unique:users,username');
            $user->password = bcrypt($this->askWithValidate('Enter password', 'required|min:6|max:60', true));
            $user->super_user = 1;
            $user->manage_supers = 1;

            $this->userRepository->createOrUpdate($user);
            if ($this->activateUserService->activate($user)) {
                $this->info('Super user is created.');
            }
        } catch (Exception $exception) {
            $this->error('User could not be created.');
            $this->error($exception->getMessage());
        }

        return true;
    }

    /**
     * @param $message
     * @param string $rules
     * @param bool $secret
     * @return mixed
     */
    protected function askWithValidate($message, string $rules, $secret = false)
    {
        do {
            if ($secret) {
                $input = $this->secret($message);
            } else {
                $input = $this->ask($message);
            }
            $validate = $this->validate(compact('input'), ['input' => $rules]);
            if ($validate['error']) {
                $this->error($validate['message']);
            }
        } while ($validate['error']);

        return $input;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return array
     */
    protected function validate(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return [
                'error'   => true,
                'message' => $validator->messages()->first(),
            ];
        }

        return [
            'error' => false,
        ];
    }
}
