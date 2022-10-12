<? include "config/core.php";

   // 
   if ($user_id) header('location: /cashbox');


	// site setting
	$menu_name = 'main';
	$site_set['menu'] = false;
	$css = ['sign'];
	// $js = [''];
?>
<? include "block/header.php"; ?>

	<div class="">

      <div class="sbl2">
         <div class="sign">
            <div class="bl_c">
               <div class="usign_c">

                  <div class="usign_head"><h3 class="usign_h">Касса</h3></div>
                  <div class="usign_cn">
                     <!-- <div class="form_im form_im_ph">
                        <input type="tel" class="form_txt fr_phone phone" placeholder="8 (___) ___-__-__" data-lenght="11" data-sel="0" />
                        <i class="far fa-mobile form_icon"></i>
                     </div> -->
                  
                     <div class="form_im form_sel">
                        <i class="fal fa-user form_icon"></i>
                        <div class="form_txt sel_clc user_id" data-val="">Выберите кассира</div>
                        <i class="fal fa-caret-down form_icon_sel"></i>
                        <div class="form_im_sel sel_clc_i">
                           <? $user_mn = db::query("select * from user_management where staff_id in (3, 4, 5)"); ?>
                           <? while ($user_mnd = mysqli_fetch_assoc($user_mn)): ?>
                              <? $user_ds = fun::user($user_mnd['user_id']); ?>
                              <div class="form_im_seli" data-val="<?=$user_ds['id']?>"><?=$user_ds['name']?> <?=$user_ds['surname']?></div>
                           <? endwhile ?>
                        </div>
                     </div>

                     <div class="form_im form_im_ps">
                        <input type="password" class="form_txt password" placeholder="Пароль" data-lenght="6" data-sel="0" data-eye="0" />
                        <i class="far fa-lock form_icon"></i>
                        <i class="far fa-eye-slash form_icon_pass"></i>
                     </div>
                     
                     <div class="form_im">
                        <button class="btn btn_sign">Вход</button>
                     </div>
                  
                  </div>

               </div>
            </div>
         </div>
      </div>

	</div>

<? include "block/footer.php"; ?>