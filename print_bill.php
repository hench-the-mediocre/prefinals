<?php
require_once 'config.php';

$orderId = $_GET['order_id'] ?? 0;

// Get order
$stmt = $pdo->prepare("
    SELECT o.*, 
           u1.user_name as waiter_name,
           u2.user_name as cashier_name
    FROM order_table o
    LEFT JOIN user_table u1 ON o.waiter_id = u1.user_id
    LEFT JOIN user_table u2 ON o.cashier_id = u2.user_id
    WHERE o.order_id = ?
");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

// Get items
$stmt = $pdo->prepare("SELECT * FROM order_item_table WHERE order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bill - <?= htmlspecialchars($order['order_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .total-row { font-weight: bold; font-size: 1.2em; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Restaurant Bill</h1>
        <p>Order #: <?= htmlspecialchars($order['order_number']) ?></p>
        <p>Table: <?= htmlspecialchars($order['table_name']) ?></p>
        <p>Date: <?= $order['order_date'] ?> <?= $order['order_time'] ?></p>
        <p>Waiter: <?= htmlspecialchars($order['waiter_name']) ?></p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>$<?= number_format($item['product_price'], 2) ?></td>
                <td><?= $item['product_quantity'] ?></td>
                <td>$<?= number_format($item['product_total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right">Subtotal:</td>
                <td>$<?= number_format($order['order_total_amount'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" align="right">Tax:</td>
                <td>$<?= number_format($order['order_tax_amount'], 2) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" align="right">Total:</td>
                <td>$<?= number_format($order['order_net_amount'], 2) ?></td>
            </tr>
        </tfoot>
    </table>
    
    <div style="text-align: center; margin-top: 40px;">
        <p>Thank you for dining with us!</p>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">Print Bill</button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 16px; cursor: pointer; margin-left: 10px;">Close</button>
    </div>
    
    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
