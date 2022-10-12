<?php include "../config/core.php";

	// 
	if (!$user_id) header('location: /');

   $type = $_GET['type'];

	// filter user all
	if ($type != 'return') {
		if ($_GET['on'] == 1) $orders_all = db::query("select * from retail_orders where paid = 1 and user_id = '$user_id'");
		elseif ($_GET['off'] == 1) $orders_all = db::query("select * from retail_orders where paid = 1 and user_id = '$user_id'");
		else $orders_all = db::query("select * from retail_orders where paid = 1 and user_id = '$user_id'");
		$page_result = mysqli_num_rows($orders_all);
	} else {
		if ($_GET['on'] == 1) $orders_all = db::query("select * from retail_returns where returns = 1 and user_id = '$user_id'");
		elseif ($_GET['off'] == 1) $orders_all = db::query("select * from retail_returns where returns = 1 and user_id = '$user_id'");
		else $orders_all = db::query("select * from retail_returns where returns = 1 and user_id = '$user_id'");
		$page_result = mysqli_num_rows($orders_all);
	}

	// page number
	$page = 1; if ($_GET['page'] && is_int(intval($_GET['page']))) $page = $_GET['page'];
	$page_age = 10;
	$page_all = ceil($page_result / $page_age);
	if ($page > $page_all) $page = $page_all;
	$page_start = ($page - 1) * $page_age;
	$number = $page_start;

	// filter cours
	if ($type != 'return') {
		if ($_GET['on'] == 1) $orders = db::query("select * from retail_orders where paid = 1 and user_id = '$user_id' order by ins_dt desc limit $page_start, $page_age");
		elseif ($_GET['off'] == 1) $orders = db::query("select * from retail_orders where paid = 1 and user_id = '$user_id' order by ins_dt desc limit $page_start, $page_age");
		else $orders = db::query("select * from retail_orders where paid = 1 and user_id = '$user_id' order by ins_dt desc limit $page_start, $page_age");
	} else {
		if ($_GET['on'] == 1) $orders = db::query("select * from retail_returns where returns = 1 and user_id = '$user_id' order by ins_dt desc limit $page_start, $page_age");
		elseif ($_GET['off'] == 1) $orders = db::query("select * from retail_returns where returns = 1 and user_id = '$user_id' order by ins_dt desc limit $page_start, $page_age");
		else $orders = db::query("select * from retail_returns where returns = 1 and user_id = '$user_id' order by ins_dt desc limit $page_start, $page_age");
	}


	// site setting
	$menu_name = 'orders';
	$pod_menu_name = 'main';
	$css = ['orders'];
	$js = ['orders'];
