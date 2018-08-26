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
	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="/bootstrap-4.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/common.css">
	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="/js/jquery-1.9.1.min.js"></script>
	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="/bootstrap-4.1.1/js/bootstrap.min.js"></script>
	<script src="/js/nprogress.js"></script>

      <script src="/js/jedate/jquery.jedate.js"></script>
      <script src="/js/jedate/timer.js"></script>
	<link rel="stylesheet" href="/css/nprogress.css">
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
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">提示</h4>
      </div>
      <div class="modal-body" id="alertModalMessage">
       {message}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
      </div>
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
$(window).load(function() { 
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
			if(data.status == 1)
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