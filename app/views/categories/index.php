<?php require_once VIEW_PATH . 'layouts/header.php'; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Categories List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Products</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td><?= htmlspecialchars($category['description'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $category['product_count'] ?> products</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" 
                                                class="btn btn-info edit-category" 
                                                data-id="<?= $category['id'] ?>"
                                                data-name="<?= htmlspecialchars($category['name']) ?>"
                                                data-description="<?= htmlspecialchars($category['description'] ?? '') ?>"
                                                data-bs-toggle="tooltip" 
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($category['product_count'] == 0): ?>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete('<?= BASE_URL ?>categories/delete/<?= $category['id'] ?>')"
                                                data-bs-toggle="tooltip" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0" id="form-title">Add Category</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>categories" id="category-form">
                    <input type="hidden" name="action" id="form-action" value="add">
                    <input type="hidden" name="id" id="category-id">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" style="display: none;">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.edit-category').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var description = $(this).data('description');
        
        $('#form-title').text('Edit Category');
        $('#form-action').val('edit');
        $('#category-id').val(id);
        $('#name').val(name);
        $('#description').val(description);
        $('#btn-cancel').show();
        
        $('html, body').animate({
            scrollTop: $('#category-form').offset().top - 100
        }, 500);
    });
    
    $('#btn-cancel').on('click', function() {
        $('#form-title').text('Add Category');
        $('#form-action').val('add');
        $('#category-id').val('');
        $('#name').val('');
        $('#description').val('');
        $('#btn-cancel').hide();
    });
});
</script>

<?php require_once VIEW_PATH . 'layouts/footer.php'; ?>