?>
<? include "../block/header.php"; ?>

	<div class="">
		<div class="bl_c">
			
			<div class="">
				<div class="btn_sel btn_sel2">
					<a class="<?=($type!='return'?'btn_sel_act':'')?>" href="?type=main" >Продажи</a>
					<a class="<?=($type=='return'?'btn_sel_act':'')?>" href="?type=return">Возврат</a>
				</div>
			</div>
			<br>

			<!--  -->

			<div class="uc_u">
				<div class="uc_us">
					<div class="form_im uc_usn">
						<input type="text" placeholder="Поиск" class="sub_user_search_in">
						<i class="fal fa-search form_icon"></i>
					</div>
				</div>
				<div class="uc_uh">
					<div class="uc_uh2">
						<div class="uc_uh_number">#</div>
						<? if ($type != 'return'): ?> <div class="uc_uh_other">Номер продажи</div>
						<? else: ?> <div class="uc_uh_other">Номер возврата</div> <? endif ?>
						<div class="uc_uh_other">Время</div>
						<div class="uc_uh_other">Сумма</div>
						<div class="uc_uh_other">Количество</div>
						<div class="uc_uh_name">Продавец</div>
					</div>
					<div class="uc_uh_cn"></div>
				</div>
				<div class="uc_uc">
					<? if (mysqli_num_rows($orders) != 0): ?>
						<? while ($buy_d = mysqli_fetch_assoc($orders)): ?>
							<? $number++; ?>

							<div class="uc_ui">
								<a class="uc_uil" href="list.php?id=<?=$buy_d['id'].($type=='return'?'&type=return':'')?>">
									<div class="uc_ui_number"><?=$number?></div>
									<div class="uc_uin_other"><?=$buy_d['id']?></div>
									<div class="uc_uin_date2">
										<div class="uc_uin_date2_d"><?=date('d-m-y', strtotime($buy_d['upd_dt']))?></div>
										<div class="uc_uin_date2_t"><?=date('h:i:s', strtotime($buy_d['upd_dt']))?></div>
									</div>
									<div class="uc_uin_other fr_price"><?=$buy_d['total']?></div>
									<div class="uc_uin_other fr_number3"><?=$buy_d['quantity']?></div>
									<div class="uc_uiln">
										<div class="uc_ui_icon lazy_img" data-src="https://lighterior.kz/assets/uploads/users/<?=$user['img']?>">
											<?=($user['img']!=null?'':'<i class="fal fa-user"></i>')?>
										</div>
										<div class="uc_uinu">
											<div class="uc_ui_name"><?=$user['name']?> <?=$user['surname']?></div>
											<div class="uc_ui_phone"><?=fun::user_staff_name($user_right['staff_id'])?></div>
										</div>
									</div>
								</a>
                        <? if ($type != 'return' && $buy_d['returned'] != 1): ?> <div class="uc_uin_cn btn_return_sn" data-id="<?=$buy_d['id']?>"><i class="fal fa-undo-alt"></i></div> <? endif ?>
                        <? if ($buy_d['returned'] == 1): ?> <div class=""></div> <? endif ?>
								<!-- <div class="uc_uib">
									<div class="uc_uibo"><i class="fal fa-ellipsis-v"></i></div>
									<div class="menu_c uc_uibs">
										<a class="menu_ci " href="#">
											<div class="menu_cin"><i class="fal fa-external-link"></i></div>
											<div class="menu_cih">Открыть</div>
										</a>
										<div class="menu_ci " data-id="<?=$buy_d['id']?>">
											<div class="menu_cin"><i class="fal fa-undo-alt"></i></div>
											<div class="menu_cih">Возврат</div>
										</div>
										<div class="menu_ci uc_uib_del " data-id="<?=$buy_d['id']?>">
											<div class="menu_cin"><i class="fal fa-trash-alt"></i></div>
											<div class="menu_cih">Удалить</div>
										</div>
									</div>
								</div> -->
							</div>
						<? endwhile ?>
					
					<? else: ?>
						<div class="ds_nr"><i class="fal fa-ghost"></i><p>НЕТ</p></div>
					<? endif ?>

				</div>
			</div>

			<? if ($page_all > 1): ?>
				<div class="uc_p">
					<? if ($page > 1): ?> <a class="uc_pi" href="?<?=($type=='return'?'type=return&':'')?>page=<?=$page-1?>"><i class="fal fa-angle-left"></i></a> <? endif ?>
					<a class="uc_pi <?=($page==1?'uc_pi_act':'')?>" href="?<?=($type=='return'?'type=return&':'')?>page=1">1</a>
					<? for ($pg = 2; $pg < $page_all; $pg++): ?>
						<? if ($pg == $page - 1): ?>
							<? if ($page - 1 != 2): ?> <div class="uc_pi uc_pi_disp">...</div> <? endif ?>
							<a class="uc_pi <?=($page==$pg?'uc_pi_act':'')?>" href="?<?=($type=='return'?'type=return&':'')?>page=<?=$pg?>"><?=$pg?></a>
						<? endif ?>
						<? if ($pg == $page): ?> <a class="uc_pi <?=($page==$pg?'uc_pi_act':'')?>" href="?page=<?=$pg?>"><?=$pg?></a> <? endif ?>
						<? if ($pg == $page + 1): ?>
							<a class="uc_pi <?=($page==$pg?'uc_pi_act':'')?>" href="?<?=($type=='return'?'type=return&':'')?>page=<?=$pg?>"><?=$pg?></a>
							<? if ($page + 1 != $page_all - 1): ?> <div class="uc_pi uc_pi_disp">...</div> <? endif ?>
						<? endif ?>
					<? endfor ?>
					<a class="uc_pi <?=($page==$page_all?'uc_pi_act':'')?>" href="?<?=($type=='return'?'type=return&':'')?>page=<?=$page_all?>"><?=$page_all?></a>
					<? if ($page < $page_all): ?> <a class="uc_pi" href="?<?=($type=='return'?'type=return&':'')?>page=<?=$page+1?>"><i class="fal fa-angle-right"></i></a> <? endif ?>
				</div>
			<? endif ?>

		</div>
	</div>

<? include "../block/footer.php"; ?>