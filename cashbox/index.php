<?php include "../config/core.php";

	// 
	if (!$user_id) header('location: /');

	// 
	$cashbox = db::query("select * from retail_orders where user_id = '$user_id' and paid = 0 order by id desc limit 1");
	if (mysqli_num_rows($cashbox)) {
		$cashbox_d = mysqli_fetch_assoc($cashbox);
		$cashbox_id = $cashbox_d['id'];
	} else {
		$cashbox_id = (mysqli_fetch_assoc(db::query("SELECT * FROM `retail_orders` order by id desc")))['id'] + 1;
		$ins = db::query("INSERT INTO `retail_orders`(`id`, `user_id`) VALUES ('$cashbox_id', '$user_id')");
	}
	$cashboxp = db::query("select * from retail_orders_products where order_id = '$cashbox_id' order by ins_dt desc");
	$number = 0; $total = 0;


	// site setting
	$menu_name = 'cashbox';
	$site_set['swiper'] = true;
	$css = ['cashbox'];
	$js = ['cashbox'];
?>
<? include "../block/header.php"; ?>

	<div class="bl_c">

		<div class="cash_bl1">

			<div class="cash_bl1_l">
				<div class="uc_us">
					<div class="form_im uc_usn">
						<input type="text" class="form_txt sub_user_search_in cashbox_search" data-oid="<?=$cashbox_id?>" placeholder="Поиск" autofocus>
						<i class="fal fa-search form_icon"></i>
					</div>
					<div class="uc_usb">
						<div class="btn btn_cm">Каталог</div>
						<div class="btn btn_cm product_add_pop">Добавить товар</div>
						<div class="btn_sel">
							<a class="<?=($view_pr?'btn_sel_act':'')?>" href="?view_pr=list" ><i class="far fa-list-ol"></i></a>
							<a class="<?=($view_pr?'':'btn_sel_act')?>" href="?view_pr=0"><i class="far fa-th-list"></i></a>
						</div>
					</div>
					<div class="uc_use">
						<div class="btn btn_dd_cm"><i class="fal fa-bars"></i></div>
						<div class="btn btn_dd_cm product_add_pop"><i class="fal fa-plus"></i></div>
					</div>
				</div>
				<div class="cash_bl1_la"></div>
				<div class="cash_bl1_lsr">
					<div class="uc_u">
						<div class="tb_con_head">
							<div class="tb_con_n">#</div>
							<div class="tb_con_icon"></div>
							<div class="tb_con_name">Наименование</div>
							<div class="tb_con_other">Артикул</div>
							<div class="tb_con_other">Цвет</div>
							<div class="tb_con_other">Размер</div>
							<div class="tb_con_other">Цена</div>
							<div class="tb_con_other">Количество</div>
						</div>
						<div class="uc_uc lazy_c"></div>
					</div>
				</div>
				<div class="uc_uh">
					<div class="uc_uh2">
						<div class="uc_uh_number">#</div>
						<div class="uc_uh_name">Наименование</div>
						<div class="uc_uh_other">Цена</div>
						<div class="uc_uh_other">Количество</div>
						<div class="uc_uh_other">Сумма</div>
					</div>
					<div class="uc_uh_cn"></div>
				</div>
			</div>

			<div class="cash_bl1_r">
				
				<div class="cash_bl1_rc">
					<div class="uc_u <?=($view_pr?'uc_u2':'')?>">
						<div class="uc_uc lazy_c">
							<? if (mysqli_num_rows($cashboxp) != 0): ?>
								<? while ($sel_d = mysqli_fetch_assoc($cashboxp)): ?>
									<? $number++; $sum = $sel_d['quantity'] * $sel_d['price']; $total = $total + $sum; ?>
									<? $product_d = product::product($sel_d['product_id']); ?>
									<? $pitem_d = product::pr_item($sel_d['product_item_id']); ?>
									<? $quantity = product::pr_item_quantity($pitem_d['id']); ?>

									<div class="uc_ui uc_ui2" data-id="<?=$sel_d['id']?>" data-item-id="<?=$pitem_d['id']?>" data-pr="<?=$sel_d['price']?>" data-qn="<?=$sel_d['quantity']?>" data-sum="<?=$sum?>">
										<div class="uc_uil">
											<div class="uc_ui_number"><?=$number?></div>
											<div class="uc_uiln">
												<div class="uc_ui_img lazy_img" data-src="https://lighterior.kz/assets/uploads/products/<?=$pitem_d['img']?>">
													<?=($pitem_d['img']!=null?'':'<i class="fal fa-box"></i>')?>
												</div>
												<div class="uc_uinu">
													<div class="uc_ui_name"><?=$product_d['name_ru']?></div>
													<? if ($pitem_d['article'] || $pitem_d['barcode']): ?>
														<div class="uc_ui_cont">
															<? if ($pitem_d['article']): ?> <div><?=$pitem_d['article']?></div> <? endif ?>
															<? if ($pitem_d['barcode']): ?> <div><?=$pitem_d['barcode']?></div> <? endif ?>
														</div>
													<? endif ?>
													<? if ($pitem_d['color_id'] || $pitem_d['size_id']): ?>
														<div class="uc_ui_cont">
															<? if ($pitem_d['color_id']): ?> <div><?=(product::pr_color($pitem_d['color_id']))['name_ru']?></div> <? endif ?>
															<? if ($pitem_d['size_id']): ?> <div><?=(product::pr_size($pitem_d['size_id']))['name']?></div> <? endif ?>
														</div>
													<? endif ?>
												</div>
											</div>
											<div class="uc_uin_other">
												<input type="tel" class="uc_uin_calc_q fr_price cashbox_pr" value="<?=$sel_d['price']?>" data-lenght="1" />
											</div>
											<div class="uc_uin_other" data-max="<?=$quantity?>">
												<input type="tel" class="uc_uin_calc_q fr_number3 cashbox_qn" value="<?=$sel_d['quantity']?>" data-lenght="1" />
											</div>
											<div class="uc_uin_other cashbox_sum fr_price"><?=$sum?></div>
										</div>
										<div class="uc_uin_cn cashbox_remove" data-id="<?=$sel_d['id']?>"><i class="fal fa-trash-alt"></i></div>
									</div>
								<? endwhile ?>
							<? else: ?> 
								<div class="ds_nr"><p>Пустой список</p></div>
							<? endif ?>
						</div>
					</div>

				</div>
			</div>
			
			<div class="cash_bl1_rb <?=($total==0?'dsp_n':'')?>">
				<div class="cash_bl1_rbl">
					<div class="cash_bl1_rblin">Итого:</div>
					<div class="cash_bl1_rblip cashbox_total fr_price" data-total="<?=$total?>"><?=$total?></div>
				</div>
				<div class="cash_bl1_rbr">
					<div class="btn cashbox_pay" data-id="<?=$cashbox_id?>">Оплата</div>
				</div>
			</div>

		</div>
	</div>

