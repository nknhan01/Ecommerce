<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('plugins/backup::backup.settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('plugins/backup::backup.settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group">
                <label class="text-title-field"
                       for="backup_mysql_execute_path">{{ trans('plugins/backup::backup.settings.backup_mysql_execute_path') }}</label>
                <input data-counter="120" type="text" class="next-input" name="backup_mysql_execute_path" id="backup_mysql_execute_path"
                       value="{{ setting('backup_mysql_execute_path') }}" placeholder="{{ trans('plugins/backup::backup.settings.backup_mysql_execute_path_placeholder') }}">
            </div>
        </div>
    </div>
</div>