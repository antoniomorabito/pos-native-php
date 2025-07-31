<!DOCTYPE html>
<html>
<head>
    <title>Receipt - <?= $sale['invoice_number'] ?></title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
        }
        
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .shop-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .shop-info {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .invoice-info {
            margin: 10px 0;
            font-size: 12px;
        }
        
        .items {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            margin: 10px 0;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 12px;
        }
        
        .item-name {
            flex: 1;
        }
        
        .item-qty {
            margin: 0 10px;
        }
        
        .item-price {
            text-align: right;
            width: 80px;
        }
        
        .totals {
            font-size: 12px;
            margin: 10px 0;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }
        
        .total-line.grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .payment-info {
            margin: 10px 0;
            font-size: 12px;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        
        @media print {
            body { margin: 0; padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="shop-name"><?= APP_NAME ?></div>
            <div class="shop-info">
                Jl. Contoh No. 123<br>
                Telp: 081234567890
            </div>
        </div>
        
        <!-- Invoice Info -->
        <div class="invoice-info">
            <div>Invoice: <?= $sale['invoice_number'] ?></div>
            <div>Date: <?= formatDateTime($sale['created_at']) ?></div>
            <div>Cashier: <?= $sale['cashier_name'] ?></div>
            <?php if ($sale['customer_name']): ?>
            <div>Customer: <?= $sale['customer_name'] ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Items -->
        <div class="items">
            <?php foreach ($sale['items'] as $item): ?>
            <div class="item">
                <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
            </div>
            <div class="item">
                <div class="item-qty"><?= $item['quantity'] ?> x <?= formatCurrency($item['price']) ?></div>
                <div class="item-price"><?= formatCurrency($item['subtotal']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Totals -->
        <div class="totals">
            <div class="total-line">
                <span>Subtotal:</span>
                <span><?= formatCurrency($sale['subtotal']) ?></span>
            </div>
            
            <?php if ($sale['discount_amount'] > 0): ?>
            <div class="total-line">
                <span>Discount (<?= $sale['discount_percent'] ?>%):</span>
                <span>-<?= formatCurrency($sale['discount_amount']) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="total-line">
                <span>Tax (<?= $sale['tax_percent'] ?>%):</span>
                <span><?= formatCurrency($sale['tax_amount']) ?></span>
            </div>
            
            <div class="total-line grand-total">
                <span>TOTAL:</span>
                <span><?= formatCurrency($sale['total']) ?></span>
            </div>
        </div>
        
        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-line">
                <span>Payment (<?= ucfirst($sale['payment_method']) ?>):</span>
                <span><?= formatCurrency($sale['paid_amount']) ?></span>
            </div>
            
            <?php if ($sale['change_amount'] > 0): ?>
            <div class="total-line">
                <span>Change:</span>
                <span><?= formatCurrency($sale['change_amount']) ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>Terima kasih atas kunjungan Anda</div>
            <div>Barang yang sudah dibeli tidak dapat dikembalikan</div>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment to auto-print
            // window.print();
        };
    </script>
</body>
</html>