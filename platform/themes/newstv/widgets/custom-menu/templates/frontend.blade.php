<div class="col-md-3 col-sm-6">
    <h4>{{ $config['name'] }}</h4>
    {!!
        Menu::generateMenu([
            'slug'    => $config['menu_id'],
            'options' => ['class' => 'footer-menu', 'role' => 'menu'],
        ])
    !!}
</div>
