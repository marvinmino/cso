<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\News;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use \Auth;
use App\Exceptions\NewsNotFoundException;

class NewsService
{
    /**
     * @var news
     */
    protected $news;

    /**
     * NewsRepository constructor.
     *
     * @param News $news
     */
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     *
     * @param  $id
     * @return void
     */
    public function delete($id)
    {
        $news = $this->news->where('id', $id)->first();
        if (!empty($news)) {
            $news->delete();
        } else {
            abort(500);
        }
    }
    
    /**
     * Save news
     *
     * @param $data
     * @return news
     */
    public function save($data)
    {
        $data['user_id'] = Auth::user()->id;
        return $this->news->create($data);
    }

    /**
     * Update news
     *
     * @param $data
     */
    public function update($data, $id)
    {
        $news = $this->news->where('id', $id)->firstOrFail();
        $news->update($data);
    }

    /**
     *
     * @param $request
     * @return void
     */
    public function published($request)
    {
        $query = $this->news->published();
                    
        return $query->paginate(10);
    }

    /**
    *
    * @param $request
    * @return void
    */
    public function index($request)
    {
        $query = $this->news->published();
                    
        return $query->paginate(10);
    }

    /**
     *
     * @param $id
     * @return void
     */
    public function publish($id)
    {
        $news = $this->news->where('id', $id);
        $news->update(['published' => 1]);
    }

    /**
     *
     * @param  $id
     * @return void
     */
    public function unpublish($id)
    {
        $news = $this->news->where('id', $id);
        $news->update(['published' => 0]);
    }
}
