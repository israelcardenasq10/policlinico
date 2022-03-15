<?php $this->load->view("partial/header"); ?>
<!-- 
<header id="Carousel" class="carousel slide carousel-fade bg-black">  
        <ol class="carousel-indicators">
            <li data-target="#Carousel" data-slide-to="0" class="active"></li>
            <li data-target="#Carousel" data-slide-to="1"></li>
            <li data-target="#Carousel" data-slide-to="2"></li>
        </ol>

        <div class="carousel-inner">
            <div class="item active">
                <img src="<?=base_url()?>public/images/ima_1.jpg" class="img-responsive" alt="">
            </div>
           <div class="item">
                <img src="<?=base_url()?>public/images/ima_2.jpg" class="img-responsive" alt="">
            </div>
           <div class="item">
                <img src="<?=base_url()?>public/images/ima_3.jpg" class="img-responsive" alt="">
            </div>
        </div>

        <a class="left carousel-control" href="#Carousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#Carousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
</header>
-->

<!-- <input type="hidden" name="hd_lista_graf_1" id="hd_lista_graf_1" value="<?=$lista_graf_1?>"> -->

<div class="wrapper"> 
  <div class="content-wrapper">
    <section class="content">
        <div class="row" style="border: 0px solid red;">
          <div class="col-md-6" style="margin-top: 20px;">
            <!-- LINE CHART -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Productos m&aacute;s vendidos Hoy</h3>
              </div>
              <div class="box-body">
                <div class="chart">
                  <canvas id="chart_pie" width="450" height="220" ></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6" style="margin-top: 20px;">
            <!-- BAR CHART -->
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Ventas por d&iacute;as de la Semana</h3>
              </div>
              <div class="box-body">
                <div class="chart">
                  <canvas id="chart_linea" width="450" height="220"></canvas>
                </div>
              </div>
            </div>
            <!-- -->
          </div>
        </div>

        <div class="row" style="">
          <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Ventas por Meses <?=date('Y')?></h3>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="chart_bar" width="800" height="200" ></canvas>
                  </div>
                </div>
              </div>
          </div>
        </div>

    </section>
  </div>
</div>
<?php $this->load->view("partial/footer"); ?>