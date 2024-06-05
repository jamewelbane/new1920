<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="cart-table-wrap">
                <table class="cart-table dataTable" style="width: 100%;">
                    <thead class="cart-table-head">
                        <tr class="table-head-row">
                            <th>Transaction #</th>
                            <th>Refunded</th>
                            <th>Date Cancelled</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch cancelled orders
                        $queryCancelled = "SELECT * FROM cancellation_request WHERE userid = ?";
                        $stmtCancelled = $link->prepare($queryCancelled);
                        $stmtCancelled->bind_param("i", $verifiedUID);
                        $stmtCancelled->execute();
                        $resultCancelled = $stmtCancelled->get_result();

                        while ($rowCancelled = $resultCancelled->fetch_assoc()) {
                            $cancelled_order_id = $rowCancelled['order_id'];
                            $date_cancelled = date('d M Y', strtotime($rowCancelled['date']));
                            $cancel_status = $rowCancelled['status'];

                            // Fetch orders 
                            $queryOrder = "SELECT * FROM orders WHERE order_id = ? AND userid = ?";
                            $stmtOrder = $link->prepare($queryOrder);
                            $stmtOrder->bind_param("ii", $cancelled_order_id, $verifiedUID);
                            $stmtOrder->execute();
                            $resultOrder = $stmtOrder->get_result();

                            while ($row = $resultOrder->fetch_assoc()) {
                                $status = $row['order_status'];
                                $order_id = $row['order_id'];
                                $transaction_number = $row['transaction_number'];
                                $createdAt = date('d M Y', strtotime($row['created_at']));

                                if ($status === 'Cancelled') {
                                    $statusLabel = '<label class="badge badge-success">Cancelled</label>';
                                } else if ($cancel_status === 3) {
                                    $statusLabel = '<label class="badge badge-danger">Rejected</label>';
                                } else {
                                    $statusLabel = '<label class="badge badge-warning">' . $status . '</label>';
                                }

                                echo "<tr>
                <td data-label='Transaction #'>{$transaction_number}</td>
                <td data-label='To pay' style='color: green;'>₱" . number_format($row['total_amount'], 2, '.', ',') . "</td>
                <td data-label='Date Ordered'>{$createdAt}</td>
                <td data-label='Status'>{$statusLabel}</td>
                <td data-label='Actions'>
                    <button type='button' data-txn='{$transaction_number}' class='view-order btn btn-primary btn-md'><i class='fas fa-shopping-cart'></i></button>
                </td>
            </tr>";
                            }

                            $stmtOrder->close();
                        }

                        $stmtCancelled->close();
                        ?>
                    </tbody>


                </table>
            </div>
        </div>


    </div>
</div>