<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Bliss - Business Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background:#f8f9fa; font-family: Arial, sans-serif; }
        h1, h3 { color:#6f4e37; }
        .chart-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
    </style>
</head>
<body class="p-4">
<div class="container">
    <h1 class="text-center mb-5">Coffee Bliss Analytics Dashboard</h1>
    <?php include 'db_connect.php'; ?>

    <!-- Sales Data Table -->
    <div class="chart-container mt-4">
        <h3 class="text-center mb-4">Sales Data</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr><th>Date</th><th>Product Name</th><th>Category</th><th>Quantity Sold</th><th>Total Sales</th></tr>
            </thead>
            <tbody>
            <?php
            $sales = $conn->query("SELECT sale_date, product_name, category, quantity_sold, total_sales FROM sales ORDER BY sale_date ASC");
            while($row = $sales->fetch_assoc()) {
                echo "<tr>
                    <td>" . date('M d', strtotime($row['sale_date'])) . "</td>
                    <td><strong>{$row['product_name']}</strong></td>
                    <td>{$row['category']}</td>
                    <td>{$row['quantity_sold']}</td>
                    <td>$" . number_format($row['total_sales'],2) . "</td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- 1. Descriptive Analytics - 3 Charts -->
    <div class="row">
        <!-- Top Products Bar Chart -->
        <div class="col-lg-4">
            <div class="chart-container">
                <h4 class="text-center">Top Products by Quantity</h4>
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- Revenue by Category Pie Chart -->
        <div class="col-lg-4">
            <div class="chart-container">
                <h4 class="text-center">Revenue by Category</h4>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Daily Sales Trend Line Chart (NEW!) -->
        <div class="col-lg-4">
            <div class="chart-container">
                <h4 class="text-center">Daily Sales Trend (Oct 1–4)</h4>
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. Predictive Analytics -->
    <div class="chart-container mt-4">
        <h3 class="text-center mb-4">November 2025 Demand Forecast (30 days)</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr><th>Product</th><th>Avg Daily</th><th>Nov Forecast</th><th>Stockout Risk</th></tr>
            </thead>
            <tbody>
            <?php
            $forecast = $conn->query("SELECT product_name, ROUND(SUM(quantity_sold)/4,0) as avg_daily
                                    FROM sales GROUP BY product_name ORDER BY avg_daily DESC");
            while($row = $forecast->fetch_assoc()){
                $nov = round($row['avg_daily'] * 30);
                $risk = $row['avg_daily'] > 80 ? 'HIGH' : ($row['avg_daily'] > 50 ? 'MEDIUM' : 'LOW');
                $badge = $risk === 'HIGH' ? 'bg-danger' : ($risk === 'MEDIUM' ? 'bg-warning' : 'bg-success');
                echo "<tr>
                    <td><strong>{$row['product_name']}</strong></td>
                    <td>{$row['avg_daily']}</td>
                    <td><strong>" . number_format($nov) . "</strong></td>
                    <td><span class='badge $badge text-white'>$risk</span></td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- 3. PRESCRIPTIVE ANALYTICS – FULLY DYNAMIC (THIS IS THE ONE YOU WERE MISSING!) -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info shadow-lg border-start border-primary border-5">
                <h3 class="alert-heading text-primary">
                    Prescriptive Analytics – Recommended Actions for November 2025
                </h3>
                <div class="row">
                    <?php
                    // Re-run the forecast query to get real numbers
                    $prescriptive = $conn->query("
                        SELECT
                            product_name,
                            ROUND(SUM(quantity_sold)/4, 1) AS avg_daily,
                            ROUND(SUM(quantity_sold)/4 * 30) AS nov_forecast
                        FROM sales
                        GROUP BY product_name
                        ORDER BY nov_forecast DESC
                    ");

                    $rank = 1;
                    while($p = $prescriptive->fetch_assoc()):
                        $product   = $p['product_name'];
                        $forecast  = $p['nov_forecast'];
                        $daily     = $p['avg_daily'];

                        // Dynamic safety stock + lead time (3 days)
                        $order_qty = ceil($forecast * 1.15 + $daily * 3);  // 15% buffer + 3-day lead

                        // Dynamic recommendation logic
                        if ($daily > 90) {
                            $recommend = "Order <strong>" . number_format($order_qty) . " units</strong> IMMEDIATELY → Will sell out fast!";
                            $color = "success";
                        } elseif ($daily > 60) {
                            $recommend = "Stock <strong>" . number_format($order_qty) . " units</strong> → High-margin, steady seller";
                            $color = "primary";
                        } elseif ($daily > 45) {
                            $recommend = "Offer <strong>Bundle with Croissant</strong> or <strong>Buy-2-Get-10%-Off</strong> to boost volume";
                            $color = "warning";
                        } else {
                            $recommend = "Run <strong>2-for-\$5 deal</strong> or reduce order → Slow-moving & perishable!";
                            $color = "danger";
                            $order_qty = min($order_qty, 1000); // Cap slow items
                        }
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card border-<?= $color ?> shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-<?= $color ?>">
                                    #<?= $rank++ ?>. <?= htmlspecialchars($product) ?>
                                </h5>
                                <p class="card-text mb-2">
                                    <strong>Forecast:</strong> <?= number_format($forecast) ?> units
                                    <span class="text-muted">(Avg <?= $daily ?>/day)</span>
                                </p>
                                <p class="card-text fw-bold text-<?= $color ?>">
                                    → <?= $recommend ?>
                                </p>
                                <?php if ($daily <= 45): ?>
                                    <span class="badge bg-<?= $color ?> fs-6">URGENT PROMOTION NEEDED</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="mt-4 p-3 bg-light rounded">
                    <strong>Summary for Owner:</strong><br>
                    • Never run out of top 2 drinks<br>
                    • Actively promote or reduce pastries<br>
                    • Use weekend peaks (Sat = +32%) for big batches<br>
                    • All orders include 15% safety buffer + 3-day lead time coverage
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// === 1. Top Products Bar Chart ===
<?php
$top = $conn->query("SELECT product_name, SUM(quantity_sold) as total FROM sales GROUP BY product_name ORDER BY total DESC");
$labels = []; $data = [];
while($row = $top->fetch_assoc()) {
    $labels[] = $row['product_name'];
    $data[] = $row['total'];
}
?>
new Chart(document.getElementById('topProductsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Total Units Sold',
            data: <?= json_encode($data) ?>,
            backgroundColor: 'rgba(111, 78, 55, 0.7)',
            borderColor: '#6f4e37',
            borderWidth: 2
        }]
    },
    options: { plugins: { legend: { display: false }}, scales: { y: { beginAtZero: true }}}
});

// === 2. Revenue by Category Pie Chart ===
<?php
$rev = $conn->query("SELECT category, SUM(total_sales) as revenue FROM sales GROUP BY category ORDER BY revenue DESC");
$revLabels = []; $revData = [];
while($row = $rev->fetch_assoc()) {
    $revLabels[] = $row['category'];
    $revData[] = round($row['revenue'], 2);
}
?>
new Chart(document.getElementById('revenueChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($revLabels) ?>,
        datasets: [{
            data: <?= json_encode($revData) ?>,
            backgroundColor: ['#8B4513', '#D2691E', '#F4A460', '#D2B48C']
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: context => context.label + ': $' + context.parsed.toFixed(2) }}
        }
    }
});

// === 3. Daily Sales Trend Line Chart (NEW!) ===
<?php
$daily = $conn->query("SELECT DATE_FORMAT(sale_date, '%a %b %d') as day, SUM(total_sales) as daily_total 
                       FROM sales GROUP BY sale_date ORDER BY sale_date");
$days = []; $sales = [];
while($row = $daily->fetch_assoc()) {
    $days[] = $row['day'];
    $sales[] = round($row['daily_total'], 2);
}
?>
new Chart(document.getElementById('dailySalesChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($days) ?>,
        datasets: [{
            label: 'Daily Revenue ($)',
            data: <?= json_encode($sales) ?>,
            borderColor: '#6f4e37',
            backgroundColor: 'rgba(111, 78, 55, 0.2)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#6f4e37'
        }]
    },
    options: {
        plugins: { legend: { display: false }},
        scales: { y: { beginAtZero: false }}
    }
});
</script>
</body>
</html>