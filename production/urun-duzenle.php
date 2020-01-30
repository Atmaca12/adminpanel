<?php 

include 'header.php'; 



$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:urun_id");
$urunsor->execute(array(
  'urun_id'=>$_GET['urun_id']
  ));

$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);

?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Genel Ayarlar <small>,

              <?php 

              if ($_GET['durum']=="ok") {?>

              <b style="color:green;">İşlem Başarılı...</b>

              <?php } elseif ($_GET['durum']=="no") {?>

              <b style="color:red;">İşlem Başarısız...</b>

              <?php }

              ?>


            </small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-urun" role="urun">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />

            <!-- / => en kök dizine çık ... ../ bir üst dizine çık -->
            <form action="../netting/islem.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
            

                <div class="form-group" >
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kategori seç <span class="required" ></span> </label>
                <div class="col-md-6 col-sm-6 col-xs-6" >
                     <?php

                    $urun_id=$uruncek['kategori_id'];

                    $kategorisor=$db->prepare("SELECT * FROM kategori where kategori_ust=:kategori_ust order by kategori_sira");
                    $kategorisor->execute(array(
                        'kategori_ust' =>0
                    ));

                      ?>

                    <select class="select2_multiple form-control" required="" name="kategori_id">
                       <?php 
                       
                       while($kategoricek=$kategorisor->fetch(PDO::FETCH_ASSOC)){
                            $kategori_id=$kategoricek['kategori_id'];
                       

                       ?> 
                       <option <?php if($kategori_id==$urun_id){echo "selected='select'";} ?> value="<?php echo $kategoricek['kategori_id'] ?>" >
                       <?php echo $kategoricek['kategori_ad'];  ?></option>

                       <?php } ?>

                       </select>
                </div>


                </div>




















              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> urun Ad<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_ad" value="<?php echo $uruncek['urun_ad'] ?>" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> urun detay<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_detay" value="<?php echo $uruncek['urun_detay'] ?>" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> urun keyword<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_keyword" value="<?php echo $uruncek['urun_keyword'] ?>"  class="form-control col-md-7 col-xs-12">
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> urun video<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_video" value="<?php echo $uruncek['urun_video'] ?>"  class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              
              


            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> urun fiyat<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_fiyat" value="<?php echo $uruncek['urun_fiyat']." TL" ?>" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> urun stok<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_stok" value="<?php echo $uruncek['urun_stok'] ?>" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>



              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">urun DURUM <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="heard" class="form-control" name="urun_durum" required="">
                  
                    <option value="1"  >AKTİF  
                    
                    </option>

                    <option value="0" >PASİF
                      

                    </option>

                  </select>

             
                </div>
              </div>



              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">urun onecıkar <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="heard" class="form-control" name="urun_onecikar" required="">
                  
                    <option value="1"  >evet  
                    
                    </option>

                    <option value="0" >hayır
                      

                    </option>

                  </select>

             
                </div>
              </div>





              <input type="hidden" name="urun_id" value="<?php echo  $uruncek['urun_id'] ?>">
              <div class="ln_solid"></div>
              <div class="form-group">
                <div align="right" class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="urunduzenle" class="btn btn-success">Güncelle</button>
                </div>
              </div>

            </form>



          </div>
        </div>
      </div>
    </div>



   


  </div>
</div>
<!-- /page content -->

<?php include 'footer.php'; ?>
