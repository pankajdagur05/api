<?php include("auth.php");  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4834d4;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f1c40f;
            --bg: #F4F7FE;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 0px;
            color: #2d3436;
        }

        .header { margin-bottom: 20px; position:fixed;top:0px;z-index:9999;width: 100%;background: #fff8f8;padding:10px;}
        .header h2 { margin: 0; font-weight: 700; color: #130f40; }

        /* Summary Horizontal Scroll */
        .summary-wrapper {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 5px 5px 20px 5px;
            scrollbar-width: none;
            
        }
        .summary-wrapper::-webkit-scrollbar { display: none; }

        .stat-card {
            min-width: 140px;
            background: white;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
            border: 1px solid #fff;
            animation: fadeIn 0.5s ease;
        }

        .stat-card i { font-size: 20px; margin-bottom: 10px; display: block; }
        .stat-card span { font-size: 11px; color: #95a5a6; font-weight: 600; }
        .stat-card h3 { margin: 5px 0 0 0; font-size: 16px; color: #2d3436; }

        /* Installment List */
        .fee-title { font-size: 16px; font-weight: 700; margin: 10px 0 15px 5px; }

        .installment-card {
            background: white;
            border-radius: 22px;
            padding: 18px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 6px solid #eee;
            transition: 0.3s;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .inst-info h4 { margin: 0; font-size: 14px; }
        .inst-info p { margin: 3px 0 0 0; font-size: 11px; color: #7f8c8d; }

        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Status Variations */
        .paid { border-left-color: var(--success); }
        .pending { border-left-color: var(--warning); }
        .overdue { border-left-color: var(--danger); animation: pulseBorder 2s infinite; }

        @keyframes pulseBorder {
            0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(231, 76, 60, 0); }
            100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
        }

        .btn-pay {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-receipt {
            background: #f1f2f6;
            color: #2d3436;
            width: auto;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            padding:7px;
            font-size:12px;
        }

        /* Total Fees Sticky Footer */
        .footer-action {
            background: white;
            padding: 10px;
            border-radius: 25px 25px 0 0;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.05);
            margin: 20px -15px -15px -15px;
            position:fixed;
            bottom:15px;
            width:100%;
        }

        .footer-flex { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Fees Dashboard</h2>
   

<?php   
$fees_query = mysqli_query($host,"
    SELECT 
        SUM(debit)  AS total_fees,
        SUM(credit) AS total_paid
    FROM feesdue 
    WHERE cid='$cid' AND sid='$sid'
");
$fees_res = mysqli_fetch_assoc($fees_query);

$total_fees = $fees_res['total_fees'] ?? 0;
$total_paid = $fees_res['total_paid'] ?? 0;
$balance    = $total_fees - $total_paid;
?>    

    <div class="summary-wrapper">
        <div class="stat-card">
            <i class="fas fa-wallet" style="color: var(--primary);"></i>
            <span>Total Fees</span>
            <h3>₹ <?php echo $total_fees; ?></h3>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle" style="color: var(--success);"></i>
            <span>Paid Amount</span>
            <h3>₹ <?php echo $total_paid; ?></h3>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle" style="color: var(--danger);"></i>
            <span>Pending</span>
            <h3>₹ <?php echo $balance; ?></h3>
        </div>
    </div>
    
     </div>

<div style="padding: 15px;"> 

    <div style="overflow:scroll;margin-top:170px;margin-bottom:70px;">
    <div class="fee-title">Payment Schedule</div>
   <?php 
// 1. Updated Query: Humne subquery ko simplify kar diya hai
$inst_q = mysqli_query($host,"
(
    /* 1. All Payment Entries (Credits) */
    SELECT 
        f.id, f.cateid, f.invoice, 0 AS debit, f.credit, f.date, f.rdate,
        c.name AS ledger_name, 'Completed' AS row_type
    FROM feesdue f
    LEFT JOIN erp_category c ON c.id = f.cateid
    WHERE f.cid='$cid' AND f.sid='$sid' AND f.credit > 0
)
UNION ALL
(
    /* 2. All Due Entries (Debits) */
    /* Isme hum group by nahi karenge, seedhe debit entries uthayenge */
    SELECT 
        f.id, f.cateid, f.invoice, f.debit, 0 AS credit, f.date, f.rdate,
        c.name AS ledger_name, 'Due' AS row_type
    FROM feesdue f
    LEFT JOIN erp_category c ON c.id = f.cateid
    WHERE f.cid='$cid' AND f.sid='$sid' AND f.debit > 0
)
ORDER BY date ASC, id ASC
");

$category_balance = [];

// Pehle total balance nikal lete hain har category ka background mein
$balance_check = mysqli_query($host, "SELECT cateid, (SUM(debit) - SUM(credit)) as bal FROM feesdue WHERE sid='$sid' GROUP BY cateid");
while($bc = mysqli_fetch_assoc($balance_check)) {
    $category_balance[$bc['cateid']] = $bc['bal'];
}

while ($row = mysqli_fetch_assoc($inst_q)):
    
    // Agar row 'Due' type ki hai aur uska total balance 0 ho chuka hai, toh skip karein
    if ($row['row_type'] == 'Due' && ($category_balance[$row['cateid']] ?? 0) <= 0) {
        continue; 
    }

    if ($row['row_type'] == 'Completed') {
        $status = 'Completed';
        $card_class = 'paid';
        $status_bg = '#e8faf0';
        $status_col = 'var(--success)';
        $display_amount = $row['credit'];
    } else {
        $status = 'Overdue';
        $card_class = 'overdue';
        $status_bg = '#fdeaea';
        $status_col = 'var(--danger)';
        // Yahan hum specific balance dikhayenge jo bacha hai
        $display_amount = $category_balance[$row['cateid']]; 
    }
?>

<div class="installment-card <?php echo $card_class; ?>">
    <div class="inst-info">
        <h4><?php echo $row['ledger_name']; ?></h4>
        <?php if ($status == 'Completed') { ?>
            <p>Paid on <?php echo date('d M Y', strtotime($row['date'])); ?></p>
        <?php } else { ?>
            <p style="color:var(--danger); font-weight:600;">
                Due Date: <?php echo date('d M Y', strtotime($row['rdate'])); ?>
            </p>
        <?php } ?>

        <span class="status-badge" style="background:<?php echo $status_bg; ?>; color:<?php echo $status_col; ?>">
            <?php echo $status; ?>
        </span>
    </div>

    <div style="text-align:right;">
        <div style="font-weight:700; font-size:14px; margin-bottom:8px;">
            ₹ <?php echo number_format($display_amount); ?>
        </div>

        <?php if ($status == 'Completed') { ?>
            <a href="receipt.php?id=<?php echo $row['id']; ?>" class="btn-receipt">
                <i class="fas fa-file-invoice" style="margin-right:7px;"></i> <?php echo $row['invoice']; ?>
            </a>
        <?php } else { ?> 
            <button class="btn-pay pay-ledger"
                data-type="ledger" 
                data-cateid="<?php echo $row['cateid']; ?>" 
                data-amount="<?php echo $display_amount; ?>"> 
                Pay Now 
            </button>
        <?php } ?>
    </div>
</div>
<?php endwhile; ?>

</div>
</div>

<?php
$due_q = mysqli_query($host,"
    SELECT sum(debit) - sum(credit) AS total_due
    FROM feesdue
    WHERE cid='$cid' 
      AND sid='$sid' 
");
$due_res = mysqli_fetch_assoc($due_q);
$total_due_amount = $due_res['total_due'] ?? 0;
?>

<div class="footer-action">
    <div class="footer-flex">
        <div style=margin-left:15px;">
            <span style="font-size:11px; color:#95a5a6; font-weight:600;">Balance Due</span>
            <h2 style="margin:0; color:var(--danger);">
                ₹ <?php echo number_format($total_due_amount); ?>
            </h2>
        </div>

        <?php if($total_due_amount > 0){ ?>
            <button 
                class="btn-pay pay-all" data-type="all" data-amount="<?php echo $total_due_amount; ?>"
                style="padding:15px 30px; font-size:14px; border-radius:18px;">
                Pay All Dues <i class="fas fa-arrow-right" style="margin-left:5px;"></i>
            </button>
        <?php } ?>
    </div>
</div> 


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script>
document.querySelectorAll('.btn-pay').forEach(btn => {
    btn.addEventListener('click', function () {
        let amount = parseFloat(this.dataset.amount);
        let cateid = this.dataset.cateid ?? 0;
        
        btn.innerHTML = "Creating Order...";
        btn.disabled = true;

        fetch('create_order.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                sid: '<?php echo $sid; ?>',
                cid: '<?php echo $cid; ?>',
                cateid: cateid,
                amount: amount
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = "Pay Now"; // Reset button text
            if(data.status === 'success') {
                const options = {
                    key: data.key,
                    amount: data.amount * 100,
                    currency: 'INR',
                    order_id: data.order_id,
                    name: data.school_name || '<?php echo htmlspecialchars($branch_name ?? "School Fees"); ?>',
                    description: `Fee Payment for ${data.name}`,
                    prefill: {
                        name: data.name,
                        contact: data.mobile
                    },
                    theme: { color: '#3399cc' },
                    method: { 
                        netbanking: true,
                        card: true,
                        upi: true,
                        wallet: true 
                    },
                    "webview_intent": true,
                    "enable_partial_payments": true,
                    display: {
                        upi: { qr: true }
                    },
                    handler: function(response) {
                        // Payment success → verify_payment.php
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'verify_payment.php';
                        form.appendChild(createInput('razorpay_payment_id', response.razorpay_payment_id));
                        form.appendChild(createInput('razorpay_order_id', response.razorpay_order_id));
                        form.appendChild(createInput('razorpay_signature', response.razorpay_signature));
                        document.body.appendChild(form);
                        form.submit();
                    },
                    modal: {
                        ondismiss: function() {
                            alert('Payment cancel ho gaya');
                            btn.disabled = false;
                        }
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } else {
                alert("Error: " + data.message);
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = "Pay Now";
            btn.disabled = false;
            alert('Network error, dobara try karein');
        });
    });
});

function createInput(name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    return input;
}

</script>


</body>
</html>