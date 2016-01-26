<?php

namespace App\Http\Requests;

use App\Interfaces\ItemRepositoryInterface;
use Auth;

class UpdateItemPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param ItemRepositoryInterface $itemRepo
     * @return bool
     */
    public function authorize(ItemRepositoryInterface $itemRepo)
    {
        $itemId = $this->input('itemId');
        $item = $itemRepo->get($itemId, ['user']);
        return ($item->user->id == Auth::user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
