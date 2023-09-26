<div class="row ">	
	<div class="container-fluid">
		<div class="text-center" id="dataTable_paginate">
			@if ($paginator->lastPage() > 1)
				@if ($paginator->onFirstPage())
					<a > 首頁  | </a>
				    <a > 上一頁  | </a>
				@else
				    <a href="{{ $paginator->url(1) }}"> 首頁  | </a>
				    <a href="{{ $paginator->previousPageUrl() }}">上一頁 | </a>
				@endif
				@if ($paginator->currentPage() == $paginator->lastPage())
					<a > 下一頁  | </a>
				    <a > 最後一頁 </a>
				@else
				    <a href="{{ $paginator->nextPageUrl() }}"> 下一頁  | </a>
				    <a href="{{ $paginator->url($paginator->lastPage()) }}">最後一頁  </a>
				@endif
			@endif
			當前頁 {{ $paginator->currentPage() }} | 共 {{ $paginator->lastPage() }} 頁
		</div>
	</div>
</div>
