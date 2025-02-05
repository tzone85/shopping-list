<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;

/**
 * Posts Controller
 * 
 * @package App\Controllers
 */
class PostsController extends Controller
{
    /**
     * Show the index page
     * 
     * @return void
     */
    public function indexAction(): void
    {
        $post = new Post();
        $posts = $post->all();
        
        $this->render('posts/index.php', [
            'posts' => $posts
        ]);
    }

    /**
     * Show a single post
     * 
     * @return void
     */
    public function showAction(): void
    {
        $post = new Post();
        $post_data = $post->find($this->route_params['id']);
        
        $this->render('posts/show.php', [
            'post' => $post_data
        ]);
    }

    /**
     * Create a new post
     * 
     * @return void
     */
    public function createAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = new Post();
            
            if ($post->create([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'created_at' => date('Y-m-d H:i:s')
            ])) {
                $this->redirect('/posts');
            }
        }
        
        $this->render('posts/create.php');
    }

    /**
     * Show the edit form for a post
     * 
     * @return void
     */
    public function editAction(): void
    {
        $post = new Post();
        $post_data = $post->find($this->route_params['id']);
        
        if (!$post_data) {
            throw new \Exception('Post not found', 404);
        }
        
        $this->render('posts/edit.php', [
            'post' => $post_data
        ]);
    }

    /**
     * Update a post
     * 
     * @return void
     */
    public function updateAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = new Post();
            
            if ($post->update($this->route_params['id'], [
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ])) {
                $this->redirect('/posts/' . $this->route_params['id']);
            }
        }
        
        $this->redirect('/posts');
    }

    /**
     * Delete a post
     * 
     * @return void
     */
    public function deleteAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = new Post();
            
            if ($post->delete($this->route_params['id'])) {
                $this->redirect('/posts');
            }
        }
        
        $this->redirect('/posts');
    }
}