<? include "../block/footer.php"; ?>

	<!--  -->
	<div class="pop_bl pop_bl2 cashbox_pay_block">
		<div class="pop_bl_a cashbox_pay_back"></div>
		<div class="pop_bl_c">
			<div class="head_c">
				<h4>Оплата</h4>
				<div class="btn btn_dd cashbox_pay_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl">
				<div class="form_c">
					<div class="cashbox_pay_btotol">
						<div class="form_span">Общая сумма:</div>
						<div class="cashbox_pay_btotol_c fr_price" data-val="<?=$total?>"><?=$total?></div>
					</div>
					<div class="form_im">
						<div class="form_span">Тип оплаты:</div>
						<div class="form_im_slo payment_method" data-type-name="Kaspi QR">
							<div class="form_im_slo_i form_im_slo_act" data-type="qr">Kaspi QR</div>
							<div class="form_im_slo_i" data-type="transfer">Перевод</div>
							<div class="form_im_slo_i" data-type="cash">Наличные</div>
							<div class="form_im_slo_i" data-type="card">Банковская карта</div>
							<div class="form_im_slo_i" data-type="mixed">Смешанный</div>
						</div>
					</div>
					<div class="cashbox_pay_btype">
						<div class="form_im btype_qr">
							<div class="form_span">Kaspi QR:</div>
							<input type="tel" class="form_txt fr_price " disabled placeholder="0" value="<?=$total?>">
							<i class="fal fa-tenge form_icon"></i>
						</div>
						<div class="form_im btype_transfer dsp_n ">
							<div class="form_span">Перевод:</div>
							<input type="tel" class="form_txt fr_price " placeholder="0">
							<i class="fal fa-tenge form_icon"></i>
						</div>
						<div class="form_im btype_cash dsp_n ">
							<div class="form_span">Наличные:</div>
							<input type="tel" class="form_txt fr_price " placeholder="0">
							<i class="fal fa-tenge form_icon"></i>
						</div>
						<div class="form_im btype_card dsp_n ">
							<div class="form_span">Банковская карта:</div>
							<input type="tel" class="form_txt fr_price " placeholder="0">
							<i class="fal fa-tenge form_icon"></i>
						</div>
					</div>
					
					<div class="cashbox_pay_bsem dsp_n">
						<div class="form_im">
							<div class="form_span">Итог:</div>
							<div class="cashbox_pay_bsemc cashbox_pay_bsemt fr_price"><?=$total?></div>
						</div>
						<div class="form_im">
							<div class="form_span">Сдача:</div>
							<div class="cashbox_pay_bsemc cashbox_pay_bsems fr_price">0</div>
						</div>
					</div>

					<div class="form_im">
						<div class="btn cashbox_pay2" data-id="<?=$cashbox_id?>">Продать</div>
						<div class="btn btn_cl cashbox_pay2" data-id="<?=$cashbox_id?>" data-type="check">Продать и показать чек</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!--  -->
	<div class="pop_bl pop_bl2 product_add_block">
		<div class="pop_bl_a product_add_back"></div>
		<div class="pop_bl_c">
			<div class="head_c">
				<h4>Добавить товар</h4>
				<div class="btn btn_dd product_add_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl">
				<div class="form_c">
					<!-- <div class="form_head">Основные:</div> -->
					<div class="form_im">
						<div class="form_span">Артикул товара:</div>
						<input type="text" class="form_txt pr_article" placeholder="Введите артикул" data-lenght="3">
						<i class="fal fa-barcode form_icon"></i>
					</div>
					<div class="form_im">
						<div class="form_span">Штрих-код:</div>
						<input type="tel" class="form_txt pr_barcode" placeholder="Сканируйте код" data-lenght="8">
						<i class="fal fa-barcode form_icon"></i>
					</div>
					<!-- <div class="form_im form_sel">
						<div class="form_span">Склад:</div>
						<i class="fal fa-warehouse-alt form_icon"></i>
						<div class="form_txt sel_clc pr_warehouses" data-val="5">Точка продажа</div>
						<i class="fal fa-caret-down form_icon_sel"></i>
						<div class="form_im_sel sel_clc_i">
							<? $warehouses = db::query("select * from product_warehouses"); ?>
							<? while ($warehouses_d = mysqli_fetch_assoc($warehouses)): ?>
								<div class="form_im_seli" data-val="<?=$warehouses_d['id']?>"><?=$warehouses_d['name']?></div>
							<? endwhile ?>
						</div>
					</div> -->
					<div class="form_im">
						<div class="form_span">Количество:</div>
						<input type="tel" class="form_txt fr_number3 pr_quantity" placeholder="0" data-lenght="1">
						<i class="fal fa-hashtag form_icon"></i>
					</div>
					<div class="form_im">
						<div class="form_span">Цена продажи:</div>
						<input type="tel" class="form_txt fr_price pr_price" placeholder="0" data-lenght="1">
						<i class="fal fa-tenge form_icon"></i>
					</div>
					<!-- <div class="form_im form_im_toggle">
						<div class="form_span">Доп. цены:</div>
						<input type="checkbox" class="info_inp" data-val="" />
						<div class="form_im_toggle_btn price1_clc"></div>
					</div>
					<div class="price1_bl">
						<div class="form_head">Цена товара:</div> //
						<div class="form_im">
							<div class="form_span">Закупочная цена:</div>
							<input type="tel" class="form_txt fr_price pr_purchase_price" placeholder="0" data-lenght="1">
							<i class="fal fa-tenge form_icon"></i>
						</div>
						<div class="form_im">
							<div class="form_span">Оптовая цена:</div>
							<input type="tel" class="form_txt fr_price pr_discount_price" placeholder="0" data-lenght="1">
							<i class="fal fa-tenge form_icon"></i>
						</div>
					</div> -->

					<div class="form_im form_im_toggle">
						<div class="form_span">Доп. инфо:</div>
						<input type="checkbox" class="info_inp" data-val="" />
						<div class="form_im_toggle_btn price1_clc"></div>
					</div>
					<div class="price1_bl">
						<!-- <div class="form_head">Дополнительные:</div> -->
						<div class="form_im">
							<div class="form_span">Наименование товара:</div>
							<input type="text" class="form_txt pr_name" placeholder="Введите наименование" data-lenght="1">
							<i class="fal fa-text form_icon"></i>
						</div>
						<!-- <div class="form_im">
							<div class="form_span">Бренд:</div>
							<input type="text" class="form_txt pr_brand" placeholder="Введите бренда" data-lenght="1">
							<i class="fal fa-text form_icon"></i>
						</div> -->
						<!-- <div class="form_im form_sel">
							<div class="form_span">Категория товара:</div>
							<i class="fal fa-inventory form_icon"></i>
							<div class="form_txt sel_clc pr_catalog" data-val="">Выберите категорию</div>
							<i class="fal fa-caret-down form_icon_sel"></i>
							<div class="form_im_sel sel_clc_i">
								<? $catalog = db::query("select * from product_catalog"); ?>
								<? while ($catalog_d = mysqli_fetch_assoc($catalog)): ?>
									<div class="form_im_seli" data-val="<?=$catalog_d['id']?>"><?=$catalog_d['name_ru']?></div>
								<? endwhile ?>
							</div>
						</div> -->
						<!-- <div class="form_im form_sel">
							<div class="form_span">Цвет товара:</div>
							<i class="fal fa-palette form_icon"></i>
							<input type="text" class="form_txt pr_color" placeholder="Введите цвет" data-txt="Введите цвет" data-lenght="2">
						</div>
						<div class="form_im form_sel">
							<div class="form_span">Размер товара:</div>
							<i class="fal fa-ruler form_icon"></i>
							<input type="text" class="form_txt pr_size" placeholder="Введите размер" data-txt="Введите размер" data-lenght="2">
						</div> -->
					</div>
				
					<!-- <div class="form_c">
						<div class="form_head">Добавить изображение товара:</div>
						<div class="form_im">
							<input type="file" class="file dsp_n product_img pr_img" accept=".png, .jpeg, .jpg">
							<div class="form_im_img lazy_img pr_img_add" data-txt="Обновить изображение">Выберите с устройства</div>
						</div>
					</div> -->

					<div class="form_im">
						<div class="btn product_add" data-oid="<?=$cashbox_id?>"><span>Добавить</span></div>
					</div>
				</div>

			</div>
		</div>
	</div>