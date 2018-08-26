<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
  <h5 class="my-0 mr-md-auto font-weight-normal">管理中心</h5>
  <nav class="my-2 my-md-0 mr-md-3">
    <a class="p-2 @if (app()->request->segment(2) == 'depart') text-primary @else text-dark @endif" href="/admin/depart">部门管理</a>
    <a class="p-2 @if (app()->request->segment(2) == 'dicts') text-primary @else text-dark @endif" href="/admin/dicts">设置</a>
    <!-- 
    <a class="p-2 @if (app()->request->segment(2) == 'task') text-primary @else text-dark @endif" href="/admin/task">task</a>
    <a class="p-2 @if (app()->request->segment(2) == 'notice') text-primary @else text-dark @endif" href="/admin/notice">notice</a>
    <a class="p-2 @if (app()->request->segment(2) == 'vacation') text-primary @else text-dark @endif" href="/admin/vacation">vacation</a>
    <a class="p-2 @if (app()->request->segment(2) == 'repay') text-primary @else text-dark @endif" href="/admin/repay">repay</a>
     -->
  </nav>
  <a class="btn btn-outline-primary" href="/admin/user/logout">退出</a>
</div>