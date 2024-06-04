<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="cart-table-wrap">
				<table class="cart-table dataTable" style="width: 100%;">
					<thead id="pendingTable" class="cart-table-head">
						<tr class="table-head-row">
							<th>Transaction #</th>
							<th>To pay</th>
							<th>Date Ordered</th>
							<th>Status</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Fetch orders
						$queryPending = "SELECT * FROM orders WHERE order_status = 'Pending' AND userid = ?";
						$stmtPending = $link->prepare($queryPending);
						$stmtPending->bind_param("i", $verifiedUID);
						$stmtPending->execute();
						$resultPending = $stmtPending->get_result();

						while ($row = $resultPending->fetch_assoc()) {
							$status = $row['order_status'];
							$order_id = $row['order_id'];
							$transaction_number = $row['transaction_number'];
							$createdAt = date('d M Y', strtotime($row['created_at']));

							if ($status === 'Pending') {
								$status = '<label class="badge badge-warning">Pending</label>';
							}

							echo "<tr>
                    <td data-label='Transaction #'>{$row['transaction_number']}</td>
                    <td data-label='To pay' style='color: green;'>₱" . number_format($row['total_amount'], 2, '.', ',') . "</td>
                    <td data-label='Date Ordered'>{$createdAt}</td>
                    <td data-label='Status'>{$status}</td>
                    <td data-label='Actions'>
                        <button type='button' data-txn='{$row['transaction_number']}' class='view-order btn btn-primary btn-md'><i class='fas fa-shopping-cart'></i></button>
                        <button type='button' class='cancel_request btn btn-danger btn-md' data-order_id='$order_id'>Cancel</button>
                    </td>
                </tr>";
						}

						$stmtPending->close();
						?>
					</tbody>

				</table>
			</div>
		</div>


	</div>
</div>

