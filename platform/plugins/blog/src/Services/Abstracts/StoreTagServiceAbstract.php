<?php

namespace Platform\Blog\Services\Abstracts;

use Platform\Blog\Models\Post;
use Platform\Blog\Repositories\Interfaces\TagInterface;
use Illuminate\Http\Request;

abstract class StoreTagServiceAbstract
{
    /**
     * @var TagInterface
     */
    protected $tagRepository;

    /**
     * StoreTagService constructor.
     * @param TagInterface $tagRepository
     */
    public function __construct(TagInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return mixed
     */
    abstract public function execute(Request $request, Post $post);
}
