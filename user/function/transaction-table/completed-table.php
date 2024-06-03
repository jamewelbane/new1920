<table id="completedTable" class="table dataTable">
                                    <thead>
                                        <tr>

                                            <th>Transaction #</th>
                                            <th>To pay</th>
                                            <th>Date Ordered</th>
                                            <th>Status</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch orders
                                        $queryComplete = "SELECT * FROM orders WHERE order_status = 'Completed' AND userid = ?";
                                        $stmtComplete = $link->prepare($queryComplete);
                                        $stmtComplete->bind_param("i", $verifiedUID);
                                        $stmtComplete->execute();
                                        $resultComplete = $stmtComplete->get_result();

                                        while ($row = $resultComplete->fetch_assoc()) {
                                            $status = $row['order_status'];
                                            $order_id = $row['order_id'];
                                            $transaction_number = $row['transaction_number'];
                                            $createdAt = date('d M Y', strtotime($row['created_at']));

                                            if ($status === 'Completed') {
                                                $status = '<label class="badge badge-success">Completed</label>';
                                            }

                                            echo "<tr>
                                                <td>{$row['transaction_number']}</td>
                                                <td style='color: green;'>₱" . number_format($row['total_amount'], 2, '.', ',') . "</td>
                                                <td>{$createdAt}</td>
                                                <td>{$status}</td>
                                                
                                            </tr>";
                                        }

                                        $stmtComplete->close();
                                  
                                        ?>

                                    </tbody>
                                </table>