<?php

register_page_template([
    'default' => 'Default',
]);

register_sidebar([
    'id'          => 'second_sidebar',
    'name'        => 'Second sidebar',
    'description' => 'This is a sample sidebar for main theme',
]);

theme_option()
    ->setSection([ //Color-Management
        'title' => 'Color',
        'desc' => 'null',
        'id' => 'opt-color-management',
        'subsection' => true,
        'icon' => 'fas fa-palette',
        'fields' =>
        [
            [
                'id' => 'primary_color',
                'type' => 'customColor',
                'label' => 'Primary color',
                'attributes' => [
                    'name' => 'primary_color',
                    'value' => '#000000',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ],
            ],
            [
                'id' => 'primary_color_hover',
                'type' => 'customColor',
                'label' => 'Primary color hover',
                'attributes' => [
                    'name' => 'primary_color_hover',
                    'value' => '#000000',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ],
            ],
            [
                'id' => 'primary_color_text',
                'type' => 'customColor',
                'label' => 'Primary color text',
                'attributes' => [
                    'name' => 'primary_color_text',
                    'value' => '#000000',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ],
            ],
            [
                'id' => 'primary_color_text_hover',
                'type' => 'customColor',
                'label' => 'Primary color text hover',
                'attributes' => [
                    'name' => 'primary_color_text_hover',
                    'value' => '#000000',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ],
            ],
            
        ],
    ]) //end: Color-Managerment

    ->setSection([ //InforCompany
        'title' => 'Company information',
        'desc' => 'null',
        'id' => 'opt-company-information',
        'subsection' => true,
        'icon' => 'fas fa-info-circle',
        'fields' =>
        [   
            //Name of Company
            [
                'id' => 'company_name',
                'type' => 'text',
                'label' => 'Company name',
                'attributes' => [
                    'name' => 'company_name',
                    'value' => 'Ecommerce',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ],
            ],
        ],
    ]) //end: Color-Managerment
    ->setField([
        'id'         => 'copyright',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Copyright'),
        'attributes' => [
            'name'    => 'copyright',
            'value'   => '© 2020 Laravel Technologies. All right reserved.',
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => __('Change copyright'),
                'data-counter' => 250,
            ],
        ],
        'helper'     => __('Copyright on footer of site'),
    ])
    ->setField([
        'id'         => 'primary_font',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'googleFonts',
        'label'      => __('Primary font'),
        'attributes' => [
            'name'  => 'primary_font',
            'value' => 'Roboto',
        ],
    ]); //end theme_option
add_action('init', function () {
    config(['filesystems.disks.public.root' => public_path('storage')]);
}, 124);
