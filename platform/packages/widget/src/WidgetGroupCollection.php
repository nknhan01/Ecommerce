<?php

namespace Botble\Widget;

use Botble\Widget\Contracts\ApplicationWrapperContract;
use Illuminate\Support\Arr;

class WidgetGroupCollection
{
    /**
     * The array of widget groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * @var ApplicationWrapperContract
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param ApplicationWrapperContract $app
     */
    public function __construct(ApplicationWrapperContract $app)
    {
        $this->app = $app;
    }

    /**
     * Get the widget group object.
     *
     * @param string $sidebarId
     * @return WidgetGroup
     */
    public function group($sidebarId)
    {
        if (isset($this->groups[$sidebarId])) {
            return $this->groups[$sidebarId];
        }
        $this->groups[$sidebarId] = new WidgetGroup(['id' => $sidebarId, 'name' => $sidebarId], $this->app);
        return $this->groups[$sidebarId];
    }

    /**
     * @param $args
     * @return $this|mixed
     */
    public function setGroup($args)
    {
        if (isset($this->groups[$args['id']])) {
            $group = $this->groups[$args['id']];
            $group->setName(Arr::get($args, 'name'));
            $group->setDescription(Arr::get($args, 'description'));
            $this->groups[$args['id']] = $group;
        } else {
            $this->groups[$args['id']] = new WidgetGroup($args, $this->app);
        }
        return $this;
    }

    /**
     * @param string $groupId
     * @return $this
     */
    public function removeGroup($groupId)
    {
        if (isset($this->groups[$groupId])) {
            unset($this->groups[$groupId]);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
