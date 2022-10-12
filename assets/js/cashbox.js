// start jquery
$(document).ready(function() {


   // 
   var cash_bl1_lq = new Swiper(".cash_bl1_lq", {
      slidesPerView: 'auto',
      spaceBetween: 20,
   });


	// cashbox_add
	$('html').on('click', '.cashbox_add', function () {
      btn = $(this)
      $.ajax({
         url: "/cashbox/get.php?cashbox_add",
         type: "POST",
         dataType: "html",
         data: ({
            id: btn.data('id'),
            item_id: btn.data('item-id'),
            oid: btn.data('oid'),
         }),
         success: function(data){ 
            if (data == 'yes') location.reload();
            else if (data == 0) mess('Товар уже не осталось');
            else console.log(data);
            console.log(data);
         },
         beforeSend: function(){ },
         error: function(data){ }
      })
	})


   // cashbox_search
   $('.cashbox_search').on('input', function() {
      if ($('.cashbox_search').val() == '') {
         $('.cash_bl1_l').removeClass('cash_bl1_act')
         $('.cash_bl1_lm').removeClass('dsp_n')
      } else {
         $('.cash_bl1_lm').addClass('dsp_n')
         $('.cash_bl1_l').addClass('cash_bl1_act') 
         $.ajax({
            url: "/cashbox/get.php?cashbox_search",
            type: "POST",
            dataType: "html",
            data: ({
               inp: $('.cashbox_search').val(),
               oid: $('.cashbox_search').data('oid'),
            }),
            success: function(data){ 
               if (data == 'yes') location.reload();
               else if (data == 0) mess('Товар уже не осталось');
               else console.log(data);
            },
            beforeSend: function(){ },
            error: function(data){ }
         })
         $.ajax({
            url: "/cashbox/search.php?product_search",
            type: "POST",
            dataType: "html",
            data: ({ 
               result: $('.cashbox_search').val(),
               oid: $('.cashbox_search').data('oid'),
            }),
            success: function(data){
               $('.cash_bl1_lsr .uc_uc').html(data)
               $('.lazy_img').lazy({effect:"fadeIn", effectTime:300, threshold:0})
               console.log(data)
            },
            beforeSend: function(){ },
            error: function(data){ }
         })
      }
   })

	// cashbox_remove
	$('.cashbox_remove').on('click', function () {
		btn = $(this)
      $.ajax({
         url: "/cashbox/get.php?cashbox_remove",
         type: "POST",
         dataType: "html",
         data: ({ id: btn.data('id'), }),
         success: function(data){ 
            if (data == 'yes') location.reload();
            else console.log(data);
         },
         beforeSend: function(){ },
         error: function(data){ }
      })
	})




   // cashbox_pr
	$('html').on('input', '.cashbox_pr', function () {
		btn = $(this)
      sum = btn.attr('data-val') * btn.parents('.uc_ui').attr('data-qn')
      btn.parent().siblings('.cashbox_sum').html(sum + ' тг')

      $.ajax({
         url: "/cashbox/get.php?cashbox_pr",
         type: "POST",
         dataType: "html",
         data: ({
            id: btn.parents('.uc_ui').data('id'),
            pr: btn.attr('data-val'),
         }),
         success: function(data){
            
            total = $('.cashbox_total').attr('data-total') - btn.parents('.uc_ui').attr('data-sum') + sum
            $('.cashbox_total').html(total + ' тг')

            btn.parents('.uc_ui').attr('data-pr', btn.attr('data-val'))
            btn.parents('.uc_ui').attr('data-sum', sum)
            $('.cashbox_total').attr('data-total', total)
            $('.cash_bl1_rb').removeClass('dsp_n')

            console.log(data);
         },
         beforeSend: function(){ },
         error: function(data){ }
      })
	})


	// cashbox_qn
	$('html').on('input', '.cashbox_qn', function () {
		btn = $(this)
      qn = btn.attr('data-val')
      qn_max = btn.parent('.uc_uin_other').data('max')
      if (qn_max < qn) {
         mess('Вы можете ввести не более ' + qn_max + ' шт.');
         qn = qn_max; btn.attr('data-val', qn_max); btn.val(qn_max + ' шт');
      }

      $.ajax({
         url: "/cashbox/get.php?cashbox_qn",
         type: "POST",
         dataType: "html",
         data: ({ 
            id: btn.parents('.uc_ui').data('id'),
            item_id: btn.parents('.uc_ui').data('item-id'),
            qn: qn,
         }),
         success: function(data){ 
            if (data == 'yes') {
               sum = qn * btn.parents('.uc_ui').attr('data-pr')
               btn.parent().siblings('.cashbox_sum').html(sum + ' тг')

               total = $('.cashbox_total').attr('data-total') - btn.parents('.uc_ui').attr('data-sum') + sum
               $('.cashbox_total').html(total + ' тг')
   
               btn.parents('.uc_ui').attr('data-qn', qn)
               btn.parents('.uc_ui').attr('data-sum', sum)
               $('.cashbox_total').attr('data-total', total)
               $('.cash_bl1_rb').removeClass('dsp_n')
            }
            if (data == 0) mess('Товар уже не осталось');
            else console.log(data);
            // if (data == 'yes') location.reload();
         },
         beforeSend: function(){ },
         error: function(data){ }
      })

	})







   
	$('.form_im_slo_i').click(function(){
		$('.form_im_slo_i').removeClass('form_im_slo_act')
		$(this).addClass('form_im_slo_act')
      $('.form_im_slo').attr('data-type-name', $(this).html())
		$(this).addClass('form_im_slo_act')

      total = $('.cashbox_pay_btotol_c').attr('data-val')

      $('.cashbox_pay_btype .form_im').addClass('dsp_n')
      $('.cashbox_pay_bsem').addClass('dsp_n')
      $('.cashbox_pay_btype .form_im input').attr('disabled', '')
      $('.cashbox_pay_btype .btype_cash input').removeAttr('disabled')
      $('.cashbox_pay_btype .form_im input').val('')
      $('.cashbox_pay_btype .form_im input').attr('data-val', '')

      $('.cashbox_pay_bsemt').html(total + ' тг')
      $('.cashbox_pay_bsems').html(0 + ' тг')

      if ($(this).attr('data-type') == 'qr') {
         $('.cashbox_pay_btype .btype_qr').removeClass('dsp_n')
         $('.cashbox_pay_btype .btype_qr input').val(total + ' тг')
         $('.cashbox_pay_btype .btype_qr input').attr('data-val', total)
      } else if ($(this).attr('data-type') == 'transfer') {
         $('.cashbox_pay_btype .btype_transfer').removeClass('dsp_n')
         $('.cashbox_pay_btype .btype_transfer input').val(total + ' тг')
         $('.cashbox_pay_btype .btype_transfer input').attr('data-val', total)
      } else if ($(this).attr('data-type') == 'cash') {
         $('.cashbox_pay_btype .btype_cash').removeClass('dsp_n')
         $('.cashbox_pay_bsem').removeClass('dsp_n')
      } else if ($(this).attr('data-type') == 'card') {
         $('.cashbox_pay_btype .btype_card').removeClass('dsp_n')
         $('.cashbox_pay_btype .btype_card input').val(total + ' тг')
         $('.cashbox_pay_btype .btype_card input').attr('data-val', total)
      } else if ($(this).attr('data-type') == 'mixed') {
         $('.cashbox_pay_btype .form_im').removeClass('dsp_n')
         $('.cashbox_pay_btype .form_im input').removeAttr('disabled')
         $('.cashbox_pay_btype .btype_qr input').val(total + ' тг')
         $('.cashbox_pay_btype .btype_qr input').attr('data-val', total)
         $('.cashbox_pay_bsem').removeClass('dsp_n')
      }
	})

   $('html').on('input', '.cashbox_pay_btype input', function () {
      total = 0;
      $('.cashbox_pay_btype input').each(function () {
         total = total + Number($(this).attr('data-val'))         
      })
      $('.cashbox_pay_bsemt').html(total + ' тг')

      sum = Number($('.cashbox_pay_btotol_c').attr('data-val'))
      change = total - sum
      if (change > 0) $('.cashbox_pay_bsems').html(change + ' тг')
   })


	// cashbox_pay
	$('.cashbox_pay').click(function(){
		$('.cashbox_pay_block').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.cashbox_pay_back').click(function(){
		$('.cashbox_pay_block').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})

   // cashbox_pay
	$('.cashbox_pay2').on('click', function () {
		btn = $(this)
      $.ajax({
         url: "/cashbox/get.php?cashbox_pay",
         type: "POST",
         dataType: "html",
         data: ({ 
            id: btn.data('id'),
            payment_method: $('.payment_method').attr('data-type-name'),
         }),
         success: function(data){
            if (btn.attr('data-type') == 'check') {
               
            } else {
               if (data == 'yes') location.reload();
               else if (data == 0) mess('Вам необходимо заполнить все поля')
            }
            console.log(data);
         },
         beforeSend: function(){ },
         error: function(data){ }
      })
	})








   // add product
	$('.product_add_pop').click(function(){
		$('.product_add_block').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.product_add_back').click(function(){
		$('.product_add_block').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})
	$('html').on('click', '.price1_clc', function() { $('.price1_bl').toggleClass('price1_bl_act') });
   // $('.pr_img_add').click(function(){ $(this).siblings('.pr_img').click() })

	$('.product_add').on('click', function() {
		if ($('.pr_article').attr('data-sel') != 1) {
			// if ($('.pr_name').attr('data-sel') != 1) mess('Введите свой данный')
			if ($('.pr_article').attr('data-sel') != 1) mess('Введите свой данный')
		} else {
			$.ajax({
				url: "/cashbox/get.php?product_add",
				type: "POST",
				dataType: "html",
				data: ({
               article: $('.pr_article').attr('data-val'),
               barcode: $('.pr_barcode').attr('data-val'),
					quantity: $('.pr_quantity').attr('data-val'),
               price: $('.pr_price').attr('data-val'), 
					oid: $('.product_add').data('oid'),
               name: $('.pr_name').attr('data-val'),
				}),
				success: function(data){
					if (data == 'yes') {
						mess('Успешно')
						setTimeout(function() { location.reload(); }, 500);
					} else mess('Ошибка!'); console.log(data);
				},
				beforeSend: function(){ mess('Отправка..') },
				error: function(data){ }
			})
		}
	})




               
					// warehouses: $('.pr_warehouses').attr('data-val'),
               // catalog: $('.pr_catalog').attr('data-val'),
					// purchase_price: $('.pr_purchase_price').attr('data-val'),
               // discount_price: $('.pr_discount_price').attr('data-val'),
					// img: $('.pr_img').attr('data-val'),
               // brand: $('.pr_brand').attr('data-val'),
					// color: $('.pr_color').attr('data-val'),
               // size: $('.pr_size').attr('data-val'),



}) // end jquery