<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [ 'required', 'string', 'min:5', 'max:30'],
            'sub_title' => [ 'required', 'string', 'min:5', 'max:50' ],
            'content' => [ 'required' , 'string', 'min:5' , 'max:255' ],
            'img_src' => [ 'required', 'url:https' ],
            'author_name' => [ 'required', 'string', 'min:5', 'max:30' ],
            'order_no' => [ 'required', 'integer' ]
        ];
    }
}
