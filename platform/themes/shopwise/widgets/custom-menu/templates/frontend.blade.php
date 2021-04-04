<div class="col-lg-2 col-md-4 col-sm-6">
    <div class="widget">
        <h6 class="widget_title">{{ $config['name'] }}</h6>
        {!!
            Menu::generateMenu(['slug' => $config['menu_id'], 'options' => ['class' => 'widget_links']])
        !!}
    </div>
</div>
