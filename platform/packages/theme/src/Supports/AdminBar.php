<?php

namespace Botble\Theme\Supports;

use Throwable;

class AdminBar
{
    /**
     * @var array
     */
    protected $groups = [
        'appearance' => [
            'link'  => 'javascript:;',
            'title' => 'Appearance',
            'items' => [

            ],
        ],
        'add-new'    => [
            'link'  => 'javascript:;',
            'title' => 'Add new',
            'items' => [
            ],
        ],
    ];

    /**
     * @var bool
     */
    protected $isDisplay = true;

    /**
     * @var array
     */
    protected $noGroupLinks = [];

    /**
     * AdminBar constructor.
     */
    public function __construct()
    {
        $this->groups['appearance']['items'] = [];
        $this->groups['add-new']['items'] = [
            __('User')     => route('users.create'),
            __('Settings') => route('settings.options'),
        ];
    }

    /**
     * @return bool
     */
    public function isDisplay(): bool
    {
        return $this->isDisplay;
    }

    /**
     * @param bool $isDisplay
     * @return $this
     */
    public function setIsDisplay($isDisplay = true): self
    {
        $this->isDisplay = $isDisplay;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return array
     */
    public function getLinksNoGroup(): array
    {
        return $this->noGroupLinks;
    }

    /**
     * @param string $slug
     * @param string $title
     * @param string $link
     * @return $this
     */
    public function registerGroup($slug, $title, $link = 'javascript:;'): self
    {
        if (isset($this->groups[$slug])) {
            $this->groups[$slug]['items'][$title] = $link;
            return $this;
        }

        $this->groups[$slug] = [
            'title' => $title,
            'link'  => $link,
            'items' => [],
        ];

        return $this;
    }

    /**
     * @param string $title
     * @param string $url
     * @param null $group
     * @return $this
     */
    public function registerLink(string $title, string $url, $group = null): self
    {
        if ($group === null || !isset($this->groups[$group])) {
            $this->noGroupLinks[] = [
                'link'  => $url,
                'title' => $title,
            ];
        } else {
            $this->groups[$group]['items'][$title] = $url;
        }

        return $this;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function render(): string
    {
        return view('packages/theme::admin-bar')->render();
    }
}
