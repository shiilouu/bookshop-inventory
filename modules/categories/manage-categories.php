
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<main class="page-content">
    <div class="page-heading">
        <div>
            <h1>Manage Categories</h1>
            <p>Create and organize your book categories.</p>
        </div>

        <button class="btn-primary" onclick="openModal('categoryModal')">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>

    <div class="content-card">
        <div class="card-heading">
            <div>
                <h2>Category List</h2>
                <p>Manage all available book categories.</p>
            </div>

            <input type="text" class="search-input" placeholder="Search category..."
                   onkeyup="searchTable(this, 'categoryTable')">
        </div>

        <div class="table-wrapper">
            <table id="categoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Number of Books</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Technology</td>
                        <td>Books about computers and IT.</td>
                        <td>45</td>
                        <td><span class="status available">Active</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit category feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete category feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Education</td>
                        <td>Learning and academic materials.</td>
                        <td>35</td>
                        <td><span class="status available">Active</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit category feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete category feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Fiction</td>
                        <td>Stories and imaginative literature.</td>
                        <td>60</td>
                        <td><span class="status available">Active</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit category feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete category feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>Business</td>
                        <td>Management and entrepreneurship.</td>
                        <td>25</td>
                        <td><span class="status available">Active</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit category feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete category feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal" id="categoryModal">
    <div class="modal-content">
        <div class="modal-heading">
            <h2>Add New Category</h2>
            <button class="close-modal" onclick="closeModal('categoryModal')">&times;</button>
        </div>

        <form class="demo-form">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category_name" placeholder="Enter category name" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Enter category description" rows="3"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('categoryModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
