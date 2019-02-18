<?php 
	if(empty($_SESSION['id'])) header('location:?mod=login');
	
	$id_user=$_SESSION['id'];
	$sql='SELECT * FROM `nn_user` WHERE `id`='.$id_user;
	$rs=mysqli_query($link,$sql);
	$r=mysqli_fetch_assoc($rs);
	
	$sql="SELECT `id`,`create_at`,`name`,`status`,sum(`price`*`qty`) as `total`
	FROM `nn_order` o,`nn_order_detail` od
	WHERE o.id=od.order_id  AND `user_id` =".$id_user." 
	GROUP BY `id`
	ORDER by `id` DESC";
	$rs=mysqli_query($link,$sql);
	
	$status=array(-1=>'Hủy','Mới đặt','đã xác nhận','đang giao','đã giao','hoàn thành');
?>
        	<h4 class="heading colr">Tài Khoản Của Tôi</h4>
            <div class="account">
            	<ul class="acount_navi">
                    <li><a href="" class="selected">Tài Khoản</a></li>
                    <li><a href="#">Lịch Sử</a></li>
                    <li><a href="#">Hồ Sơ</a></li>
                    <li><a href="#">Hổ Trợ</a></li>
                    <li><a href="?mod=cart">Giỏ Hàng</a></li>
                    <li><a href="?mod=logout">Đăng Xuất</a></li>
                </ul>
                <div class="account_title">
                    <h6 class="bold">Xin Chào <?=$r['name']?>!</h6>
                    <p>
                        Từ tài khoản cá nhân của bạn có khả năng kiểm tra thông tin tài khoản và thông tin đơn hàng gân đây bạn đã đặt. Chọn sản phẩm bên dưới để xem thông tin sản phẩm chi tiết.
                    </p>
                </div>
                <div class="clear"></div>
                <div class="acount_info">
                    <h6 class="heading bold colr">Thông Tin Tài Khoản</h6>
                    <div class="big_sec left">
                        <div class="big_small_sec left">
                        	<div class="headng">
                                <h6 class="bold">Thông Tin Liên Hệ</h6>
                                <a href="#" class="right bold">Sửa</a>
                            </div>
                            <p class="bold"><?=$r['name']?></p>
                            <a href="#"><?=$r['email']?></a><br >
                            <a href="?mod=update">Thay Đổi Mật Khẩu</a>
                        </div>
                        <div class="clear"></div>
                        <div class="botm_big">&nbsp;</div>
                    </div>
                    <div class="clear"></div>
                    <div class="big_sec left">
                        <div class="big_small_sec left">
                        	<h6 class="bold"><?=$r['address']?></h6>
                            <p>Bạn không nhận hàng bằng địa chỉ hiện tại.</p>
                            <a href="?mod=update"><u>Thay Đổi Địa Chỉ</u></a>
                        </div>
                        <div class="clear"></div>
                        <div class="botm_big">&nbsp;</div>
                    </div>
                </div>
                <h6 class="heading bold colr">Hàng Đặt Gần Đay</h6>
                <div class="account_table">
                	<ul class="headtable">
                    	<li class="order bold">Đặt Hàng</li>
                        <li class="date bold">Ngày</li>
                        <li class="ship bold">Người Nhận</li>
                        <li class="ordertotal bold">Tổng Tiền</li>
                        <li class="status bold last">Trạng Thái</li>
                        <li class="action bold last">&nbsp;</li>
                    </ul>
                    <?php 
						while($r2=mysqli_fetch_assoc($rs))
						{
					?>
                    <ul class="contable">
                    	<li class="order"><?=$r2['id']?></li>
                        <li class="date"><?=date('d/m/Y H:i',strtotime($r2['create_at']))?></li>
                        <li class="ship"><?=$r2['name']?></li>
                        <li class="ordertotal"><?=number_format($r2['total'])?> VNĐ</li>
                        <li class="status last"><?=$status[$r2['status']]?></li>
                        <li class="action last">
                        <a href="?mod=view&id=<?=$r2['id']?>" class="first">Xem</a>
                        
                        <?php if($r2['status']==0) { ?>
                        <a onlick="return confirm('Bạn đã chắc chắn chưa?')" href="?mod=order_cancel&id=<?=$r2['id']?>">Xóa</a>
                        <?php } ?>
                        </li>
                    </ul>
                    <?php } 
					?>
                </div>
                <div class="view_tags">
                	<div class="viewssec">
                    	<h4 class="heading colr">Recent Views</h4>
                    	<ul>
                        	<li>
                            	<h5 class="bullet">1</h5>
                                <h5 class="title">RECENT VIEWS</h5>
                                <div class="clear"></div>
                                <div class="ratingsblt">
                                	<a href="#"><img src="images/star_green.gif" alt="" ></a>
                                    <a href="#"><img src="images/star_green.gif" alt="" ></a>
                                    <a href="#"><img src="images/star_green.gif" alt="" ></a>
                                    <a href="#"><img src="images/star_green.gif" alt="" ></a>
                                    <a href="#"><img src="images/star_grey.gif" alt="" ></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tagssec">
                    	<h4 class="heading colr">My Recent Tags</h4>
                    	<ul>
                        	<li>
                            	<h5 class="bullet">1</h5>
                                <h5 class="title">Product Name</h5>
                                <div class="clear"></div>
                                <div class="tgs">
                                	<p class="colr tag">Tags: </p>
                                    <a href="#">Leatehr Bags, </a>
                                    <a href="#">Bags, </a>
                                    <a href="#">Laptop Bags</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            