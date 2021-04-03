@if ($contact)
    <div id="reply-wrapper">
        @if (count($contact->replies) > 0)
            @foreach($contact->replies as $reply)
                <p>{{ trans('plugins/contact::contact.tables.time') }}: <i>{{ $reply->created_at }}</i></p>
                <p>{{ trans('plugins/contact::contact.tables.content') }}:</p>
                <pre class="message-content">{!! clean($reply->message) !!}</pre>
            @endforeach
        @else
            <p>{{ __('No reply yet!') }}</p>
        @endif
    </div>

    <p><button class="btn btn-info answer-trigger-button">{{ __('Reply') }}</button></p>

    <div class="answer-wrapper">
        <div class="form-group">
            {!! render_editor('message', null, false, ['without-buttons' => true, 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <input type="hidden" value="{{ $contact->id }}" id="input_contact_id">
            <button class="btn btn-success answer-send-button"><i class="fas fa-reply"></i> {{ __('Send') }}</button>
        </div>
    </div>
@endif
