<?php
$cs = Yii::app()->getClientScript();

$cs->registerCssFile(Yii::app()->theme->baseUrl. '/assets/plugins/lightbox2/css/lightbox.css');
$cs->registerScriptFile(Yii::app()->theme->baseUrl. '/assets/plugins/lightbox2/js/lightbox.min.js' , CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl. '/assets/plugins/mixitup/src/jquery.mixitup.js' , CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl. '/assets/js/pages-gallery.js' , CClientScript::POS_END);
?>
<!-- start: PAGE CONTENT -->
<style type="text/css">
	
	.panel-tools{
		filter: alpha(opacity=1);
		-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=1)";
		-moz-opacity: 1;
		-khtml-opacity: 1;
		opacity: 1;
	}

	.mix{ 
		height: 150px;
		/*width: 23.5%;*/
		background-color: white;
		display: inline-block;
		border:1px solid #666
		/*margin-right : 1.5%;*/
	}
	.mix a{
		color:black;
		font-weight: bold;
	}
	.mix .imgDiv{
		float:left;
		width:25%;
		margin-top:25px;
	}
	.mix .detailDiv{
		float:right;
		width:75%;
		margin-top:25px;
		padding-left:15px;
		text-align: left;
	}
	.mix .text-xss{font-size: 10px;}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-transparent">
			<div class="panel-body">
				<div class="controls">
					<h5>Filter Controls</h5>
					<ul class="nav nav-pills">
						<li class="filter active" data-filter="all">
							<a href="#">Show All</a>
						</li>
						<li class="filter" data-filter=".person">
							<a href="#"><i class="fa fa-user"></i> People</a>
						</li>
						<li class="filter" data-filter=".organization">
							<a href="#"><i class="fa fa-users"></i> Organizations</a>
						</li>
						<li class="filter" data-filter=".event">
							<a href="#"><i class="fa fa-calendar"></i> Events</a>
						</li>
						<li class="filter" data-filter=".project">
							<a href="#"><i class="fa fa-lightbulb-o"></i> Project</a>
						</li>
					</ul>
				</div>
				<hr/>
				<!-- GRID -->
				<ul id="Grid" class="list-unstyled">
					<?php 
					$memberId = Yii::app()->session["userId"];
					$memberType = Person::COLLECTION;
					$tags = array();
					$scopes = array(
						"codeInsee"=>array(),
						"codePostal"=>array(),
						"region"=>array(),
					);
					
					/* ************ ORGANIZATIONS ********************** */
					if(isset($organizations)) 
					{ 
						foreach ($organizations as $e) 
						{ 
							buildDirectoryLine($e, Organization::COLLECTION, Organization::CONTROLLER, Organization::ICON, $this->module->id,$tags,$scopes);
						};
					}

					/* ********** PEOPLE ****************** */
					if(isset($people)) 
					{ 
						foreach ($people as $e) 
						{ 
							buildDirectoryLine($e, Person::COLLECTION, Person::CONTROLLER, Person::ICON, $this->module->id,$tags,$scopes);
						}
					}

					/* ************ EVENTS ************************ */
					if(isset($events)) 
					{ 
						foreach ($events as $e) 
						{ 
							buildDirectoryLine($e, Event::COLLECTION, Event::CONTROLLER, Event::ICON, $this->module->id,$tags,$scopes);
						}
					}
	
					/* ************ PROJECTS **************** */
					if( count($projects) ) 
					{ 
						foreach ($projects as $e) 
						{ 
							buildDirectoryLine($e, Project::COLLECTION, Project::CONTROLLER, Project::ICON, $this->module->id,$tags,$scopes);
						}
					}
					/*
					<li class="col-md-3 col-sm-6 col-xs-12 mix kiki gallery-img" data-cat="1" id="">
						<div class="portfolio-item">
							<a class="thumb-info" href="" data-title="Website"  data-lightbox="all">
								<i class="fa fa-user"></i>
								<span class="thumb-info-title">Tihdfhd fghdfh dg dfgh tle</span>
							</a>
							<br/><br/><br/>
							<div class="chkbox"></div>
							<div class="tools tools-bottom">
								<a href="#" class="btnRemove" data-id="" data-name="" data-key="" >
									<i class="fa fa-trash-o"></i>
								</a>
							</div>
						</div>
					</li>
					*/
					function buildDirectoryLine( $e, $collection, $type, $icon, $moduleId, &$tags, &$scopes ){
							
							if(!isset( $e['_id'] ) || !isset( $e["name"]) || $e["name"] == "" )
								return;
							$actions = "";
							$id = @$e['_id'];

							/* **************************************
							* TYPE + ICON
							***************************************** */
							$img = '<i class="fa '.$icon.' fa-3x"></i> ';
							if ($e && isset($e["imagePath"])){ 
								$img = '<img width="50" height="50" alt="image" src="'.Yii::app()->createUrl('/'.$moduleId.'/document/resized/50x50'.$e['imagePath']).'">';
							}
							
							/* **************************************
							* TAGS FILTER
							***************************************** */							
							$tagsClasses = "";
							if(isset($e["tags"])){
								foreach ($e["tags"] as $key => $value) {
									$tagsClasses .= ' '.str_replace(" ", "", $value) ;
								}
							}

							/* **************************************
							* SCOPES FILTER
							***************************************** */
							$scopesClasses = "";
							if( isset($e["address"]) && isset( $e["address"]['codeInsee']) )
								$scopesClasses .= ' '.$e["address"]['codeInsee'];
							if( isset($e["address"]) && isset( $e["address"]['codePostal']) )
								$scopesClasses .= ' '.$e["address"]['codePostal'];
							if( isset($e["address"]) && isset( $e["address"]['region']) )
								$scopesClasses .= ' '.$e["address"]['region'];

							$strHTML = '<li id="'.$collection.(string)$id.'" class="col-md-3 col-sm-6 col-xs-12 mix '.$collection.'Line '.$type.' '.$scopesClasses.' '.$tagsClasses.'" data-cat="1" >'.
								'<div class="portfolio-item">'.
									'<div class="imgDiv">'.$img.'</div>'.
									'<div class="detailDiv"><a href="'.Yii::app()->createUrl('/'.$moduleId.'/'.$type.'/dashboard/id/'.$id).'" class="thumb-info" data-lightbox="all" >'.
										((isset($e["name"]))? $e["name"]:"").
									'</a>';
							
							/* **************************************
							* EMAIL for admin use only
							***************************************** */
							$strHTML .= '<br/><a class="text-xss" href="'.Yii::app()->createUrl('/'.$moduleId.'/'.$type.'/dashboard/id/'.$id).'">'.((isset($e["email"]))? $e["email"]:"").'</a>';

							/* **************************************
							* TAGS
							***************************************** */
							$strHTML .= '<div class="tools tools-bottom">';
							if(isset($e["tags"])){
								foreach ($e["tags"] as $key => $value) {
									$strHTML .= ' <a href="#" class="filter" data-filter=".'.str_replace(" ", "", $value).'"><span class="text-red text-xss">#'.$value.'</span></a>';
									if( $tags != "" && !in_array($value, $tags) ) 
										array_push($tags, $value);
								}
							}

							/* **************************************
							* SCOPES
							***************************************** */
							$strHTML .= '<br/>';
							if( isset($e["address"]) && isset( $e["address"]['codeInsee']) ){
								$strHTML .= ' <a href="#" class="filter" data-filter=".'.$e["address"]['codeInsee'].'"><span class="label label-danger text-xss">'.$e["address"]['codeInsee'].'</span></a>';
								if( !in_array($e["address"]['codeInsee'], $scopes['codeInsee']) ) 
									array_push($scopes['codeInsee'], $e["address"]['codeInsee'] );
							}
							if( isset($e["address"]) && isset( $e["address"]['codePostal']) ){
								$strHTML .= ' <a href="#" class="filter" data-filter=".'.$e["address"]['codePostal'].'"><span class="label label-danger text-xss">'.$e["address"]['codePostal'].'</span></a>';
								if( !in_array($e["address"]['codePostal'], $scopes['codePostal']) ) 
									array_push($scopes['codePostal'], $e["address"]['codePostal'] );
							}
							if( isset($e["address"]) && isset( $e["address"]['region']) ){
								$strHTML .= ' <a href="#" class="filter" data-filter=".'.$e["address"]['region'].'" ><span class="label label-danger text-xss">'.$e["address"]['region'].'</span></a>';
								if( !in_array($e["address"]['region'], $scopes['region']) ) 
									array_push($scopes['region'], $e["address"]['region'] );
							}	

						$strHTML .= '</div></div></div></li>';
						echo $strHTML;
					}
					?>
					
					
					
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- end: PAGE CONTENT-->


