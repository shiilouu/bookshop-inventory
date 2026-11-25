
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<main class="page-content">
    <div class="page-heading">
        <div>
            <h1>Inventory Monitoring</h1>
            <p>Monitor book quantities and stock availability.</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-boxes-stacked"></i></div>
            <div>
                <p>Total Stock</p>
                <h2>250</h2>
                <small>Units in inventory</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <p>Low Stock</p>
                <h2>8</h2>
                <small>Needs restocking</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <p>In Stock</p>
                <h2>220</h2>
                <small>Normal stock level</small>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-heading">
            <div>
                <h2>Stock Overview</h2>
                <p>Current quantity of each registered book.</p>
            </div>

            <input type="text" class="search-input" placeholder="Search inventory..."
                   onkeyup="searchTable(this, 'inventoryTable')">
        </div>

        <div class="table-wrapper">
            <table id="inventoryTable">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Book Title</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Stock Level</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>BK-001</td>
                        <td>Introduction to Programming</td>
                        <td>Technology</td>
                        <td>25</td>
                        <td>
                            <div class="stock-bar">
                                <div class="stock-fill" style="width: 80%;"></div>
                            </div>
                        </td>
                        <td><span class="status available">In Stock</span></td>
                    </tr>

                    <tr>
                        <td>BK-002</td>
                        <td>Database Management</td>
                        <td>Education</td>
                        <td>5</td>
                        <td>
                            <div class="stock-bar">
                                <div class="stock-fill low-fill" style="width: 20%;"></div>
                            </div>
                        </td>
                        <td><span class="status low">Low Stock</span></td>
                    </tr>

                    <tr>
                        <td>BK-003</td>
                        <td>Web Development Basics</td>
                        <td>Technology</td>
                        <td>18</td>
                        <td>
                            <div class="stock-bar">
                                <div class="stock-fill" style="width: 65%;"></div>
                            </div>
                        </td>
                        <td><span class="status available">In Stock</span></td>
                    </tr>

                    <tr>
                        <td>BK-004</td>
                        <td>Business Fundamentals</td>
                        <td>Business</td>
                        <td>2</td>
                        <td>
                            <div class="stock-bar">
                                <div class="stock-fill low-fill" style="width: 10%;"></div>
                            </div>
                        </td>
                        <td><span class="status low">Low Stock</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-card">
        <div class="card-heading">
            <div>
                <h2>Low Stock Reminder</h2>
                <p>These books may need replenishment.</p>
            </div>
        </div>

        <div class="alert warning">
            <i class="fas fa-triangle-exclamation"></i>
            Database Management has only 5 copies remaining.
        </div>

        <div class="alert warning">
            <i class="fas fa-triangle-exclamation"></i>
            Business Fundamentals has only 2 copies remaining.
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
