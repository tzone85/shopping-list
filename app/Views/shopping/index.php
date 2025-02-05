<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Shopping List</h1>
    
    <!-- Add Item Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="/create" method="POST" class="row g-3">
                <div class="col-sm-6">
                    <label for="itemName" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="itemName" name="name" 
                           placeholder="Enter item name" required minlength="2" 
                           pattern="^(?!\d+$)[\w\s-]+$" 
                           title="Item name must be at least 2 characters long and cannot be numbers only">
                    <div class="form-text">Must be at least 2 characters long and cannot be numbers only</div>
                </div>
                <div class="col-sm-3">
                    <label for="itemQuantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="itemQuantity" name="quantity" value="1" min="1" required>
                </div>
                <div class="col-sm-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Add Item</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Shopping List -->
    <div class="list-group">
        <?php foreach ($items as $item): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <!-- Checkbox -->
                    <form action="/items/<?php echo $item['id']; ?>/toggle" method="POST" class="me-3">
                        <button type="submit" class="btn btn-link p-0">
                            <i class="bi bi-<?php echo $item['checked'] ? 'check-square' : 'square'; ?>"></i>
                        </button>
                    </form>
                    
                    <!-- Item Details -->
                    <div class="<?php echo $item['checked'] ? 'text-decoration-line-through text-muted' : ''; ?>">
                        <span class="me-2"><?php echo htmlspecialchars($item['name']); ?></span>
                        <small class="text-muted">×<?php echo $item['quantity']; ?></small>
                    </div>
                </div>
                
                <div class="btn-group">
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $item['id']; ?>">
                        <i class="bi bi-pencil"></i>
                    </button>
                    
                    <!-- Delete Button -->
                    <form action="/items/<?php echo $item['id']; ?>/delete" method="POST" class="d-inline">
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?php echo $item['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/items/<?php echo $item['id']; ?>/update" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