<script type="text/javascript">

var tabButton = [];
var mapButton = {"media": "Media", "slider": "Slider", "profil" : "Profil", "banniere" : "Banniere", "logo" : "Logo"};
var itemId = "";
var itemType = "";
var controllerId = ""

var authorizationToEdit = <?php echo (isset($canEdit) && $canEdit) ? 'true': 'false'; ?>; 
var images = [];

jQuery(document).ready(function() {
	
	initGrid();


	$("#backToDashboardBtn").off().on("click", function(){
		document.location.href=baseUrl+"/"+moduleId+"/"+controllerId+"/dashboard/id/"+itemId;
	})
});

function initGrid(){
	console.log(images);
	j = 1;
	$.each(images, function(k, v){
		j++;
		
		if($.inArray(k, tabButton)==-1){
			tabButton.push(k);
			var liHtml = '<li class="filter" data-filter=".'+k+'">'+
							'<a href="#">' + mapButton[k] + '</a>'+
						 '</li>';
			$(".nav-pills").append(liHtml);
		}
		//$.each(v, function(docId, document) {
		for(var i = 0; i<v.length; i++){
			var htmlBtn = "";
			if(authorizationToEdit){
				htmlBtn= ' <div class="tools tools-bottom">' +
								' <a href="#" class="btnRemove" data-id="'+v[i]["_id"]["$id"]+'" data-name="'+v[i].name+'" data-key="'+v[i].contentKey+'" >' +
									' <i class="fa fa-trash-o"></i>'+
								' </a>'+
							' </div>'
			}
			var path = baseUrl+v[i]["imageUrl"];
			var htmlThumbail = '<li class="col-md-3 col-sm-6 col-xs-12 mix '+k+' gallery-img" data-cat="1" id="'+v[i]["_id"]["$id"]+'">'+
						' <div class="portfolio-item">'+
							' <a class="thumb-info" href="'+path+'" data-title="Website"  data-lightbox="all">'+
								' <img src="'+path+'" class="img-responsive" alt="">'+
								' <span class="thumb-info-title">'+k+'</span>' +
							' </a>' +
							' <div class="chkbox"></div>' +
							htmlBtn +
						' </div>' +
					'</li>' ;
			$("#Grid").append(htmlThumbail);
		}
	})
	if(j>0){
		bindBtnGallery();
		$('#Grid').mixItUp();
		$('.portfolio-item .chkbox').bind('click', function () {
	        if ($(this).parent().hasClass('selected')) {
	            $(this).parent().removeClass('selected').children('a').children('img').removeClass('selected');
	        } else {
	            $(this).parent().addClass('selected').children('a').children('img').addClass('selected');
	        }
	    });
	}else{
		var htmlDefault = "<div class='center'>"+
							"<i class='fa fa-picture-o fa-5x text-blue'></i>"+
							"<br>No picture to show"+
						"</div>";
		$('#Grid').append(htmlDefault);
	}
}

function bindBtnGallery(){
	$(".portfolio-item .btnRemove").on("click", function(e){
		var imageId= $(this).data("id");
		var imageName= $(this).data("name");
		var key = $(this).data("key")
		e.preventDefault();
		bootbox.confirm("Are you sure you want to delete <span class='text-red'>"+$(this).data("name")+"</span> ?", 
			function(result) {
				if(result){
					$.ajax({
						url: baseUrl+"/"+moduleId+"/document/delete/dir/"+moduleId+"/type/"+itemType+"/parentId/"+itemId,
						type: "POST",
						dataType : "json",
						data: {"name": imageName, "parentId": itemId, "docId":imageId, "parentType": itemType, "pictureKey" : key, "path" : ""},
						success: function(data){
							if(data.result){
								$("#"+imageId).remove();
								toastr.success(data.msg);
							}else{
								toastr.error(data.error)
							}
						}
					})
				}
			})
	})
}
</script>