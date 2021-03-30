<?php

namespace Botble\Media\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'created_at' => date_from_database($this->created_at, 'Y-m-d H:i:s'),
            'updated_at' => date_from_database($this->updated_at, 'Y-m-d H:i:s'),
        ];
    }
}
