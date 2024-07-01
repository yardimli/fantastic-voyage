<div class="modal fade" id="image-picker" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
				<div class="d-flex align-items-center">
					<input type="search" class="form-control me-2" id="image-search-query" placeholder="{{ __('default.Search') }}"
					       style="width: auto;">
					<button class="btn btn-primary mr-2" id="quiz-image-search-button"><i class="fas fa-search"></i> {{ __('default.Search') }}
					</button>
				</div>
				<div class="d-flex align-items-center">
					<button id="prevPage" class="btn btn-secondary">{{ __('default.Prev') }}</button>
					<span id="currentPage" class="m-2">1</span>
					<button id="nextPage" class="btn btn-secondary me-2">{{ __('default.Next') }}</button>

					<button class="upload_image_btn btn btn-primary mr-2"><i class="fas fa-upload"></i> {{ __('default.Upload') }}</button>
					<input type="file" name="quiz_image" id="upload_image_file" accept="image/*" style="display: none;">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
			<div class="modal-body" style="min-height: calc(100vh - 200px); max-height: calc(100vh - 200px); overflow: auto;">
				<div class="row g-4" id="image-search-results">
					<div class="col-12 text-center">Image Powered by Pexels</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="audio-picker" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
				<div class="d-flex align-items-center">
					{{ __('default.Add Sound') }}
				</div>
				<div class="d-flex align-items-center">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label for="audio-tts-input">{{ __('default.Text') }}</label>
					<input type="text" class="form-control" id="audio-tts-input" placeholder="Enter Text"
					       value="Welcome to Fantastic Voyage!">
				</div>
				<div class="form-group">
					<label for="voice-input">{{ __('default.Voice') }}</label>
					<select class="form-control" id="voice-input">
						<!-- options generated dynamically -->
					</select>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between">
				<div class="d-flex align-items-start">
					<div class="upload_audio_btn btn btn-primary me-1"><i class="fas fa-upload"></i> {{ __('default.Upload') }}</div>
					<input type="file" name="quiz_audio" id="upload_audio_file" accept="audio/*" style="display: none;">
					<audio id="audio-preview" controls style="max-width: 200px; max-height: 34px; display: none;"></audio>
					<div id="play-audio-button" class="btn btn-info d-none me-1"><i class="fas fa-play"></i> {{ __('default.Play Voice') }}</div>
					<div id="generate-sound-button" class="btn btn-primary me-1">
						<div id="spinner" class="spinner-border" role="status"
						     style="display: none; width: 1rem; height: 1rem;"></div>
						{{ __('default.Generate') }}
					</div>
				</div>
				<div class="d-flex align-items-center">
					<div id="remove-audio-btn" class="btn btn-danger me-1">{{ __('default.Remove') }}</div>
					<div id="update-audio-btn" class="btn btn-primary">{{ __('default.Update') }}</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="delete-item-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				{{ __('default.Are you sure you want to delete this question?') }}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="delete-question-confirm">{{ __('default.Delete') }}</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('default.Cancel') }}</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit-group-text-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				{{ __('default.Edit Group Title') }}
			</div>
			<div class="modal-body">
				<input type="text" class="form-control group-text-input" placeholder="Enter Group Title"
				       value=""
				       style="font-size: 20px; font-weight: bold;">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="update-group-text">{{ __('default.Update') }}</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('default.Cancel') }}</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add-new-word-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				{{ __('default.Add a new word') }}
			</div>
			<div class="modal-body">
				<input type="text" class="form-control word-input" placeholder=""
				       value=""
				       style="font-size: 20px; font-weight: bold;">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="update-new-word">{{ __('default.Done') }}</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('default.Cancel') }}</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="create-article-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				{{ __('default.Create Article') }}
			</div>
			<div class="modal-body">
				<div class="language">
					<span>{{ __('default.Language') }}: </span>
					<select class="form-control" id="generate_article_language">
						<option value="English">English</option>
						<option value="Traditional Chinese">繁體中文</option>
						<option value="French">Français</option>
					</select>
				</div>
				<div class="subject">
					<span>{{ __('default.Subject') }}:  </span>
					<input type="text" class="form-control article-subject" placeholder=""
					       value=""
					       style="font-size: 20px; font-weight: bold;">
					<div id="article-example" class="mt-1" style="font-size: 10px; color: #888;">{{ __('default.For example') }}: Article about Taipei City</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-type="type" id="generate-article"><i id="spinIcon2" class="fa fa-spinner"></i>{{ __('default.Generate') }}</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('default.Cancel') }}</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add-content-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				{{ __('default.Add content') }}
			</div>
			<div class="modal-body">
				<div class="language">
					<span>{{ __('default.Language') }}: </span>
					<select class="form-control" id="generate_language">
						<option value="English">English</option>
						<option value="Traditional Chinese">繁體中文</option>
						<option value="French">Français</option>
					</select>
				</div>
				<div class="quantity">
					<span>{{ __('default.Quantity') }}: </span>
					<select class="form-control" id="generate_quantity">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				<div class="subject1">
					<span>{{ __('default.Subject') }}:  </span>
					<input type="text" class="form-control user-content" placeholder=""
					       value=""
					       style="font-size: 20px; font-weight: bold;">
					<div id="generate_example1" class="mt-1" style="font-size: 10px; color: #888;">{{ __('default.For example') }}:</div>
				</div>
				<div class="subject2" style="display: none;">
					<span>{{ __('default.Subject') }}:  </span>
					<input type="text" class="form-control user-content" placeholder=""
					       value=""
					       style="font-size: 20px; font-weight: bold;">
					<div id="generate_example2" class="mt-1" style="font-size: 10px; color: #888;">{{ __('default.For example') }}:</div>
				</div>
			</div>
			<div class="modal-footer">
{{--				<div class="progress progressContainer" style="margin: 20px; width: 100%;">--}}
{{--					<div id="progressbar" class="progress-bar" role="progressbar"></div>--}}
{{--				</div>--}}
{{--				<div id="progress_update_message"></div>--}}
				<button type="button" class="btn btn-danger" id="generate-content" data-type="type"><i id="spinIcon"
				                                                                                       class="fa fa-spinner"></i>{{ __('default.Generate') }}
				</button>
				<button type="button" class="btn btn-secondary" id="cancel-create-content" data-bs-dismiss="modal">{{ __('default.Cancel') }}
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="preview-confirm" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				{{ __('default.Please save the quiz before you preview.') }}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="save-preview">{{ __('default.Save') }}</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('default.Cancel') }}</button>
			</div>
		</div>
	</div>
</div>


<div id="tui-image-editor-container" style="position: fixed; top:0px; left:0px; display: none; z-index: 1080;"></div>
