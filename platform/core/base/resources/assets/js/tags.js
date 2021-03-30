class TagsManager {
    init() {
        let element = document.querySelector('.tags');

        // initialize Tagify on the above input node reference
        let tagify = new Tagify(element, {
            keepInvalidTags: true,
        });

        tagify.on('input', e => {
            tagify.settings.whitelist.length = 0; // reset current whitelist
            tagify.loading(true).dropdown.hide.call(tagify) // show the loader animation

            $.ajax({
                type: 'GET',
                url: $(element).data('url'),
                success: data => {
                    tagify.settings.whitelist = data;

                    // render the suggestions dropdown.
                    tagify.loading(false).dropdown.show.call(tagify, e.detail.value);
                },
            });
        });
    }
}

$(document).ready(() => {
    (new TagsManager()).init();
})
