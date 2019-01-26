<?php $isAjax = app()->request->ajax();?>
<?php if($isAjax){?>
<h3>@yield('title', '后台管理系统')</h3>
@yield('body')
<?php return; }?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="robots" content="noindex,nofollow" />
    <title>@yield('title', '后台管理系统')</title>
	<div class="loading"></div>
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<style>
	table caption{display:none;}
    .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
	</style>
	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="https://cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="https://cdn.staticfile.org/twitter-bootstrap/4.2.1/js/bootstrap.min.js"></script>
		<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.2.1/css/bootstrap.min.css">
	
	<script src="https://cdn.staticfile.org/nprogress/0.2.0/nprogress.min.js"></script>
	<link rel="stylesheet" href="https://cdn.staticfile.org/nprogress/0.2.0/nprogress.min.css">

  </head>

<body>
@if (!empty(array_diff(app()->request->segments(), ['admin', 'user','login'])))
	@include('header', ['some' => 'data'])
@endif
@yield('body')


 
	<!-- alertModal -->
	<div id="alertPopover"></div>

<style>
.dropdown_caret {
  content: "";
  border-top: 0;
  border-bottom: 4px solid;
}

.sort:hover{
	cursor: pointer;
}
.dropdown:hover .dropdown-menu {
    display: block;
 }
 
 .bs-docs-footer {
    padding-top: 10px;
    padding-bottom:8px;
    margin-top: 15px;
    color: #767676;
    text-align: center;
    border-top: 1px solid #e5e5e5;
}

.desc{  
    float:right;
    display: inline-block;  
    border-left: 1px solid; border-bottom: 1px solid;  
    width: 8px; height: 8px;  
    transform: rotate(315deg);  
}  
.asc{  
    float:right;    
    display: inline-block;  
    border-left: 1px solid; border-bottom: 1px solid;  
    width: 8px; height: 8px;  
    transform: rotate(135deg);  
}  
</style>

<!-- 
<footer class="bs-docs-footer" role="contentinfo">
  <div class="container">
  </div>
</footer>
 -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
      -->
    </div>
  </div>
</div>

<script type="text/javascript">
function sbAlert(message)
{
	$("#alertModalMessage").html(message);
	$('#alertModal').modal('show');
}
//starting setting some animation when the ajax starts and completes
$(document).ajaxStart(function(){
	NProgress.start();
})
.ajaxComplete(function(){
	NProgress.done();
});
$(document).ready(function() { 
	NProgress.start();
}); 
$(window).on('load', function() { 
	NProgress.done();
}); 
$(function () {
  $('[data-toggle="popover"]').popover();

  $("th.sort").click(function(){
		var th_id = $(this).attr('id').replace('thead_','');
		var class_name = $(this).find('span').attr('class');
		var desc_or_asc  = 'desc';
		if(class_name.indexOf('asc') == '-1')
		{
			desc_or_asc = 'asc';
		}

		$("input[name=order_by]").val(th_id + "_" + desc_or_asc);
		$(".form-inline").submit();
	})

	if($("#senba_error_message").length>0)
	{
		setTimeout(function(){$("#senba_error_message").animate({opacity: 'toggle'}, "slow");}, 5000);
	}
	  
})

function formCheck(dom){
	
	if(dom.checkValidity() === false)
	{
		dom.classList.add('was-validated');
		return false;
	}
	return true;
}


function search_from(field, value)
{
	if($("select[name=search_field]").length > 0)
	{
		   $("select[name=search_field]").val(field);
	}
	else
	{
		$("<input name='search_field' type='hidden' value='"+field+"' />").appendTo($("form.form-inline"));
	}

	if($("input[name=search]").length > 0)
	{
		$("input[name=search]").val(value);
	}
	else
	{
		$("<input name='search' type='hidden' value='"+value+"' />").appendTo($("form.form-inline"));
	}
	
	$("form.form-inline").submit();
}
function empty(fData)
{
    return (typeof(fData) == 'undefined' ||(fData==null) || (fData.length==0) || fData == '0')
} 

function form_submit_callback(data)
{
	if(data.status == '1')
	{
		$("div[role='dialog']").modal('hide');
		sbAlert('提交成功');	
	}
	else
	{
		alert(data.info);
	}
	
}
/*
$("form.ajax").validate({
	  submitHandler: function(form) {
		  $.post($(form).attr('action'), 
				  $(form).serialize(), 
				  form_submit_callback,
				  "json"
				  );
		  return false;
	  },
	   errorPlacement: function(error, element) {
		  $(element).parent().addClass('has-error');  
			if($(element).next().hasClass('glyphicon'))
			{
				$(element).next().addClass('glyphicon-remove');
				$(element).next().removeClass('glyphicon-ok')
			}
			else
			{
    			var html = $('<span class="glyphicon glyphicon-remove form-control-feedback"></span>')
    			$(element).after(html);
			}
		},
		success: function(label, element) {
			$(element).parent().removeClass('has-error');  
			if($(element).next().hasClass('glyphicon'))
			{
				$(element).next().removeClass('glyphicon-remove');
				$(element).next().addClass('glyphicon-ok')
			}
			else
			{
    			var html = $('<span class="glyphicon glyphicon-ok form-control-feedback"></span>')
    			$(element).after(html);
			}
		}
});

*/
function delete_item(dom)
{
    var r =confirm("确认删除？");
    var url = $(dom).attr('data-url');
    if (r==true && url)
    {
		$.post(url, function(data) {
			if(data.status == 0)
			{
				$(dom).parents('tr').remove();
			}
		});
    }

}

function hide_popover(dom)
{
	$(dom).parents("[role=tooltip]").popover('hide')
}

function showModalByUrl(url){
	$.get(url, function(data){
		$('#exampleModal').find('.modal-body').html(data);
		$('#exampleModalLabel').html($('#exampleModal').find('.modal-body h3').html());
		$('#exampleModal').find('.modal-body h3').html('')
		$('#exampleModal').modal('show')
	})

}

function ajaxAddOptions(url, target) {
    target.empty();
    $.getJSON(url, function (json) {
        for(i in json)
        {   
            target.append("<option value='" + i + "'>" + json[i] + "</option>");
        }
    });
}
</script>

</body>
</html>