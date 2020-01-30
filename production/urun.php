<?php 

include 'header.php'; 

//Belirli veriyi seçme işlemi
$urunsor=$db->prepare("SELECT * FROM urun ORDER BY urun_id DESC");
$urunsor->execute();


?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>urunü Listeleme <small>,

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
            <div align="right">
            <a href="urun-ekle.php"><button class="btn btn-success btn-xs">Yeni Ekle</button></a>
            </div>

            <!-- Div İçerik Başlangıç -->

            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <td>s.no</td>
                  <th>urun ad</th>
                  <th>urun stok</th>
                  <th>urun fiyat</th>
                  <th>urun onecikar</th>
                  <th>urun durum</th>
                  <th>urun durum</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>

              <tbody>

                <?php 
                $say=0;
                while($uruncek=$urunsor->fetch(PDO::FETCH_ASSOC)) {$say++;?>


                <tr>
                  <td><?php echo $say; ?></td>
                  <td><?php echo $uruncek['urun_ad'] ?></td>
                  <td><?php echo $uruncek['urun_stok'] ?></td>
                  <td><?php echo $uruncek['urun_fiyat'] ?></td>
                  <td><?php 
                  
                  
                 if( $uruncek['urun_onecikar']==0){ ?>

                  
                 

                 <a href="../netting/islem.php?urun_id=<?echo $uruncek['urun_id'] ?>&urun_onecikar=1&urun_one=ok"><button   class="btn btn-success btn-xs">one cıkar</button></a>



                 <? }
                 

                 else if ($uruncek['urun_onecikar']==1){?>

                  <!-- urun onecikar butonu islem php ye get gonderimi unutucan buyuk ihtimalle  -->
                  <a href="../netting/islem.php?urun_id=<?echo $uruncek['urun_id'] ?>&urun_onecikar=0&urun_one=ok"><button   class="btn btn-danger btn-xs">kaldır</button></a>
                 <?} 
                
                
                ?>
                </td>



                  <td><?php 

                  if ($uruncek['urun_durum']==1) {?>
                    <button class="btn btn-success btn-xs">Aktif</button>
                  <?php } else { ?>

                    <button class="btn btn-danger btn-xs">Pasif</button>

                    <?php } ?>
                      

                    </td>


                  


                  <td><center><a href="urun-duzenle.php?urun_id=<?php echo $uruncek['urun_id'] ?>"><button class="btn btn-primary btn-xs">Düzenle</button></a></center></td>
                  <td><center><a href="../netting/islem.php?urun_id=<?php echo $uruncek['urun_id'] ?>&urunsil=ok"><button class="btn btn-danger btn-xs" >Sil</button></a></center></td>
                </tr>



                <?php  }

                ?>


              </tbody>
            </table>

            <!-- Div İçerik Bitişi -->


          </div>
        </div>
      </div>
    </div>




  </div>
</div>
<!-- /page content -->

<?php include 'footer.php'; ?>
