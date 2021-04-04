<?php

namespace Platform\Contact\Http\Controllers;

use Platform\Base\Events\BeforeEditContentEvent;
use Platform\Base\Forms\FormBuilder;
use Platform\Base\Http\Controllers\BaseController;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\Base\Traits\HasDeleteManyItemsTrait;
use Platform\Contact\Enums\ContactStatusEnum;
use Platform\Contact\Forms\ContactForm;
use Platform\Contact\Http\Requests\ContactReplyRequest;
use Platform\Contact\Http\Requests\EditContactRequest;
use Platform\Contact\Repositories\Interfaces\ContactReplyInterface;
use Platform\Contact\Tables\ContactTable;
use Platform\Contact\Repositories\Interfaces\ContactInterface;
use Platform\Setting\Supports\SettingStore;
use EmailHandler;
use Exception;
use Illuminate\Http\Request;
use Platform\Base\Events\DeletedContentEvent;
use Platform\Base\Events\UpdatedContentEvent;

class ContactController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var ContactInterface
     */
    protected $contactRepository;

    /**
     * @param ContactInterface $contactRepository
     */
    public function __construct(ContactInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param ContactTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(ContactTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/contact::contact.menu'));

        return $dataTable->renderTable();
    }

    /**
     * @param $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        page_title()->setTitle(trans('plugins/contact::contact.edit'));

        $contact = $this->contactRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $contact));

        return $formBuilder->create(ContactForm::class, ['model' => $contact])->renderForm();
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, EditContactRequest $request, BaseHttpResponse $response)
    {
        $contact = $this->contactRepository->findOrFail($id);

        $contact->fill($request->input());

        $this->contactRepository->createOrUpdate($contact);

        event(new UpdatedContentEvent(CONTACT_MODULE_SCREEN_NAME, $request, $contact));

        return $response
            ->setPreviousUrl(route('contacts.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
        try {
            $contact = $this->contactRepository->findOrFail($id);
            $this->contactRepository->delete($contact);
            event(new DeletedContentEvent(CONTACT_MODULE_SCREEN_NAME, $request, $contact));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->contactRepository, CONTACT_MODULE_SCREEN_NAME);
    }

    /**
     * @param int $id
     * @param ContactReplyRequest $request
     * @param BaseHttpResponse $response
     * @param ContactReplyInterface $contactReplyRepository
     * @return BaseHttpResponse
     */
    public function postReply(
        $id,
        ContactReplyRequest $request,
        BaseHttpResponse $response,
        ContactReplyInterface $contactReplyRepository
    ) {
        $contact = $this->contactRepository->findOrFail($id);

        EmailHandler::send($request->input('message'), 'Re: ' . $contact->subject, $contact->email);

        $contactReplyRepository->create([
            'message'    => $request->input('message'),
            'contact_id' => $id,
        ]);

        $contact->status = ContactStatusEnum::READ();
        $this->contactRepository->createOrUpdate($contact);

        return $response
            ->setMessage(trans('plugins/contact::contact.message_sent_success'));
    }
}
