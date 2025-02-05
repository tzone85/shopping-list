<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ShoppingItem;

/**
 * Shopping Controller
 * 
 * @package App\Controllers
 */
class ShoppingController extends Controller
{
    /**
     * Show the shopping list
     * 
     * @return void
     */
    public function indexAction(): void
    {
        $item = new ShoppingItem();
        $items = $item->all();
        
        $this->render('shopping/index.php', [
            'items' => $items
        ]);
    }

    /**
     * Add a new item
     * 
     * @return void
     */
    /**
     * Validate item name
     * 
     * @param string $name
     * @return bool
     */
    private function validateItemName(string $name): bool
    {
        // Check minimum length
        if (strlen($name) < 2) {
            return false;
        }
        
        // Check if it's not just numbers
        if (preg_match('/^\d+$/', $name)) {
            return false;
        }
        
        return true;
    }

    public function createAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $quantity = (int)($_POST['quantity'] ?? 1);
            
            if ($this->validateItemName($name)) {
                $item = new ShoppingItem();
                
                if ($item->create([
                    'name' => $name,
                    'quantity' => $quantity
                ])) {
                    $this->redirect('/');
                    return;
                }
            }
        }
        
        // If validation fails or creation fails, redirect back
        $this->redirect('/');
    }

    /**
     * Update an item
     * 
     * @return void
     */
    public function updateAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $quantity = (int)($_POST['quantity'] ?? 1);
            
            if ($this->validateItemName($name)) {
                $item = new ShoppingItem();
                
                if ($item->update($this->route_params['id'], [
                    'name' => $name,
                    'quantity' => $quantity
                ])) {
                    $this->redirect('/');
                    return;
                }
            }
        }
        
        $this->redirect('/');
    }

    /**
     * Toggle item checked status
     * 
     * @return void
     */
    public function toggleAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = new ShoppingItem();
            $item->toggleChecked($this->route_params['id']);
        }
        
        $this->redirect('/');
    }

    /**
     * Delete an item
     * 
     * @return void
     */
    public function deleteAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = new ShoppingItem();
            $item->delete($this->route_params['id']);
        }
        
        $this->redirect('/');
    }
}
