<div class="box-communecter box">
	
	<section>
		<?php /* ?>
		<br/><u><a href="#" class="text-white" onclick="showVideo('133636468')">En image <i class="fa fa-2x fa-youtube-play"></i></a> </u>
		php*/?>
		<style type="text/css">
		#communectMe{
			line-height: 35px;
			height : 60px;
			opacity : 0.6;
			border:none;
			width: 450px;
			font-weight: bold;
			font-size: 1.5em;
			border-radius: 10px;
			border-bottom : 1px solid #666;
		}
		</style>
		
		<div class="partition-white padding-20 radius-10">
			<br/><input type="text" class="center" name="communectMe" id="communectMe" placeholder="Your city or postal code"/>
			<div class="space20"></div>
			<span class="homestead text-red" >Communect</span> is part of <a href="#" class="text-red">the commons</a>
			<br/> Get together to build 
			<br/> <a href="#" class="text-red">smart, democratic and connected territories</a>
			<br/> Organize, develop and innovate 
			<br/> Localy, socialy and massively.

			<?php if( false && !isset( Yii::app()->session['userId']) ){?>
			<div class="space10"></div>
			<div class="row radius-10 padding-20" style="background-color: black">
				<iframe class=" col-sm-6" src="https://player.vimeo.com/video/133636468" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<iframe class="col-sm-6" src="https://player.vimeo.com/video/74212373" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			</div>
			<?php } ?>

			<div class="space20"></div>
			<div class="col-sm-6">
				Connected Cities
				<br/>
				<a href="#todo" class="text-red btn btn-xs btn-default">St Pierre</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">St Denis</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Lille</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Toulouse</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Paris</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Montpellier</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Nouméa</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Perpignan</a>
			</div>
			<div class="col-sm-6">
				Connected People
				<br/>
				<a href="#todo" class="text-red btn btn-xs btn-default">Tango</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Maroual</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Mr Green</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Oceatoon</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Jeronimo</a> 
				<a href="#todo" class="text-red btn btn-xs btn-default">Dori</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Rap pha</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Xabi</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Pierrot</a>
				<a href="#todo" class="text-red btn btn-xs btn-default">Pif</a>     
				<a href="#todo" class="text-red btn btn-xs btn-default">Chlorofib</a>    
				<a href="#todo" class="text-red btn btn-xs btn-default">JR</a>   
				<a href="#todo" class="text-red btn btn-xs btn-default">Costa</a>   
				<a href="#todo" class="text-red btn btn-xs btn-default">Ontologist</a>   
			</div>
		</div>
		
	</section>
	<div class="space20"></div>
	<hl/>
	<a href="#"  onclick="startIntro()" class="homestead nextBtns pull-left"><i class="fa fa-arrow-circle-o-left"></i> GUIDED TOUR  </a>
	<a href="#" onclick="showPanel('box-why','bggreen')" class="homestead nextBtns pull-right">WHY <i class="fa fa-arrow-circle-o-right"></i> </a>
</div>

<script type="text/javascript">

	jQuery(document).ready(function()
	{
		
	});
	
</script>