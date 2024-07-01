@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
	
	<style>
      .my-border {
          border: 1px solid #ccc;
      }
	</style>
	<pre>
		
		i have quiz with 2-6 answers,
the text inside the answers can be between 1 to 40 characters long
the answer buttons can have pictures inside
i need a code that will calculate how to best show the answers based on the target box width and height.

if the screen is wide it should prioritize using as few rows as possible, if the screen is portrait it should try to use more rows than using columns.

the example should have 5 answers, 1 with text and image, one with short text, one with a long text, one with image only and one with averge text.

write the code using phaser 3. display the answers as boxes with text and/or image inside them.
	
	
	WITH PICTURE AND TEXT IN QUESTION:
	6 ANSWERS:
	
	WIDE SCREEN:
	ROW: QUESTION:
	ROW:
		COL6: PICTURE
		COL6:
			COL6: ANSWERS
	
	MEDIUM SCREEN:
	ROW: QUESTION:
	ROW:
		COL8: PICTURE
		COL4:
			COL6: ANSWERS
	
	SMALL SCREEN:
	ROW: QUESTION:
	ROW:
		COL12: PICTURE
		COL12:
			COL2: ANSWERS
	
	SMALLER SCREEN:
	ROW: QUESTION:
	ROW:
		COL12: PICTURE
		COL12:
			COL4: ANSWERS
	
	PHONE PORTRAIT:
	ROW: QUESTION:
	ROW:
		COL12: PICTURE
		COL12:
			COL4: ANSWERS
	
	PHONE LANDSCAPE:
	ROW: QUESTION:
	ROW:
		COL6: PICTURE
		COL6:
			COL6: ANSWERS
	
	
	==================

	WITH ONLY TEXT IN QUESTION:
	6 ANSWERS:
	
	WIDE SCREEN:
	ROW: QUESTION:
	ROW:
		COL2: ANSWERS
		
	MEDIUM SCREEN, SMALL SCREEN, SMALLER SCREEN:
	ROW: QUESTION:
	ROW:
		COL4: ANSWERS
		
	PHONE PORTRAIT:
	ROW: QUESTION:
	ROW:
		COL6: ANSWERS
		
	PHONE LANDSCAPE:
	ROW: QUESTION:
	ROW:
		COL2: ANSWERS

		</pre>
	
	<div class="container my-border" style="min-height: 200px;">
		
		WITH PICTURE AND TEXT IN QUESTION:<BR>
		6 ANSWERS:
		
		<div class="row my-border">
			
			<div class="col-12 my-border">
				Where is the ball?
			</div>
		
		</div>
		
		<div class="row my-border">
			<div class="col-lg-6 col-md-12 col-12 my-border" style="min-height: 200px;">
				PICTURE
			</div>
			
			<div class="col-lg-6 col-md-12 col-12 my-border">
				<div class="row my-border">
					<div class="col-6 col-sm-6 col-md-6 col-lg-2 my-border">
						Answer #1
					</div>
					<div class="col-6 col-sm-6 col-md-6 col-lg-2 my-border">
						Answer #2
					</div>
					
					<div class="col-6 col-sm-6 col-md-6 col-lg-2 my-border">
						Answer #3
					</div>
					
					<div class="col-6 col-sm-6 col-md-6 col-lg-2 my-border">
						Answer #4
					</div>
					
					<div class="col-6 col-sm-6 col-md-6 col-lg-2 my-border">
						Answer #5
					</div>
					
					<div class="col-6 col-sm-6 col-md-6 col-lg-2 my-border">
						Answer #6
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="container my-border mt-5" style="min-height: 200px;">
		
		WITH ONLY TEXT IN QUESTION:<BR>
		6 ANSWERS:
		
		<div class="row my-border">
			
			<div class="col-12 my-border">
				Where is the ball?
			</div>
		
		</div>
		
		<div class="row my-border">
			<div class="col-lg-12 my-border">
				<div class="row my-border">
					<div class="col-6 col-sm-4 col-md-4 col-lg-2 my-border">
						Answer #1
					</div>
					<div class="col-6 col-sm-4 col-md-4 col-lg-2 my-border">
						Answer #2
					</div>
					
					<div class="col-6 col-sm-4 col-md-4 col-lg-2 my-border">
						Answer #3
					</div>
					
					<div class="col-6 col-sm-4 col-md-4 col-lg-2 my-border">
						Answer #4
					</div>
					
					<div class="col-6 col-sm-4 col-md-4 col-lg-2 my-border">
						Answer #5
					</div>
					
					<div class="col-6 col-sm-4 col-md-4 col-lg-2 my-border">
						Answer #6
					</div>
				</div>
			</div>
		
		</div>
	</div>

@endsection



@push('scripts')
	<script>
		var current_page = 'payment_end';
		$(document).ready(function () {
		});
	
	</script>
@endpush

<?php

?>
