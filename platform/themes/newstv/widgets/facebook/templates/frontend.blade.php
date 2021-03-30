@if (!empty($config['facebook_name']) && !empty($config['facebook_id']))
    <div class="fb-page" data-href="{{ $config['facebook_id'] }}" data-tabs="timeline" data-small-header="false"
         data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
        <blockquote cite="{{ $config['facebook_id'] }}" class="fb-xfbml-parse-ignore"><a
                    href="{{ $config['facebook_id'] }}">{{ $config['facebook_name'] }}</a></blockquote>
    </div>
@endif