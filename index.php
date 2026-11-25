
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="page-content">
    <div class="page-heading">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back! Here's your bookshop overview.</p>
        </div>
        <span class="date">
            <i class="fas fa-calendar"></i>
            <?php echo date('F d, Y'); ?>
        </span>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-book"></i></div>
            <div>
                <p>Total Books</p>
                <h2>250</h2>
                <small>Books in inventory</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon cyan"><i class="fas fa-layer-group"></i></div>
            <div>
                <p>Categories</p>
                <h2>12</h2>
                <small>Book categories</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <p>Low Stock</p>
                <h2>8</h2>
                <small>Books need restocking</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <p>Available</p>
                <h2>220</h2>
                <small>Books available</small>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-heading">
            <div>
                <h2>Quick Actions</h2>
                <p>Manage your bookshop inventory easily.</p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="modules/books/manage-books.php" class="action-card">
                <i class="fas fa-book"></i>
                <span>Manage Books</span>
                <small>View and organize books</small>
            </a>

            <a href="modules/categories/manage-categories.php" class="action-card">
                <i class="fas fa-folder"></i>
                <span>Manage Categories</span>
                <small>Organize book categories</small>
            </a>

            <a href="modules/books/inventory-monitoring.php" class="action-card">
                <i class="fas fa-boxes-stacked"></i>
                <span>Inventory Monitoring</span>
                <small>Check stock availability</small>
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-heading">
            <div>
                <h2>Recent Inventory</h2>
                <p>Sample recently added books.</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Introduction to Programming</td>
                        <td>John Smith</td>
                        <td>Technology</td>
                        <td><span class="status available">Available</span></td>
                    </tr>
                    <tr>
                        <td>Database Management</td>
                        <td>Mary Johnson</td>
                        <td>Education</td>
                        <td><span class="status low">Low Stock</span></td>
                    </tr>
                    <tr>
                        <td>Web Development Basics</td>
                        <td>David Lee</td>
                        <td>Technology</td>
                        <td><span class="status available">Available</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
