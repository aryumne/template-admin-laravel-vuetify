<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Repositories\BlogRepository;

class FeBlogController extends Controller
{
    protected $blogRepo;

    function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepo = $blogRepository;
    }
}
