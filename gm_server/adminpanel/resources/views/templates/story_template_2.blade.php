<?php //echo "<pre>";print_r($article);?>
	
<div class="row">
	<div class="col-md-8">
		<h1>{{$article['title']}}</h1>
		<p>
			{!!$article['final_content']!!}
		</p>
	</div>
	<div class="col-md-4">
		@if(!empty($article['image']))
			<img class="img-responsive" src="{{asset('uploads/articles/'.$article['image'])}}"/>
		@endif
	</div>
</div>	