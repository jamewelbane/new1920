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

							// Check if there is a pending cancellation request for this order
							$checkCancellationQuery = "SELECT * FROM cancellation_request WHERE order_id = ? AND status = 0";
							$stmtCancellation = $link->prepare($checkCancellationQuery);
							$stmtCancellation->bind_param("i", $order_id);
							$stmtCancellation->execute();
							$resultCancellation = $stmtCancellation->get_result();
							$cancellationExists = $resultCancellation->num_rows > 0;
							$stmtCancellation->close();

							if ($status === 'Pending') {
								$statusLabel = '<label class="badge badge-warning">Pending</label>';
							} else {
								$statusLabel = $status;
							}

							echo "<tr>
							<td data-label='Transaction #'>{$transaction_number}</td>
							<td data-label='To pay' style='color: green;'>₱" . number_format($row['total_amount'], 2, '.', ',') . "</td>
							<td data-label='Date Ordered'>{$createdAt}</td>
							<td data-label='Status'>";

							if (!$cancellationExists) {
								echo $statusLabel;
							} else {
								echo "<label class='badge badge-secondary'>Cancellation Pending</label>";
							}

							echo "</td>
							<td data-label='Actions'>
								<button type='button' data-txn='{$transaction_number}' class='view-order btn btn-primary btn-md'><i class='fas fa-shopping-cart'></i></button>";

							if (!$cancellationExists) {
								echo "<button type='button' class='cancel_request btn btn-danger btn-md' data-order_id='$order_id'>Cancel</button>";
							}

							echo "</td></tr>";
						}

						$stmtPending->close();
						?>
					</tbody>

					<script>
						document.addEventListener('DOMContentLoaded', (event) => {

							var cancelButtons = document.querySelectorAll('.cancel_request');
							// Loop through each cancel button
							cancelButtons.forEach(function(button) {
								// Add event listener to each cancel button
								button.addEventListener('click', function() {
									if (confirm('Cancel this order?')) {
										var orderId = this.getAttribute('data-order_id');
										// Redirect to the cancellation page with the order ID
										window.location.href = 'cancellation.php?order_id=' + orderId;
									}
								});
							});
						});
					</script>

				

				</table>
			</div>
		</div>


	</div>
</div>