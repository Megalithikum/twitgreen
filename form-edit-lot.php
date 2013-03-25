<?php

	global $current_user;

	wp_enqueue_script('bootstrap-datepicker', get_stylesheet_directory_uri() . '/js/bootstrap-datepicker.js', array('bootstrap','jquery'),'1.0',true);
	wp_enqueue_script('bootstrap-fileupload', get_stylesheet_directory_uri() . '/js/bootstrap-fileupload.js', array('bootstrap','jquery'),'1.0',true);
	
	wp_enqueue_script('suggest');
	
	//GMap
	wp_enqueue_script('gmap','http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAF4PVqw0p5l92pEmE39k0MRQWxhPw7-SAnMb84NfHs4vQ3HTp4BTb-yeL6fQg7Up9d9idBGy5naXydw', true);
	
	wp_enqueue_script('peta', get_stylesheet_directory_uri() . '/js/peta.js', true);
	
	//OpenLayers
	wp_enqueue_script('openlayers','http://www.openlayers.org/api/OpenLayers.js','2.12',true); //live
	
	wp_enqueue_script('ol', get_stylesheet_directory_uri() . '/js/OL.js', true);
	
	wp_enqueue_script('twitgreen-edit', get_stylesheet_directory_uri() . '/js/edit-script.js', array('suggest','gmap','openlayers','bootstrap-datepicker','bootstrap-fileupload','jquery','twitgreen'),'1.0',true);
	

	// check if editing or create new
	if (is_tax( )) {
		// if in taxonomy page
		$current_view = get_queried_object();
		$lot_data = get_post_object($current_view->term_id,$current_view->taxonomy);
		$lot_meta = get_post_meta( $lot_data->ID );
		$edit = TRUE;
		$back_url = get_term_link($current_view);

	} elseif (is_single()) {
		// if is single
		$lot_data = get_queried_object();
		$lot_meta = get_post_meta( $lot_data->ID );
		$edit = TRUE;
		$back_url = get_permalink( $lot_data->ID );

	}else{
		// if is creating new
		$lot_data = array();
		$lot_meta = array();
		$edit = FALSE;
		$back_url = get_bloginfo( 'url' );

	}

	// get additional data, based on wheter is editing or adding new
	if ($edit) {

		if(isset($lot_meta['farmer'][0]) && $lot_meta['farmer'][0] != ''){
			$farmer_data = get_user_by( 'id', $lot_meta['farmer'][0] );
		}

		if(isset($lot_meta['landlord'][0]) && $lot_meta['landlord'][0] != ''){
			$landlord_data = get_user_by( 'id', $lot_meta['landlord'][0] );
		}

		if(isset($lot_meta['verifikator'][0]) && $lot_meta['verifikator'][0] != ''){
			$verifikator_data = get_user_by( 'id', $lot_meta['verifikator'][0] );
		}

		if(isset($lot_meta['sponsor'][0]) && $lot_meta['sponsor'][0] != ''){
			$sponsor_data = get_user_by( 'id', $lot_meta['sponsor'][0] );
		}




		$current_area = wp_get_post_terms( intval($lot_data->ID), 'area');
		if(isset($current_area[0]->term_id)){
			$area_id=$current_area[0]->term_id;
			$area_name=$current_area[0]->name;
		}else{
			$area_id='';
			$area_name='';	
		}
		$current_block = wp_get_post_terms( $lot_data->ID, 'block-term');
		$current_plants = wp_get_post_terms( $lot_data->ID, 'plants');
		$current_project = wp_get_post_terms( $lot_data->ID, 'project-term');
		$current_sponsorship = wp_get_post_terms( $lot_data->ID, 'sponsorship-term');
		$lot_status = wp_get_post_terms( $lot_data->ID, 'lot-status' );
	}

	// get user edit status capabilty, based on wheter is editing or adding new
	if ($edit) {
		// get user editing capability
		$role = get_role_in_lot($current_user->ID, $lot_data->ID);

		if ( (isset($role['relawan']) && !isset($lot_status[0])) || (isset($role['relawan']) && $lot_status[0]->slug == 'draft')){
			$status_array = array('draft','offering','delete');
		} elseif ( isset($role['relawan']) && $lot_status[0]->slug == 'planting'){
			$status_array = array('planting','planted');
		} elseif ( isset($role['verifikator']) && $lot_status[0]->slug == 'plan'){
			$status_array = array('draft','plan','ready-to-plant');
		} elseif ( isset($role['verifikator']) && $lot_status[0]->slug == 'planted'){
			$status_array = array('draft','planted');
		} elseif (isset($role['administrator'])){
			$status_array = array('draft','offering','plan','ready-to-plant','planting','planted','verified','saving-trees','delete');
		}

	} else {
		// create new
		$status_array = array('draft','offering');

	}

	$current_user_role = get_eco_role($current_user->ID);

	if (in_array('relawan', $current_user_role)) {
		$editable_field = array('identitas','penanaman','relasi','status');
	}
	if (in_array('verifikator', $current_user_role)) {
		$editable_field = array('identitas','penanaman','relasi','verifikasi','status');
	}
	if (in_array('sponsor', $current_user_role)) {
		$editable_field = array('identitas');
	}
	if (in_array('administrator', $current_user_role)) {
		$editable_field = array('identitas','penanaman','relasi','verifikasi','status');
	}
	

