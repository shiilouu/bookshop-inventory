
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<main class="page-content">
    <div class="page-heading">
        <div>
            <h1>Manage Books</h1>
            <p>View, add, and organize your book collection.</p>
        </div>

        <button class="btn-primary" onclick="openModal('bookModal')">
            <i class="fas fa-plus"></i> Add Book
        </button>
    </div>

    <div class="content-card">
        <div class="card-heading">
            <div>
                <h2>Book Inventory</h2>
                <p>List of registered books.</p>
            </div>

            <input type="text" class="search-input" placeholder="Search books..."
                   onkeyup="searchTable(this, 'bookTable')">
        </div>

        <div class="table-wrapper">
            <table id="bookTable">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>BK-001</td>
                        <td>Introduction to Programming</td>
                        <td>John Smith</td>
                        <td>Technology</td>
                        <td>₱550.00</td>
                        <td>25</td>
                        <td><span class="status available">Available</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit book feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete book feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>BK-002</td>
                        <td>Database Management</td>
                        <td>Mary Johnson</td>
                        <td>Education</td>
                        <td>₱650.00</td>
                        <td>5</td>
                        <td><span class="status low">Low Stock</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit book feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete book feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>BK-003</td>
                        <td>Web Development Basics</td>
                        <td>David Lee</td>
                        <td>Technology</td>
                        <td>₱480.00</td>
                        <td>18</td>
                        <td><span class="status available">Available</span></td>
                        <td>
                            <button class="btn-icon edit" onclick="showMessage('Edit book feature is a UI preview.')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="showMessage('Delete book feature is a UI preview.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal" id="bookModal">
    <div class="modal-content">
        <div class="modal-heading">
            <h2>Add New Book</h2>
            <button class="close-modal" onclick="closeModal('bookModal')">&times;</button>
        </div>

        <form class="demo-form">
            <div class="form-group">
                <label>Book Title</label>
                <input type="text" name="book_title" placeholder="Enter book title" required>
            </div>

            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" placeholder="Enter author name" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <option value="1">Technology</option>
                    <option value="2">Education</option>
                    <option value="3">Fiction</option>
                    <option value="4">Business</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" min="0" placeholder="0" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('bookModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Book</button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
