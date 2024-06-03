<table id="confirmedTable" class="table dataTable">
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
                                        $queryConfirmed = "SELECT * FROM orders WHERE order_status = 'Confirmed' AND userid = ?";
                                        $stmtConfirmed = $link->prepare($queryConfirmed);
                                        $stmtConfirmed->bind_param("i", $verifiedUID);
                                        $stmtConfirmed->execute();
                                        $resultConfirmed = $stmtConfirmed->get_result();

                                        while ($row = $resultConfirmed->fetch_assoc()) {
                                            $status = $row['order_status'];
                                            $order_id = $row['order_id'];
                                            $transaction_number = $row['transaction_number'];
                                            $createdAt = date('d M Y', strtotime($row['created_at']));

                                            if ($status === 'Confirmed') {
                                                $status = '<label class="badge badge-primary">To ship</label>';
                                            }

                                            echo "<tr>
                                                <td>{$row['transaction_number']}</td>
                                                <td style='color: green;'>₱" . number_format($row['total_amount'], 2, '.', ',') . "</td>
                                                <td>{$createdAt}</td>
                                                <td>{$status}</td>
                                                
                                            </tr>";
                                        }

                                        $stmtConfirmed->close();
                                    
                                        ?>

                                    </tbody>
                                </table>