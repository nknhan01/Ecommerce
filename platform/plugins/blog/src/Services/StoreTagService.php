<?php

namespace Platform\Blog\Services;

use Platform\ACL\Models\User;
use Platform\Base\Events\CreatedContentEvent;
use Platform\Blog\Models\Post;
use Platform\Blog\Services\Abstracts\StoreTagServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreTagService extends StoreTagServiceAbstract
{

    /**
     * @param Request $request
     * @param Post $post
     * @return mixed|void
     */
    public function execute(Request $request, Post $post)
    {
        $tags = $post->tags->pluck('name')->all();

        $tagsInput = collect(json_decode($request->input('tag'), true))->pluck('value')->all();

        if (count($tags) != count($tagsInput) || count(array_diff($tags, $tagsInput)) > 0) {
            $post->tags()->detach();
            foreach ($tagsInput as $tagName) {

                if (!trim($tagName)) {
                    continue;
                }

                $tag = $this->tagRepository->getFirstBy(['name' => $tagName]);

                if ($tag === null && !empty($tagName)) {
                    $tag = $this->tagRepository->createOrUpdate([
                        'name'        => $tagName,
                        'author_id'   => Auth::check() ? Auth::user()->getKey() : 0,
                        'author_type' => User::class,
                    ]);

                    $request->merge(['slug' => $tagName]);

                    event(new CreatedContentEvent(TAG_MODULE_SCREEN_NAME, $request, $tag));
                }

                if (!empty($tag)) {
                    $post->tags()->attach($tag->id);
                }
            }
        }
    }
}