?>
<div id="" class="row">
	<div id="" class="span3 hidden-phone">
		<ol class="acc-wizard-sidebar">
			<?php if (in_array('identitas', $editable_field)): ?>
				<li class="acc-wizard-todo"><a href="#data-identitas" data-toggle="collapse" data-parent="#form-lot" >Identitas Lot</a></li>
			<?php endif ?>
			<?php if (in_array('penanaman', $editable_field)): ?>		
				<li class="acc-wizard-todo"><a href="#data-penanaman" data-toggle="collapse" data-parent="#form-lot" >Penanaman Lot</a></li>
			<?php endif ?>
			<?php if (in_array('relasi', $editable_field)): ?>
				<li class="acc-wizard-todo"><a href="#data-relasi" data-toggle="collapse" data-parent="#form-lot" >Relasi Lot</a></li>
			<?php endif ?>
			<?php if (in_array('verifikasi', $editable_field)): ?>
				<li class="acc-wizard-todo"><a href="#data-verifikasi" data-toggle="collapse" data-parent="#form-lot" >Verifikasi Lot</a></li>
			<?php endif ?>
			<?php if (in_array('status', $editable_field)): ?>
			<li class="acc-wizard-todo"><a href="#data-status" data-toggle="collapse" data-parent="#form-lot" >Status Lot</a></li>
			<?php endif ?>
		</ol>
	</div>
	<div id="" class="span9">
		<form class="form form-horizontal accordion" id="form-lot" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('add_edit_lot',$current_user->user_login); ?>
			<input name="action" value="add_edit_lot" type="hidden">
			<?php if ($edit): ?>
				<input name="lot-id" value="<?php echo $lot_data->ID ?>" type="hidden">
				<input name="lot-term" value="<?php echo $lot_meta['lot-term-id'][0] ?>" type="hidden">
			<?php endif ?>
			<?php if (in_array('identitas', $editable_field)): ?>
				<fieldset class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#form-lot" href="#data-identitas">
							Identitas Lot
						</a>
					</div>
					<div id="data-identitas" class="accordion-body collapse in">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="lot-title">Nama Lot</label>
								<div class="controls">
									<input type="text" id="lot-title" name="lot-title" class="span6" value="<?php if($edit){ echo $lot_data->post_title; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputjudul">Foto Lot</label>
								<div class="controls">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-new thumbnail">
											<?php if ($edit && has_post_thumbnail( $lot_data->ID )): ?>
												<?php echo get_the_post_thumbnail( $lot_data->ID, 'medium' ) ?>
											<?php else: ?>
												<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
											<?php endif ?>
										</div>
										<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
										<div>
											<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="profile-picture" name="profile-picture[]" /></span>
											<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
										</div>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="info-lot">Deskripsi Lot</label>
								<div class="controls">
									<textarea name="info-lot" id="info-lot" class="input-xlarge span5" rows="6"><?php if($edit){ echo $lot_data->post_content; } ?></textarea>
								</div> 
							</div>
							<div class="control-group">
								<label class="control-label" for="sponsorship">Sponsorship</label>
								<div class="controls">
									<input type="text" name="sponsorship" id="sponsorship-suggest" class="span4" value="<?php if($edit && $current_sponsorship[0]){ echo $current_sponsorship[0]->name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button id="submit-profile" type="submit" class="btn btn-primary">Simpan</button> <a href="<?php echo $back_url ;?>" class="btn">Batal</a>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			<?php endif ?>
			<?php if (in_array('penanaman', $editable_field)): ?>
				<fieldset class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#form-lot" href="#data-penanaman">
							Penanaman Lot
						</a>
					</div>
					<div id="data-penanaman" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="project">Project</label>
								<div class="controls">
									<input type="text" name="project" id="project-suggest" class="span6" value="<?php if($edit && isset($current_project[0])){ echo $current_project[0]->name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<a href="<?php echo home_url( '/add-project/' ) ?>">Daftarkan Project Baru</a>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="block">Block</label>
								<div class="controls">
									<input type="text" name="block" id="block-suggest" class="span6" value="<?php if($edit && isset($current_block[0])){ echo $current_block[0]->name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<a href="<?php echo home_url( '/add-block/' ) ?>" >Daftarkan Block Baru</a>
								</div>
							</div>
							
							<!-- Peta Lot -->
							<div class="control-group">
								<label class="control-label" for="map">GMap</label>
								<div class="controls">
								<span class="help-map">*Click pada peta untuk membuat titik*</span>
									<div id="Map-div" style='height:356px; width:456px; border:1px solid;'></div>
									<form action="#" name="latlong" onsubmit="findeAlle(address); return false">
									<br><p>Koordinat :</p>               
									<textarea name="koordinat-lot" id="results" class="input-xlarge span5" rows="6"></textarea>
									<br>
									<p><input style="font-size: 12px; font-family: Tahoma, sans-serif;" value="Clear &amp; Reset the Map" onclick="map.clearOverlays();map.setCenter(new GLatLng(-6.72, 109.97), 4);countOther = 1;" type="reset">     
								</p>
								</form>  
								</div>	
							</div>
								
							<div class="control-group">
								<label class="control-label" for="map">OpenLayers</label>
								<div class="controls">
									<div id="map" style='height:356px; width:456px; border:1px solid;'></div>
									<div id="coords"></div>
										<div id="lonlatTG"></div>
										<div id="lonlatTrans"></div><br/>								
								</div>
							</div> 
							
							<div class="control-group">
								<label class="control-label" for="koordinat-lot">Koordinat Lot</label>
								<div class="controls">
									<!--textarea name="koordinat-lot" id="results" class="input-xlarge span5" rows="6"><!--php if($edit && isset($lot_meta['koordinat-lot'][0])){ echo $lot_meta['koordinat-lot'][0]; } ?></textarea-->
									<textarea name="koordinat-lot" id="lonlatDouble" class="input-xlarge span5" rows="6"></textarea>	
								</div>	
							</div>
							
							<div class="control-group">
								<label class="control-label" for="kota">Jenis Tanaman</label>
								<div class="controls">
									<?php
										$plants = get_terms('plants', array('hide_empty' => false));
									?>
									<select name="plant-name">
										<?php
											if ( count( $plants ) ) {
												foreach( $plants as $plant ) {
													if( $edit && isset($current_plants[0]) && $current_plants[0]->slug == $plant->slug){
														$selected = 'selected="selected"';
													} else {
														$selected = '';
													}
													// if ($edit) {
													// 	$selected = selected( $current_plants->term_id, $plant->term_id, FALSE);
													// } else {
														// $selected = '';
													// }
													print '<option value="'.$plant->name.' " '.$selected.'">'.$plant->name.'</option>"';
												}
											}
										?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="area">Kota / Kabupaten</label>
								<div class="controls">
									<input type="text" id="area-suggest" name="area" value="<?php if($edit && isset($current_area[0])){ echo $current_area[0]->name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="kecamatan">Kecamatan</label>
								<div class="controls">
									<input type="text" name="kecamatan" id="kecamatan" placeholder="" value="<?php if($edit && isset($lot_meta['kecamatan'][0])){ echo $lot_meta['kecamatan'][0]; } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="desa">Desa</label>
								<div class="controls">
									<input type="text" name="desa" id="desa" placeholder="" value="<?php if($edit && isset($lot_meta['desa'][0])){ echo $lot_meta['desa'][0]; } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="persil">No Persil / Objek Pajak</label>
								<div class="controls">
									<input type="text" name="persil" id="persil" placeholder="" value="<?php if($edit && isset($lot_meta['persil'][0])){ echo $lot_meta['persil'][0]; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="kerjasama">Kerjasama</label>
								<div class="controls">
									<select id="kerjasama" name="kerjasama">
										<option value="AA-70/30" <?php if( $edit && isset($lot_meta['kerjasama'][0])){selected($lot_meta['kerjasama'][0], 'AA-70/30');} ?>>AA-70/30</option>
										<option value="AB-60/40" <?php if( $edit && isset($lot_meta['kerjasama'][0])){selected($lot_meta['kerjasama'][0], 'AB-60/40');} ?>>AB-60/40</option>
										<option value="BA-60/40" <?php if( $edit && isset($lot_meta['kerjasama'][0])){selected($lot_meta['kerjasama'][0], 'BA-60/40');} ?>>BA-60/40</option>
										<option value="BB-50/50" <?php if( $edit && isset($lot_meta['kerjasama'][0])){selected($lot_meta['kerjasama'][0], 'BB-50/50');} ?>>BB-50/50</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="ho-ktp">Tanggal Penanamen</label>
								<div class="controls">
									<div class="input-append date datepicker" id="dp3" data-date="<?php if($edit && isset($lot_meta['tanggal-tanam'][0])){ echo $lot_meta['tanggal-tanam'][0]; } else { echo date('Y-m-d') ; } ?> " data-date-format="yyyy-mm-dd">
										<input class="span2" size="16" type="text" name="tanggal-tanam" id="tanggal-tanam" value="<?php if($edit && isset($lot_meta['tanggal-tanam'][0])){ echo $lot_meta['tanggal-tanam'][0]; } else { echo date('Y-m-d') ; } ?> ">
										<span class="add-on"><i class="icon-th"></i></span>
									</div>
								</div> 
							</div>
							<div class="control-group">
								<label class="control-label" for="jumlah-pohon">Jumlah Rencana Penanamen Pohon</label>
								<div class="controls">
									<input type="text" name="jumlah-pohon" id="jumlah-pohon" placeholder="" value="<?php if($edit && isset($lot_meta['jumlah-pohon'][0])){ echo $lot_meta['jumlah-pohon'][0]; } ?>"> 
									<span class="help-block">Jumlah pohon yang diperbolehkan adalah 25 pohon sampai 2500 pohon.</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="luas-lot">Luas Lot</label>
								<div class="controls">
									<input type="text" name="luas-lot" id="luas-lot" placeholder="" value="<?php if($edit && isset($lot_meta['luas-lot'][0])){ echo $lot_meta['luas-lot'][0]; } ?>"> 
									<span class="help-block">Luas lot yang diperbolehkan adalah 0.1 hektar sampai 1 hektar.</span>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button id="submit-profile" type="submit" class="btn btn-primary">Simpan</button> <a href="<?php echo $back_url ;?>" class="btn">Batal</a>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			<?php endif ?>
			<?php if (in_array('relasi', $editable_field)): ?>
				<fieldset class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#form-lot" href="#data-relasi">
							Relasi Lot
						</a>
					</div>
					<div id="data-relasi" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="farmer">Petani</label>
								<div class="controls">
									<input type="text" id="farmer" name="farmer" class="user-suggest span6" value="<?php if($edit && isset($farmer_data)){ echo $farmer_data->display_name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="landlord">Pemilik Lahan</label>
								<div class="controls">
									<input type="text" id="landlord" name="landlord" class="user-suggest span6" value="<?php if($edit && isset($landlord_data)){ echo $landlord_data->display_name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="verifikator">Verifikator</label>
								<div class="controls">
									<input type="text" id="verifikator" name="verifikator" class="user-suggest span6 uneditable-input" value="Sony Suwargana" disabled="disabaled"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sponsor">Sponsor</label>
								<div class="controls">
									<input type="text" id="sponsor" name="sponsor" class="user-suggest span6" value="<?php if($edit && isset($sponsor_data)){ echo $sponsor_data->display_name; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<a href="#">Daftarkan User Baru</a>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button id="submit-profile" type="submit" class="btn btn-primary">Simpan</button> <a href="<?php echo $back_url ;?>" class="btn">Batal</a>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			<?php endif ?>
			<?php if (in_array('verifikasi', $editable_field)): ?>
				<fieldset class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#form-lot" href="#data-verifikasi">
							Verifikasi Lot
						</a>
					</div>
					<div id="data-verifikasi" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="inputjudul">Form A Verifikasi</label>
								<div class="controls">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
											<?php
												if($edit && isset($lot_meta['form-a-verifikasi-picture'][0])){
													echo wp_get_attachment_image( $lot_meta['form-a-verifikasi-picture'][0], 'thumbnail' );
												} else {
													echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
												}
											?>
										</div>
										<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
										<div>
											<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="form-a-verifikasi-picture" name="form-a-verifikasi-picture[]" /></span>
											<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
										</div>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputjudul">Berita Acara Penanaman Verifikasi</label>
								<div class="controls">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
											<?php
												if($edit && isset($lot_meta['bap-verifikasi-picture'][0])){
													echo wp_get_attachment_image( $lot_meta['bap-verifikasi-picture'][0], 'thumbnail' );
												} else {
													echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
												}
											?>
										</div>
										<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
										<div>
											<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="bap-verifikasi-picture" name="bap-verifikasi-picture[]" /></span>
											<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
										</div>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="koordinat-lot-verifikasi">Koordinat Lot Verifikasi</label>
								<div class="controls">
									<textarea name="koordinat-lot-verifikasi" id="koordinat-lot-verifikasi" class="input-xlarge span5" rows="6"><?php if($edit && isset($lot_meta['koordinat-lot-verifikasi'][0])){ echo $lot_meta['koordinat-lot-verifikasi'][0]; } ?></textarea>
								</div> 
							</div>
							<div class="control-group">
								<label class="control-label" for="jumlah-pohon-verifikasi">Realisasi Penanaman</label>
								<div class="controls">
									<input type="text" name="jumlah-pohon-verifikasi" id="jumlah-pohon-verifikasi" class="" placeholder="" value="<?php if($edit && isset($lot_meta['jumlah-pohon-verifikasi'][0])){ echo $lot_meta['jumlah-pohon-verifikasi'][0]; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="luas-lot-verifikasi">Realisasi Luas Lot</label>
								<div class="controls">
									<input type="text" name="luas-lot-verifikasi" id="luas-lot-verifikasi" class="" placeholder="" value="<?php if($edit && isset($lot_meta['luas-lot-verifikasi'][0])){ echo $lot_meta['luas-lot-verifikasi'][0]; } ?>"> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputjudul">Verifikasi Data</label>
								<div class="controls">
									<label class="checkbox"><input type="checkbox" id="persil-verifikasi" name="persil-verifikasi" value="1" <?php if($edit && isset($lot_meta['persil-verifikasi'][0])){ echo 'checked="checked"'; } ?>>  No Persil / Objek Pajak</label>
									<label class="checkbox"><input type="checkbox" id="kerjasama-verifikasi" name="kerjasama-verifikasi" value="1" <?php if($edit && isset($lot_meta['kerjasama-verifikasi'][0])){ echo 'checked="checked"'; } ?>> Kerjasama</label>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputjudul"></label>
								<div class="controls">
									<p>Dengan memverifikasi saya menyatakan bahwa data yang tercatat benar adanya dan dapat dipertanggungjawabkan secara hukum yang berlaku di Republik Indonesia</p>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button id="submit-profile" type="submit" class="btn btn-primary">Simpan</button> <a href="<?php echo $back_url ;?>" class="btn">Batal</a>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			<?php endif ?>
			<?php if (in_array('status', $editable_field)): ?>
				<fieldset class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#form-lot" href="#data-status">
							Status Lot
						</a>
					</div>
					<div id="data-status" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="inputjudul">Status Lot</label>
								<div class="controls">
									<select name="status">
										<?php
											foreach( $status_array as $status ){
												if( $edit && isset($lot_status[0]) && $lot_status[0]->slug == $status){
													$selected = 'selected="selected"';
												} else {
													$selected = '';
												}
												echo '<option value="'.$status.'" '.$selected.'>'.str_replace('-', ' ', ucfirst($status)).'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button id="submit-profile" type="submit" class="btn btn-primary">Simpan</button> <a href="<?php echo $back_url ;?>" class="btn">Batal</a>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			<?php endif ?>
		</form>
	</div>
</div>