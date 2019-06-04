<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> <?php echo $pageTitle; ?>
      </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Shipments List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <!-- <?php echo anchor('clients/export_client', "Export Client", array('class' => 'btn btn-danger btn-flat')); ?> -->
                        <form class="form-inline" method="POST">
                            <div class="form-group">
                                <label for="sel1">Status:</label>
                                <select class="form-control" id="selected_shipment_status" name="selected_shipment_status" onchange="this.form.submit()">
                                    <option value="">Chọn trạng thái</option>
                                    <?php foreach ($shipments_status as $item) {
                                        echo "<option value='{$item['code']}' " . ($selected_shipment_status == $item['code'] ? 'selected' : '') . ">{$item['name']}</option>";
                                    } ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="shipments-list-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tracking Code</th>
                                        <th>Order Code</th>
                                        <th>Người Nhận</th>
                                        <th>SĐT</th>
                                        <th>Địa chỉ</th>
                                        <!-- <th>Tỉnh</th>  -->
                                        <th>Khối Lượng</th>
                                        <th>Tiền Thu Hộ</th>
                                        <th>Cước E1</th>
                                        <th>Trạng Thái</th>
                                        <th>Bưu Cục</th>
                                        <th>Ghi Chú Đơn</th>
                                        <!-- Webhook info -->                                    
                                        <th>Vị Trí</th>
                                        <th>Ghi Chú Vận Chuyển</th>
                                        <th>Cập Nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                
                                foreach ($shipments_list as $item) { ?>
                                    <tr>
                                        <td><?php echo $item['id'] ?></td>
                                        <td><?php echo $item['code'] ?></td>
                                        <td><?php echo $item['order_code'] ?></td>
                                        <td><?php echo $item['address_name'] ?></td>
                                        <td><?php echo $item['address_phone'] ?></td>
                                        <td><?php echo @$item['address_street'] . @item['address_ward'] . @item['address_district'] . @item['address_province'] ?></td>
                                        <!-- <td><?php echo $item['address_province'] //Tinh ?></td> -->
                                        <td><?php echo $item['total_weight'] ?></td>
                                        <td><?php echo number_format($item['money_collect']) ?> VNĐ</td>
                                        <td><?php echo number_format($item['total_fee']) ?> VNĐ</td>
                                        <td><?php echo $item['status_name'] ?></td>
                                        <td><?php //echo $item['']    //Buu Cuc ?></td>

                                        <td><?php echo $item['description'] ?></td>

                                        <td><?php echo $item['locate'] ?></td>
                                        <td><?php echo $item['note'] ?></td>

                                        <td><?php echo $item['datetime'] //lan cuoi cap nhat qua webhook ?></td>
                                    </tr>
                                <?php } ?>

                                <!-- <tfoot>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>

